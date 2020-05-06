<?php
/* Hierarquia das classes
    Admin > Atendimento > Registro > Usuario > Dados > Email > Validacao > Banco

    Esta classe gerencia todas as funções de email
*/

class Email extends Validacao {
    /* Requisições */

    public function enviarEmailValidacao($id) {
        $queryPessoaTexto = "
        select id, `nome.preferencia`, email, estado from alunos where id= '$id' and estado='REG'
        union
        select id, `nome.preferencia`, email, estado from docentes where id= '$id' and estado='REG'
        union
        select id, `nome.preferencia`, email, estado from admins where id= '$id' and estado='REG'
        limit 1";
        $queryPessoa = mysqli_query($this->conn, $queryPessoaTexto);
        if ($this->mysqli_exist($queryPessoa)) {
            $arrayPessoa = mysqli_fetch_assoc($queryPessoa);
            $email = $arrayPessoa['email'];
            $nome = $arrayPessoa['nome.preferencia'];

            $queryCodigosTexto = "SELECT * FROM codigos_email where id='$id' and tipo = 'VAL' limit 1";
            $queryCodigo = mysqli_query($this->conn, $queryCodigosTexto);
            $array = mysqli_fetch_assoc($queryCodigo);
            if ($this->mysqli_exist($queryCodigo)) {
                $link = $this->host . '/back-end/requestEmail.php?codigo=' . $array['valor'];
                if ($this->enviarEmail('SiGAÊ - Validação de email', 'validarEmail.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                    echo 'OK';
                } else {
                    echo 'EML';
                }
            } else {
                $codigo = $this->randomString('1234567890', 50);
                $link = $this->host . '/back-end/requestEmail.php?codigo=' . $codigo;
                $queryInsert = "INSERT INTO codigos_email (id, valor, tipo) VALUES ('$id', '$codigo', 'VAL');";
                if (!mysqli_query($this->conn, $queryInsert)) {
                    echo('Error grave: ' . $this->conn -> error);
                    die();
                }
                if ($this->enviarEmail('SiGAÊ - Validação de email', 'validarEmail.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                    echo 'OK';
                } else {
                    echo 'EML';
                }
            }
        } else {
            echo 'INV';
        }
    }

    public function enviarEmailTrocarSenha($email) {
        $email = $this->proteger($_GET['email']);
        $queryPessoaTexto = "
        select id, `nome.preferencia`, email, estado from alunos where email='$email' and estado='ATV' 
        union
        select id, `nome.preferencia`, email, estado from docentes where email='$email' and estado='ATV'
        union
        select id, `nome.preferencia`, email, estado from admins where email='$email' and estado='ATV'
        limit 1";
        $queryPessoa = mysqli_query($this->conn, $queryPessoaTexto);
        if ($this->mysqli_exist($queryPessoa)) {
            $arrayPessoa = mysqli_fetch_assoc($queryPessoa);
            $id = $arrayPessoa['id'];
            $nome = $arrayPessoa['nome.preferencia'];
            $queryCodigosTexto = "SELECT * FROM codigos_email where id='$id' and tipo = 'REC' limit 1";

            $queryCodigo = mysqli_query($this->conn, $queryCodigosTexto);
            $array = mysqli_fetch_assoc($queryCodigo);
            if ($this->mysqli_exist($queryCodigo)) {
                $link = $this->host . '/back-end/requestEmail.php?codigo=' . $array['valor'];
                if ($this->enviarEmail('SiGAÊ - Recuperação de senha', 'recuperacaoSenha.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                    echo 'OK';
                } else {
                    echo 'EML';
                }
            } else {
                $codigo = $this->randomString('1234567890', 50);
                $link = $this->host . '/back-end/requestEmail.php?codigo=' . $codigo;
                $queryInsert = "INSERT INTO codigos_email (id, valor, tipo) VALUES ('$id', '$codigo', 'REC');";
                if (!mysqli_query($this->conn, $queryInsert)) {
                    echo('Error grave: ' . $this->conn -> error);
                    die();
                }
                if ($this->enviarEmail('SiGAÊ - Recuperação de senha', 'recuperacaoSenha.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                    echo 'OK';
                } else {
                    echo 'EML';
                }
            }
        } else {
            echo 'INV';
        }
    }

    public function enviarEmail($titulo, $nameFile, $propsName, $propsValues, $remetentes) {
        require($_SERVER['DOCUMENT_ROOT'] . '/back-end/class/phpMailer/PHPMailer.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/back-end/class/phpMailer/SMTP.php');

        $query = $this->conn->query('SELECT * from email limit 1');
        $array = mysqli_fetch_array($query);
        $servidor = $array['servidor'];
        $porta = $array['porta'];
        $endereco = $array['endereco'];
        $senha = $array['senha'];

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = false;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->CharSet = 'UTF-8';
        $mail->Host = $servidor;
        $mail->Port = $porta;
        $mail->IsHTML(true);
        $mail->Username = $endereco;
        $mail->Password = $senha;
        $mail->SetFrom($endereco);
        $mail->Subject = $titulo;

        $html = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/back-end/class/html/' . $nameFile);
        $body = str_replace($propsName, $propsValues, $html);

        $mail->Body = $body;
        for ($i = 0; $i < count($remetentes); $i++) {
            $mail->AddAddress($remetentes[$i]);
        }

        if ($mail->Send()) {
            return 'SIM';
        }

        return 'NAO';
    }

    public function validarRegistro($value) {
        $queryCodigosTexto = "SELECT * FROM codigos_email where valor='$value' and tipo = 'VAL' limit 1";
        $queryCodigo = mysqli_query($this->conn, $queryCodigosTexto);
        $array = mysqli_fetch_assoc($queryCodigo);
        if ($this->mysqli_exist($queryCodigo)) {
            $id = $array['id'];
            $queryPessoaTexto = "
        UPDATE alunos SET estado = 'ATV' WHERE id='$id';
        UPDATE docentes SET estado = 'ATV' WHERE id='$id';
        UPDATE admins SET estado = 'ATV' WHERE id='$id';
        DELETE FROM codigos_email WHERE id='$id'";
            if (mysqli_multi_query($this->conn, $queryPessoaTexto)) {
                header('Location: ../../?reg=true');
            } else {
                echo('Error grave: ' . $this->conn -> error);
            }
        } else {
            echo 'Esse código não é válido. Tente novamente';
        }
    }
}
