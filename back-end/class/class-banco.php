<?php
set_error_handler(function () {
    die('<h1>Falha na conexão com o banco de dados</h1>');
});
class Banco {

    private $servidor = '';
    private $usuario = '';
    private $senhaDB = '';
    private $dbname = '';

    public $conn;
    public $host;

    public function __construct() {
        $this->host = $_SERVER['HTTP_HOST'];
        $this->conn = mysqli_connect($this->servidor, $this->usuario, $this->senhaDB, $this->dbname);
        if (mysqli_connect_errno()) {
            echo "<h1>Erro ao conectar com o banco de dados</h1>";
            exit();
        }
    }

    function mysqli_exist($query) {
        $numeroLinha = mysqli_num_rows($query);
        return $numeroLinha > 0;
    }

    public function proteger($string) {
        $string = strip_tags($string);
        $string = addslashes($string);
        return $string;
    }

    public function getIDnoCookie($permissao) {
        if (!isset($_SESSION)) {
            session_name('sessao');
            session_set_cookie_params(3600 * 24);
            session_start();
        }
        return $_SESSION[$permissao];
    }

    public function verificarPermissao($permissoes, $id = '') {
        if ($GLOBALS["debug"] == false) {
            if (!isset($_SESSION)) {
                session_name('sessao');
                session_set_cookie_params(3600 * 24);
                session_start();
            }
            if (isset($_GET['id'])) {
                $id = $this->proteger($_GET['id']);
            }
            if (isset($_POST['id'])) {
                $id = $this->proteger($_POST['id']);
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
}