<?php

if (!isset($_SESSION)) {
    session_name('sessao');
    session_set_cookie_params(3600 * 24);
    session_start();
}

if (isset($_GET['request'])) {
    if (isset($_SESSION['permissaoSistema'])) {
        $id = $_SESSION['permissaoSistema'];
        getFoto($id);
    } else {
        echo 'Acesso negado';
    }
}

function getFoto($id) {
    header('Content-Type: image/png');
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/main.php");
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/back-end/fotos/db/" . $id . ".png")) {
        readfile($_SERVER['DOCUMENT_ROOT'] . "/back-end/fotos/db/" . $id . ".png");
    } else {
        readfile('../../icones/semFoto.png');
    }
}

function gravarImagem($id, $base64) {
    try {
        $ifp = fopen($_SERVER['DOCUMENT_ROOT'] . "/back-end/fotos/db/$id.png", 'wb');
        $datas = base64_decode($base64);
        list($width, $height) = getimagesizefromstring($datas); 
        if($width == 400 and $height == 400) {
            fwrite($ifp, base64_decode($base64));
            fclose($ifp);
        } else {
            return false;
        }
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function removerImagem($id) {
    return unlink($_SERVER['DOCUMENT_ROOT'] . "/back-end/fotos/db/$id.png");
}
