<?php
##################################################
# TITULO I - Retornar dados de infraestrutura
##################################################

/* PEGAR DADOS DO USUÁRIO */
function pegarDadosUsuario($id) {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    $queryString = "
    select nome,`nome.preferencia`,matricula,email,campus,tipo,turma,curso from alunos
    where id='$id'
    union
    select nome,`nome.preferencia`,matricula,email,campus,tipo,disciplinas,email from docentes
    where id= '$id'
    union
    select nome,`nome.preferencia`,matricula,email,campus,tipo,email,email from admins
    where id= '$id'
    limit 1";
    $query = mysqli_query($conn, $queryString);
    $array = mysqli_fetch_assoc($query);

    $nome = $array['nome'];
    $nomePreferencia = $array['nome.preferencia'];
    $matricula = $array['matricula'];
    $email = $array['email'];
    $tipo = $array['tipo'];
    $cursoID = $array['curso'];
    $curso = 'null';
    $turma = $array['turma'];
    $campusID = $array['campus'];
    $campus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM campus where id='$campusID'"))['nome'];
    if ($tipo == 'ALU') {
        $curso = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cursos where id='$cursoID'"))['nome'];
    }
    $jsonDisci = '';
    if ($tipo == 'DOC') {
        $cursoID = 'null';
        $jsonDisci = disciplinasUsuario($id);
        $turma = 'null';
    }
    if ($tipo == 'ADM') {
        $turma = 'null';
    }

    $turmas = getTurmasByCurso($cursoID, $campusID, false);
    $disciplinas = getDisciplinas(false);

    $retorno = "{
        \"id\": \"$id\", 
        \"nome\": \"$nome\", 
        \"nomePreferencia\":\"$nomePreferencia\",
        \"matricula\":\"$matricula\",
        \"email\":\"$email\",
        \"tipo\":\"$tipo\",
        \"cursoID\":\"$cursoID\",
        \"curso\":\"$curso\", 
        \"turma\":\"$turma\", 
        \"campusID\":\"$campusID\", 
        \"campus\":\"$campus\", 
        \"disciplinas\": {
            $jsonDisci
        },
        \"todasTurmas\": 
            $turmas
        ,
        \"todasDisciplinas\": 
            $disciplinas
     }";
    $retorno = preg_replace("/\r|\n/", '', $retorno);

    return $retorno;
}

/* Mudar dados */
function mudarDados($id, $nomePreferencia, $turma, $disci, $email, $img) {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/back-end/registro.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/fotos/fotos.php');

    $queryPessoaString = "
        select id,estado from alunos
        where id='$id' and estado='ATV'
        union
        select id,estado from docentes
        where id='$id' and estado='ATV'
        union
        select id,estado from admins
        where id='$id' and estado='ATV'
        limit 1";
    $queryPessoa = mysqli_query($conn, $queryPessoaString);
    if (mysqli_exist($queryPessoa)) {
        if ($nomePreferencia != null) {
            $queryString =
            "UPDATE alunos SET `nome.preferencia` = '$nomePreferencia' WHERE id='$id' and estado='ATV';
            UPDATE docentes SET `nome.preferencia` = '$nomePreferencia' WHERE id='$id' and estado='ATV';
            UPDATE admins SET `nome.preferencia` = '$nomePreferencia' WHERE id='$id' and estado='ATV';";
            $result = mysqli_multi_query($conn, $queryString);
            if ($result == false) {
                echo(mysqli_error($conn));
            }
            $conn->next_result();
            $conn->next_result();
        }
        if ($turma != null) {
            $queryString =
            "UPDATE alunos SET turma = '$turma' WHERE id='$id' and estado='ATV'";
            $result = mysqli_query($conn, $queryString);
            if ($result == false) {
                echo(mysqli_error($conn));
            }
        }
        if ($disci != null) {
            $queryString =
            "UPDATE docentes SET disciplinas = '$disci' WHERE id='$id' and estado='ATV';";
            $result = mysqli_query($conn, $queryString);
            if ($result == false) {
                echo(mysqli_error($conn));
            }
        }
        if ($email != null) {
            if (!emailJaCadastrado($email)) {
                $queryString =
                "UPDATE alunos SET email = '$email' WHERE id='$id' and estado='ATV';
                UPDATE docentes SET email = '$email' WHERE id='$id' and estado='ATV';
                UPDATE admins SET email = '$email' WHERE id='$id' and estado='ATV';";
                $result = mysqli_multi_query($conn, $queryString);
                if ($result == false) {
                    echo(mysqli_error($conn));
                }
            } else {
                return "EML";
            }
        }
        if ($img != null) {
            if ($img == 'REMOVE') {
                if (!removerImagem($id)) {
                    return 'IMG';
                }
            } else {
                if (!gravarImagem($id, $img)) {
                    return 'IMG';
                }
            }
        }
        echo 'OK';
    } else {
        echo 'ID';
    }
}

