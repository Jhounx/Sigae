<?php
class Foto extends Banco {

    public function show($id = null) {
        if($id == null) {
            if ($this->getIDnoCookie("permissaoSistema") != null) {
                $id = $this->getIDnoCookie("permissaoSistema");
            } else {
                echo "Acesso negado";
            }
        }
        header('Content-Type: image/png');
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/back-end/class/fotosDB/' . $id . '.png')) {
            readfile($_SERVER['DOCUMENT_ROOT'] . '/back-end/class/fotosDB/' . $id . '.png');
        } else {
            readfile('../icones/semFoto.png');
        }
    }

    public function gravarImagem($id, $base64) {
        try {
            $ifp = fopen($_SERVER['DOCUMENT_ROOT'] . '/back-end/class/fotosDB/' . $id . '.png', 'wb');
            $datas = base64_decode($base64);
            list($width, $height) = getimagesizefromstring($datas);
            if ($width == 400 and $height == 400) {
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

    public function removerImagem($id) {
        return unlink($_SERVER['DOCUMENT_ROOT'] . '/back-end/class/fotosDB/' . $id . '.png');
    }
}
