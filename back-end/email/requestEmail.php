<?php
if (isset($_GET['codigo'])) {
    require('../main.php');
    require('../misc.php');
    require('../security.php');
    $value = proteger($_GET['codigo']);

    $queryCodigosTexto = "SELECT * FROM codigos_email where valor='$value' and tipo = 'VAL' limit 1";
    $queryCodigo = mysqli_query($conn, $queryCodigosTexto);
    $array = mysqli_fetch_assoc($queryCodigo);
    if (mysqli_exist($queryCodigo)) {
        $id = $array['id'];
        $queryPessoaTexto = "
        UPDATE alunos SET estado = 'ATV' WHERE id='$id';
        UPDATE docentes SET estado = 'ATV' WHERE id='$id';
        DELETE FROM codigos_email WHERE id='$id'";
        if (mysqli_multi_query($conn, $queryPessoaTexto)) {
            header('Location: ../../?reg=true');
        } else {
            echo('Error grave: ' . $conn -> error);
        }
    } else {
        echo 'Esse código não é válido. Tente novamente';
    }
}

#Criar código e enviar email
if (isset($_GET['emailValidacao']) && isset($_GET['id'])) {
    require('../main.php');
    require('../email/email.php');
    require('../misc.php');
    require('../security.php');

    $id = proteger($_GET['id']);
    $queryPessoaTexto = "
    select id, `nome.preferencia`, email, estado from alunos where id='$id' and estado='REG' 
    union
    select id, `nome.preferencia`, email, estado from docentes
    where id= '$id' and estado='REG'
    limit 1";
    $queryPessoa = mysqli_query($conn, $queryPessoaTexto);
    if (mysqli_exist($queryPessoa)) {
        $arrayPessoa = mysqli_fetch_assoc($queryPessoa);
        $email = $arrayPessoa['email'];
        $nome = $arrayPessoa['nome.preferencia'];

        $queryCodigosTexto = "SELECT * FROM codigos_email where id='$id' and tipo = 'VAL' limit 1";
        $queryCodigo = mysqli_query($conn, $queryCodigosTexto);
        $array = mysqli_fetch_assoc($queryCodigo);
        if (mysqli_exist($queryCodigo)) {
            $link = $host . '/back-end/email/requestEmail.php?codigo=' . $array['valor'];
            if (enviarEmail('SiGAÊ - Validação de email', 'validarEmail.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        } else {
            $codigo = randomString('1234567890', 50);
            $link = $host . '/back-end/email/requestEmail.php?codigo=' . $codigo;
            $queryInsert = "INSERT INTO codigos_email (id, valor, tipo) VALUES ('$id', '$codigo', 'VAL');";
            if (!mysqli_query($conn, $queryInsert)) {
                echo('Error grave: ' . $conn -> error);
                die();
            }
            if (enviarEmail('SiGAÊ - Validação de email', 'validarEmail.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        }
    } else {
        echo 'INV';
    }
}

if (isset($_GET['emailValidacao']) && isset($_GET['id'])) {
    require('../main.php');
    require('../email/email.php');
    require('../misc.php');
    require('../security.php');

    $id = proteger($_GET['id']);
    $queryPessoaTexto = "
    select id, `nome.preferencia`, email, senha, estado from alunos where id='$id' and estado='ATV' 
    union
    select id, `nome.preferencia`, email, senha, estado from docentes
    where id= '$id' and estado='ATV'
    limit 1";
    $queryPessoa = mysqli_query($conn, $queryPessoaTexto);
    if (mysqli_exist($queryPessoa)) {
        $arrayPessoa = mysqli_fetch_assoc($queryPessoa);
        $email = $arrayPessoa['email'];
        $nome = $arrayPessoa['nome.preferencia'];

        $queryCodigosTexto = "SELECT * FROM codigos_email where id='$id' and tipo = 'REC' limit 1";
        $queryCodigo = mysqli_query($conn, $queryCodigosTexto);
        $array = mysqli_fetch_assoc($queryCodigo);
        if (mysqli_exist($queryCodigo)) {
            $link = $host . '/back-end/email/recuperarSenha.php?codigo=' . $array['valor'];
            if (enviarEmail('SiGAÊ - Recuperação de senha', 'recuperacaoSenha.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        } else {
            $codigo = randomString('1234567890', 50);
            $link = $host . '/back-end/email/recuperarSenha.php?codigo=' . $codigo;
            $queryInsert = "INSERT INTO codigos_email (id, valor, tipo) VALUES ('$id', '$codigo', 'REC');";
            if (!mysqli_query($conn, $queryInsert)) {
                echo('Error grave: ' . $conn -> error);
                die();
            }
            if (enviarEmail('SiGAÊ - Recuperação de senha', 'recuperacaoSenha.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        }
    } else {
        echo 'INV';
    }
}
