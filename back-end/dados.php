<?php
##################################################
# TITULO I - Retornar dados de infraestrutura
##################################################

/* PEGAR DADOS DO USUÁRIO */
function pegarDadosUsuario($id) {
    require("main.php");
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
    $curso = "null";
    $turma = $array['turma'];
    $campusID = $array['campus'];
    $campus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM campus where id='$campusID'"))['nome'];
    if($tipo == "ALU") {
        $curso = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cursos where id='$cursoID'"))['nome'];
    }
    $jsonDisci = "";
    if($tipo == "DOC") {
        $cursoID = "null";
        $jsonDisci = disciplinasUsuario($id);
        $turma = "null";
    }
    if($tipo == "ADM") {
        $turma = "null";
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
     $retorno = preg_replace( "/\r|\n/", "", $retorno );
     return $retorno;
}

/* TURMAS */
function getTurmas() {
    require(dirname(__FILE__) .'./main.php');
    require_once(dirname(__FILE__) .'./security.php');
    $query = "SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
    FROM turmas as tabelaTurma
    LEFT JOIN cursos as tabelaCurso
    on tabelaTurma.curso = tabelaCurso.id";
    $resultadoQuery= $conn->query($query);
    $arr = array();
    while ($linha = mysqli_fetch_array($resultadoQuery)) {
        $turma = $linha["codigo"];
        $curso = $linha["nome"];
        $arr[$curso][] = $turma;
    }
    return json_encode($arr);
}

function getTurmasByCampus($campus) {
    require(dirname(__FILE__) .'./main.php');
    require_once(dirname(__FILE__) .'./security.php');
    $query = "SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
    FROM turmas as tabelaTurma
    LEFT JOIN cursos as tabelaCurso
    on tabelaTurma.curso = tabelaCurso.id
    where tabelaTurma.campus='$campus';
    ";
    $resultadoQuery= $conn->query($query);
    $arr = array();
    while ($linha = mysqli_fetch_array($resultadoQuery)) {
        $turma = $linha["codigo"];
        $curso = $linha["nome"];
        $arr[$curso][] = $turma;
    }
    return json_encode($arr);
}

function getTurmasByCurso($curso, $campus, $echo = true) {
    require(dirname(__FILE__) .'./main.php');
    require_once(dirname(__FILE__) .'./security.php');
    $query = "SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
    FROM turmas as tabelaTurma
    LEFT JOIN cursos as tabelaCurso
    on tabelaTurma.curso = tabelaCurso.id where 
    tabelaTurma.curso='$curso' and tabelaTurma.campus='$campus'";

    $resultadoQuery= $conn->query($query);
    $arr = array();
    while ($linha = mysqli_fetch_array($resultadoQuery)) {
        $turma = $linha["codigo"];
        $curso = $linha["nome"];
        $arr[$curso][] = $turma;
    }
    if($echo == true) {
        echo json_encode($arr);
    } else {
        return json_encode($arr);
    }
}

/* DISCIPLINAS */
function getDisciplinas($echo = true) {
    require(dirname(__FILE__) .'./main.php');
    require_once(dirname(__FILE__) .'./security.php');
    $query = "select * from disciplinas";
    $arr = array();
    if ($req = mysqli_query($conn, $query)) {
        while ($row = mysqli_fetch_array($req)) {
            $id = $row["id"];
            $nome = $row["nome"];
            $arr[$id][] = $nome;
        }
        if($echo == true) {
            echo json_encode($arr);
        } else {
            return json_encode($arr);
        }
    }
}

//disciplinasUsuario("YY2YC1DNYT");
function disciplinasUsuario($id) {
    require(dirname(__FILE__) .'./main.php');
    //require("./main.php");
    $stringTodas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT disciplinas FROM docentes where id = '$id' and estado = 'ATV'"))['disciplinas'];
    $array = explode("-", $stringTodas);
    $arr = array();
    for($i = 0; $i < count($array); $i++) {
        $disciplinaID = $array[$i];
        $nomeDisciplina = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM disciplinas where id='$disciplinaID'"))['nome'];
        $arr[$disciplinaID][] = $nomeDisciplina;
    }
    $json = json_encode($arr) . "";
    $json = str_replace('{', ' ', $json);
    $json = str_replace('}', ' ', $json);
    return $json;
}

##################################################
# TITULO II - Validação de dados
##################################################

function validarNomePreferivel($nomeCompleto, $teste) {
    $nomesArray = explode(" ", $nomeCompleto);
    $nome = $nomesArray[0];
    for ($i = 1; $i < count($nomesArray); $i++) {
        $final = $nome . " " . $nomesArray[$i];
        if($final == $teste)
            return "SIM";
    }
    return "NAO";
}

function validarTurma($turma) {
    include("./main.php");
    $turma = proteger($_GET["turma"]);
    $queryString = "SELECT * FROM turmas where codigo='$turma' limit 1";
    $query = $conn->query($queryString);
    $numeroLinha = mysqli_num_rows($query);
    if($numeroLinha == 0) {
        return "NAO";
    } else {
        return "SIM";
    }
}

function validarDisciplina($disciplina) {
    include("./main.php");
    $queryString = "SELECT * FROM disciplinas where id='$disciplina' limit 1";
    $query = $conn->query($queryString);
    $numeroLinha = mysqli_num_rows($query);
    if($numeroLinha == 0) {
        return "NAO";
    } else {
        return "SIM";
    }
}