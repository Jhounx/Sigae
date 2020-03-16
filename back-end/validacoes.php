<?php
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