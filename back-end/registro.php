<?php
function validarKey($key) {
    include("./main.php");
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
        $id = $array["id"];
        $nome = $array["nome"];
        $matricula = $array["matricula"];
        $estado = $array["estado"];
        $tipo = $array["tipo"];
        $cursoID = $array["curso"];
        $curso = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cursos where id='$cursoID'"))["nome"];
        if ($estado == "NUL") {
            session_name("registro");
            session_start();
            $_SESSION["id"] = $id;
            return "{\"id\": \"$id\", \"nome\":\"$nome\",\"matricula\":\"$matricula\",\"curso\":\"$curso\", \"tipo\":\"$tipo\", \"estado\":\"NUL\"}";
        }
        if ($estado == "ATV") {
            return "{\"estado\":\"ATV\"}";
        }
        if ($estado == "REG") {
            return "{\"id\": \"$id\", \"nome\":\"$nome\",\"matricula\":\"$matricula\",\"curso\":\"$curso\",\"tipo\":\"$tipo\", \"estado\":\"REG\"}";
        }
        if ($estado == "INA") {
            return "{\"estado\":\"INA\"}";
        }
    } else {
        return "{}";
    }
}

function registrarAluno($id, $nomePreferencial, $email, $senha, $turma, $disciplina) {
    include("./main.php");
    include("./validacoes.php");
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
            $nomeCompleto = $array["nome"];
            if(validarNomePreferivel($nomeCompleto, $nomePreferencial) == "NAO") {
                return "NOME";
            }

            /* verificar turma */
            $tipo = $array["tipo"];
            if($tipo == "ALU") {
                if(validarTurma($turma) == "NAO") {
                    return "TURMA";
                }
            }
            /* verificar disciplina */
            if($tipo == "DOC") {
                $arrayDisci = explode("-", $disciplina);
                for ($i = 0; $i < count($arrayDisci); $i++) {
                    $disci = $arrayDisci[$i];
                    if(validarDisciplina($disci) == "NAO") {
                        return "DISCI";
                    }
                }
            }
            /* verificar senha */
            if(strlen($senha) < 6) {
                return "SENHA";
            }
            $md5 = md5($senha);

            /* SQL */
            if($tipo == "ALU") {
                $queryInsert = "
                update alunos set `nome.preferencia` = '$nomePreferencial', senha = '$md5', turma = '$turma', email = '$email', estado = \"REG\" where id=\"$id\"";
            }
            if($tipo == "DOC") {
                $queryInsert = "
                update docentes set `nome.preferencia` = '$nomePreferencial', senha = '$md5', disciplinas = '$disciplina', email = '$email', estado = \"REG\" where id=\"$id\"";
            }
            if(mysqli_query($conn, $queryInsert)) {
                echo "{}";
            } else {
                echo "ERROR";
            }
        } else {
            return "ID";
        }
}