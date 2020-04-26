<?php 
/* Hierarquia das classes
    Usuario > Dados > Registro > Validacao > Banco
*/
 
class Validacao extends Banco {

    function validarKey($key) {
        $queryString = "
        select id,nome,matricula,campus,estado,tipo,codigo_acesso,curso from alunos
        where codigo_acesso='$key'
        union
        select id,nome,matricula,campus,estado,tipo,codigo_acesso,disciplinas from docentes
        where codigo_acesso= '$key'
        union
        select id,nome,matricula,campus,estado,tipo,codigo_acesso,senha from admins
        where codigo_acesso= '$key'
        limit 1";
        $query = mysqli_query($this->conn, $queryString);
        $array = mysqli_fetch_assoc($query);
        $numeroLinha = mysqli_num_rows($query);
        if ($numeroLinha == 1) {
            $id = $array['id'];
            $nome = $array['nome'];
            $matricula = $array['matricula'];
            $estado = $array['estado'];
            $tipo = $array['tipo'];
            $cursoID = $array['curso'];
            $campusID = $array['campus'];
            $campus = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM campus where id='$campusID'"))['nome'];
            $curso = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM cursos where id='$cursoID'"))['nome'];
            if ($estado == 'NUL') {
                $this->addPermissao($id, "permissaoRegistro");
                return "{
                    \"id\": \"$id\", 
                    \"nome\":\"$nome\",
                    \"matricula\":\"$matricula\",
                    \"campusID\":\"$campusID\",
                    \"campus\":\"$campus\",
                    \"curso\":\"$curso\", 
                    \"cursoID\":\"$cursoID\",
                    \"tipo\":\"$tipo\", 
                    \"estado\":\"NUL\", 
                    \"key\":\"$key\"
                }";
            }
            if ($estado == 'ATV') {
                return '{"estado":"ATV"}';
            }
            if ($estado == 'REG') {
                $this->addPermissao($id, "permissaoRegistro");
                return "{
                    \"id\": \"$id\", 
                    \"nome\":\"$nome\",
                    \"matricula\":\"$matricula\",
                    \"campusID\":\"$campusID\",
                    \"campus\":\"$campus\",
                    \"curso\":\"$curso\",
                    \"cursoID\":\"$cursoID\",
                    \"tipo\":\"$tipo\", 
                    \"estado\":\"REG\", 
                    \"key\":\"$key\"
                }";
            }
            if ($estado == 'INA') {
                return '{"estado":"INA"}';
            }
        } else {
            return '{}';
        }
    }


    function emailJaCadastrado($email) {
        $queryString = "
            select email from alunos
            where email='$email'
            union
            select email from docentes
            where email='$email'
            union
            select email from admins 
            where email='$email'
            limit 1";
        $query = mysqli_query($this->conn, $queryString);
        return $this->mysqli_exist($query);
    }

    function validarNomePreferivel($nomeCompleto, $teste) {
        $nomesArray = explode(' ', $nomeCompleto);
        $nome = $nomesArray[0];
        for ($i = 1; $i < count($nomesArray); $i++) {
            $final = $nome . ' ' . $nomesArray[$i];
            if ($final == $teste) {
                return 'SIM';
            }
        }
        return 'NAO';
    }
    
    function validarTurma($turma) {
        $turma = $this->proteger($_GET['turma']);
        $queryString = "SELECT * FROM turmas where codigo='$turma' limit 1";
        $query = $this->conn->query($queryString);
        $numeroLinha = mysqli_num_rows($query);
        if ($numeroLinha == 0) {
            return 'NAO';
        }
        return 'SIM';
    }
    
    function validarDisciplina($disciplina) {
        $queryString = "SELECT * FROM disciplinas where id='$disciplina' limit 1";
        $query = $this->conn->query($queryString);
        $numeroLinha = mysqli_num_rows($query);
        if ($numeroLinha == 0) {
            return 'NAO';
        }
        return 'SIM';
    }

    function randomString($chars, $length) {
        $tamaho = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $tamaho - 1)];
        }
        return $randomString;
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
    
}


?>