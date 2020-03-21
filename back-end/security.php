<?php
function proteger($string) {
    $string = strip_tags($string);
    $string = addslashes($string);
    return $string;
}

function verificarSessao($permissoes) {
    if($GLOBALS["debug"] == false) {
        if(!isset($_SESSION)) {
            session_name("sessao");
            session_start();
        }
        if(isset($_GET["id"])) {
            $id = proteger($_GET["id"]);
            $setou = 0;
            for ($i = 0; $i < count($permissoes); $i++) {
                $perm = $permissoes[$i];
                if(isset($_SESSION[$perm])) {
                    if($_SESSION[$perm] == $id) {
                        $setou = 1;
                    }
                }
            }
            if($setou == 0) {
                echo "Requisição negada! Não há uma sessão armazenada nesse dispositivo";
                die();
            }
        } else {
            echo "Requisição negada! Não apresentou-se um identificador válido";
            die();
        }
    }
}

function mysqli_exist($query) {
    $numeroLinha = mysqli_num_rows($query);
    return $numeroLinha > 0;
}