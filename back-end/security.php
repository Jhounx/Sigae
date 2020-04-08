<?php
function proteger($string) {
    $string = strip_tags($string);
    $string = addslashes($string);

    return $string;
}

function verificarSessao($permissoes, $id = '') {
    sleep(1);
    if ($GLOBALS["debug"] == false) {
        if (!isset($_SESSION)) {
            session_name('sessao');
            session_set_cookie_params(3600 * 24);
            session_start();
        }
        if (isset($_GET['id'])) {
            $id = proteger($_GET['id']);
        }
        if (isset($_POST['id'])) {
            $id = proteger($_POST['id']);
        }
        $setou = 0;
        for ($i = 0; $i < count($permissoes); $i++) {
            $perm = $permissoes[$i];
            if (isset($_SESSION[$perm])) {
                if ($_SESSION[$perm] == $id) {
                    $setou = 1;
                }
            }
        }
        if ($setou == 0) {
            echo 'NEGADO';
            die();
        }
    }
}

function addPermissao($id, $nome) {
    if (!isset($_SESSION)) {
        session_name('sessao');
        session_set_cookie_params(3600 * 24);
        session_start();
    }
    $_SESSION[$nome] = $id;
}

function removerPermissao($nome) {
    if (!isset($_SESSION)) {
        session_name('sessao');
        session_set_cookie_params(3600 * 24);
        session_start();
    }
    $_SESSION[$nome] = null;
}

function getIDByCodigo($codigo) {
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/main.php");
    $query = mysqli_query($conn, "
    SELECT id FROM codigos_email where valor='$codigo'
    ");
    if(mysqli_exist($query)) {
        return mysqli_fetch_assoc($query)['id'];
    } else {
        return null;
    }
}

function mysqli_exist($query) {
    $numeroLinha = mysqli_num_rows($query);

    return $numeroLinha > 0;
}
