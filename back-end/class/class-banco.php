<?php
/* Hierarquia das classes
    Usuario > Atendimento > Dados > Registro > Validacao > Admin > Banco

    Esta classe gerencia o banco de dados
*/

class Banco {

    /* Chaves principais do sistema. CUIDADO, NÃO PUBLIQUE ESSES CAMPOS PREENCHIDOS*/
    private $servidor = 'localhost';
    private $usuario = 'root';
    private $senhaDB = 'vertrigo';
    private $dbname = 'temp';

    /* Chaves super globais */
    public $conn;
    public $host;
    public $preFixHost = "http://";

    public function __construct() {
        $this->host = $this->preFixHost . $_SERVER['HTTP_HOST'];
        $this->conn = mysqli_connect($this->servidor, $this->usuario, $this->senhaDB, $this->dbname);
        if (mysqli_connect_errno()) {
            echo "<h1>Erro ao conectar com o banco de dados</h1>";
            exit();
        }
    }

    /* Função simplificadora de mysqli_num_rows */
    function mysqli_exist($query) {
        $numeroLinha = mysqli_num_rows($query);
        return $numeroLinha > 0;
    }

    /* Função obrigatória para $_GET ou $_POST, impede ataques por
    SQL injection e XSS
    */
    public function proteger($string) {
        $string = strip_tags($string);
        $string = addslashes($string);
        return $string;
    }

    /* Inicializa a sessão, obrigatório em funções onde deve ser usar $_SESSION */
    function init_session() {
        if (!isset($_SESSION)) {
            session_name('sessao');
            session_set_cookie_params(3600 * 24);
            session_start();
        }
    }

    /* Retornar a ID presente na sessão 
        Exemplo de invocação: verificarPermissao(['permissaoRegistro']);
            Permissões disponíveis:
                permissaoRegistro - Adquirida no /registrar
                permissaoSistema - Adquirida ao logar-se no /sistema
    */
    public function getIDnoCookie($permissao) {
        $this->init_session();
        for($i = 0; $i < count($permissao); $i++) {
            if(isset($_SESSION[$permissao[$i]]) && $_SESSION[$permissao[$i]] != null) {
                return $_SESSION[$permissao[$i]];
            }
        }
    }

    function pegarIDporCodigoEmails($codigo) {
        $query = mysqli_query($this->conn, "
        SELECT id FROM codigos_email where valor='$codigo'");
        if($this->mysqli_exist($query)) {
            return mysqli_fetch_assoc($query)['id'];
        } else {
            return null;
        }
    }

    /* Verifica se o usuário tem permissão para um certo comando 
        Parâmetro 1: array de permissões. Ex: ['permissaoSistema', 'permissaoRegistro']
        Parâmetro 2: ID, se for invocado do request.php, deixe em branco
        Parâmetro 3: Tipo do usuário. (DOC, ALU, MON)
    */
    public function verificarPermissao($permissoes, $id = '', $tipo = '') {
        if ($GLOBALS["debug"] == false) {
            $this->init_session();
            if($id == '') {
                $id = $this->getIDnoCookie($permissoes);
            }
            $permissaoTipo = 0;
            $setou = 0;
            if($tipo != '') {
                $permissaoTipo = -1;
            }
            for ($i = 0; $i < count($permissoes); $i++) {
                $perm = $permissoes[$i];
                if (isset($_SESSION[$perm])) {
                    if ($_SESSION[$perm] == $id) {
                        $setou = 1;
                        if($tipo != '') {
                            for ($index = 0; $index < count($tipo); $index++) {
                                $permitido = $tipo[$index];
                                if($_SESSION["tipo"] == $permitido) {
                                    $permissaoTipo = 1;
                                }
                            }
                        }
                    }
                }
            }
            if ($setou == 0) {
                echo 'EXPIRADO';
                die();
            }
            if ($permissaoTipo == -1) {
                echo 'NEG';
                die();
            }
        }
    }
    
    /* Adiciona à sessão uma certa permissão */
    function addPermissao($id, $nome) {
        $this->init_session();
        $_SESSION[$nome] = $id;
    }
    
    /* Remove da sessão uma certa permissão */
    function removerPermissao($nome) {
        $this->init_session();
        $_SESSION[$nome] = null;
    }

    /* Utils */
    
    function randomString($chars, $length) {
        $tamaho = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $tamaho - 1)];
        }
        return $randomString;
    }

    public function createIDUsuario(){
        $id = $this->randomString('QWERTYUIOPASDFGHJKLZXCVBNM1234567890', 10);
        $queryString = "
        select id from alunos
        where id='$id'
        union
        select id from docentes
        where id='$id'
        union
        select id from admins
        where id='$id'";
        $query = mysqli_query($this->conn, $queryString);
        while($this->mysqli_exist($query)){
            $id = $this->randomString('QWERTYUIOPASDFGHJKLZXCVBNM1234567890', 10);
            $query = mysqli_query($this->conn, $query);
        }
        return $id;
    }

    public function createIDAtendimento(){
        $id = $this->randomString('QWERTYUIOPASDFGHJKLZXCVBNM1234567890', 10);
        $queryString = "
        select id from atendimentos where id='$id'";
        $query = mysqli_query($this->conn, $queryString);
        while($this->mysqli_exist($query)){
            $id = $this->randomString('QWERTYUIOPASDFGHJKLZXCVBNM1234567890', 10);
            $query = mysqli_query($this->conn, $query);
        }
        return $id;
    }

    public function tirar_fundo($str){
        $str = substr_replace($str, ' ', strlen($str) -1, 1);
        return substr_replace($str, ' ', 0, 1);
    }
    
}