/* TURMAS */
function getTurmas() {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    $query = 'SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
    FROM turmas as tabelaTurma
    LEFT JOIN cursos as tabelaCurso
    on tabelaTurma.curso = tabelaCurso.id';
    $resultadoQuery = $conn->query($query);
    $arr = [];
    while ($linha = mysqli_fetch_array($resultadoQuery)) {
        $turma = $linha['codigo'];
        $curso = $linha['nome'];
        $arr[$curso][] = $turma;
    }

    return json_encode($arr);
}

function getTurmasByCampus($campus) {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    $query = "SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
    FROM turmas as tabelaTurma
    LEFT JOIN cursos as tabelaCurso
    on tabelaTurma.curso = tabelaCurso.id
    where tabelaTurma.campus='$campus';
    ";
    $resultadoQuery = $conn->query($query);
    $arr = [];
    while ($linha = mysqli_fetch_array($resultadoQuery)) {
        $turma = $linha['codigo'];
        $curso = $linha['nome'];
        $arr[$curso][] = $turma;
    }

    return json_encode($arr);
}

function getTurmasByCurso($curso, $campus, $echo = true) {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    $query = "SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
    FROM turmas as tabelaTurma
    LEFT JOIN cursos as tabelaCurso
    on tabelaTurma.curso = tabelaCurso.id where 
    tabelaTurma.curso='$curso' and tabelaTurma.campus='$campus'";

    $resultadoQuery = $conn->query($query);
    $arr = [];
    while ($linha = mysqli_fetch_array($resultadoQuery)) {
        $turma = $linha['codigo'];
        $curso = $linha['nome'];
        $arr[$curso][] = $turma;
    }
    if ($echo == true) {
        echo json_encode($arr);
    } else {
        return json_encode($arr);
    }
}

/* DISCIPLINAS */
function getDisciplinas($echo = true) {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    $query = 'select * from disciplinas';
    $arr = [];
    if ($req = mysqli_query($conn, $query)) {
        while ($row = mysqli_fetch_array($req)) {
            $id = $row['id'];
            $nome = $row['nome'];
            $arr[$id][] = $nome;
        }
        if ($echo == true) {
            echo json_encode($arr);
        } else {
            return json_encode($arr);
        }
    }
}

//disciplinasUsuario("YY2YC1DNYT");
function disciplinasUsuario($id) {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    //require("./main.php");
    $stringTodas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT disciplinas FROM docentes where id = '$id' and estado = 'ATV'"))['disciplinas'];
    $array = explode('-', $stringTodas);
    $arr = [];
    for ($i = 0; $i < count($array); $i++) {
        $disciplinaID = $array[$i];
        $nomeDisciplina = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM disciplinas where id='$disciplinaID'"))['nome'];
        $arr[$disciplinaID][] = $nomeDisciplina;
    }
    $json = json_encode($arr) . '';
    $json = str_replace('{', ' ', $json);
    $json = str_replace('}', ' ', $json);

    return $json;
}

##################################################
# TITULO II - Validação de dados
##################################################

function validarNomePreferivel($nomeCompleto, $teste) {
    $nomesArray = explode(' ', $nomeCompleto);
    $nome = $nomesArray[0];
    for ($i = 1; $i < count($nomesArray); $i++) {
        $final = $nome . ' ' . $nomesArray[$i];
        if ($final == $teste) {
            return 'SIM';
        }
    }

    return 'NAO';
}

function validarTurma($turma) {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    $turma = proteger($_GET['turma']);
    $queryString = "SELECT * FROM turmas where codigo='$turma' limit 1";
    $query = $conn->query($queryString);
    $numeroLinha = mysqli_num_rows($query);
    if ($numeroLinha == 0) {
        return 'NAO';
    }

    return 'SIM';
}

function validarDisciplina($disciplina) {
    require($_SERVER['DOCUMENT_ROOT'] . '/back-end/main.php');
    $queryString = "SELECT * FROM disciplinas where id='$disciplina' limit 1";
    $query = $conn->query($queryString);
    $numeroLinha = mysqli_num_rows($query);
    if ($numeroLinha == 0) {
        return 'NAO';
    }

    return 'SIM';
}
