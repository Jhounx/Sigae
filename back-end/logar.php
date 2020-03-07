<?php
function logar($login, $senha) {
    include("./database.php");
    $senha_md5 = md5($senha);
    $login = mysqli_real_escape_string($conn, $login);
    $query = "SELECT id, nome from alunos WHERE matricula='$login' and senha='$senha_md5' LIMIT 1";
    if ($req = mysqli_query($conn, $query)) {
        if ($fet = mysqli_fetch_assoc($req)) {
            $id = $fet["id"];
            session_start();
            $_SESSION['id'] = $id;
            $_SESSION['nome'] = $fet['nome'];
            return "true";
        } else {
            return "false";
        }
    } else {
        return "falsesql";
    }
}
