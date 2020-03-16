<?php
function proteger($string) {
    $string = strip_tags($string);
    $string = addslashes($string);
    return $string;
}

function verificarSessao($permissoes) {
    if($GLOBALS["debug"] == false) {
        session_name("sessao");
        session_start();
        if(isset($_GET["id"])) {
            $id = proteger($_GET["id"]);
            $setou = false;
            for ($i = 0; $i < count($permissoes); $i++) {
                $perm = $permissoes[$i];
                if(isset($_SESSION[$perm])) {
                    if($_SESSION[$perm] == $id) {
                        $setou = true;
                    }
                }
            }
            if($setou == false) {
                echo "Requisição negada! Não há uma sessão armazenada nesse dispositivo";
                die();
            }
        } else {
            echo "Requisição negada! Não apresentou-se um identificador válido";
            die();
        }
    }
}