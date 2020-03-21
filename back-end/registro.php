<?php
/* Verificar chave, criar sessão e retornar dados*/
function validarKey($key) {
    include('./main.php');
    $queryString = "
    select id,nome,matricula,estado,tipo,codigo_acesso,curso from alunos
    where codigo_acesso='$key'
    union
    select id,nome,matricula,estado,tipo,codigo_acesso,disciplinas from docentes
    where codigo_acesso= '$key'
    limit 1";
    $query = mysqli_query($conn, $queryString);
    $array = mysqli_fetch_assoc($query);
    $numeroLinha = mysqli_num_rows($query);
    if ($numeroLinha == 1) {
        $id = $array['id'];
        $nome = $array['nome'];
        $matricula = $array['matricula'];
        $estado = $array['estado'];
        $tipo = $array['tipo'];
        $cursoID = $array['curso'];
        $curso = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cursos where id='$cursoID'"))['nome'];
        if ($estado == 'NUL') {
            if(!isset($_SESSION)) {
                session_name('sessao');
                session_start();
            }
            $_SESSION['permissaoRegistro'] = $id;
            return "{\"id\": \"$id\", \"nome\":\"$nome\",\"matricula\":\"$matricula\",\"curso\":\"$curso\", \"tipo\":\"$tipo\", \"estado\":\"NUL\", \"key\":\"$key\"}";
        }
        if ($estado == 'ATV') {
            return '{"estado":"ATV"}';
        }
        if ($estado == 'REG') {
            if(!isset($_SESSION)) {
                session_name('sessao');
                session_start();
            }
            $_SESSION['permissaoRegistro'] = $id;
            return "{\"id\": \"$id\", \"nome\":\"$nome\",\"matricula\":\"$matricula\",\"curso\":\"$curso\",\"tipo\":\"$tipo\", \"estado\":\"REG\", \"key\":\"$key\"}";
        }
        if ($estado == 'INA') {
            return '{"estado":"INA"}';
        }
    } else {
        return '{}';
    }
}

/* Realizar registro */
function registrarAluno($id, $nomePreferencial, $email, $senha, $turma, $disciplina) {
    include('./main.php');
    include('./validacoes.php');
    sleep(3);
    $queryString = "
        select id,nome,matricula,tipo,estado,codigo_acesso,curso from alunos
        where id='$id'
        union
        select id,nome,matricula,tipo,estado,codigo_acesso,disciplinas from docentes
        where id='$id'
        limit 1";
    $query = mysqli_query($conn, $queryString);
    $array = mysqli_fetch_assoc($query);
    $numeroLinha = mysqli_num_rows($query);
    if ($numeroLinha == 1) {
        /* verificar nome */
        $nomeCompleto = $array['nome'];
        if (validarNomePreferivel($nomeCompleto, $nomePreferencial) == 'NAO') {
            return 'NOME';
        }

        /* verificar turma */
        $tipo = $array['tipo'];
        if ($tipo == 'ALU') {
            if (validarTurma($turma) == 'NAO') {
                return 'TURMA';
            }
        }
        /* verificar disciplina */
        if ($tipo == 'DOC') {
            $arrayDisci = explode('-', $disciplina);
            for ($i = 0; $i < count($arrayDisci); $i++) {
                $disci = $arrayDisci[$i];
                if (validarDisciplina($disci) == 'NAO') {
                    return 'DISCI';
                }
            }
        }
        /* verificar senha */
        if (strlen($senha) < 6) {
            return 'SENHA';
        }
        $md5 = md5($senha);

        /* SQL */
        if ($tipo == 'ALU') {
            $queryInsert = "
                update alunos set `nome.preferencia` = '$nomePreferencial', senha = '$md5', turma = '$turma', email = '$email', estado = \"REG\" where id=\"$id\"";
        }
        if ($tipo == 'DOC') {
            $queryInsert = "
                update docentes set `nome.preferencia` = '$nomePreferencial', senha = '$md5', disciplinas = '$disciplina', email = '$email', estado = \"REG\" where id=\"$id\"";
        }
        if (mysqli_query($conn, $queryInsert)) {
            echo '{}';
        } else {
            echo 'ERROR';
        }
    } else {
        return 'ID';
    }
}

function cancelarInscricao($id) {
    include('./main.php');
    $query = "
        UPDATE alunos SET `nome.preferencia` = '', senha = '', turma = '', email = '', estado = 'NUL' WHERE id='$id' and estado='REG';
        UPDATE docentes SET `nome.preferencia` = '', senha = '', disciplinas = '', email = '', estado = 'NUL' WHERE id='$id' and estado='REG';
        DELETE FROM codigos_email WHERE id='$id'";
    if (mysqli_multi_query($conn, $query)) {
        echo "OK";
    } else {
        echo "INV";
    }
}

function registroAcabou($id) {
    include('./main.php');require('./misc.php');
    $queryString = "
        select id,estado from alunos
        where id='$id' and estado='ATV'
        union
        select id,estado from docentes
        where id='$id' and estado='ATV'
        limit 1";
    $query = mysqli_query($conn, $queryString);
    if (mysqli_exist($query)) {
        echo "SIM";
    } else {
        echo "NAO";
    }
}