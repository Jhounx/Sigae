<?php
/* Hierarquia das classes
    Usuario > Dados > Registro > Validacao > Banco
*/

class Registro extends Validacao {

	/* Realizar registro */
	public function registrar($id, $nomePreferencial, $email, $senha, $turma, $disciplina) {
	    if($this->emailJaCadastrado($email)) {
	        return "EML";
	    }

	    $queryString = "
	        select id,nome,matricula,tipo,estado,codigo_acesso,curso from alunos
	        where id='$id'
	        union
	        select id,nome,matricula,tipo,estado,codigo_acesso,disciplinas from docentes
	        where id='$id'
	        union
	        select id,nome,matricula,tipo,estado,codigo_acesso,senha from admins 
	        where id='$id'
	        limit 1";
	    $query = mysqli_query($this->conn, $queryString);
	    $array = mysqli_fetch_assoc($query);
	    $numeroLinha = mysqli_num_rows($query);
	    if ($numeroLinha == 1) {
	        /* verificar nome */
	        $nomeCompleto = $array['nome'];
	        if ($this->validarNomePreferivel($nomeCompleto, $nomePreferencial) == 'NAO') {
	            return 'NOME';
	        }

	        /* verificar turma */
	        $tipo = $array['tipo'];
	        if ($tipo == 'ALU') {
	            if ($this->validarTurma($turma) == 'NAO') {
	                return 'TURMA';
	            }
	        }
	        /* verificar disciplina */
	        if ($tipo == 'DOC' || $tipo == 'MON') {
				$arrayDisci = explode('-', $disciplina);
				if(count($arrayDisci) > 5 || count($arrayDisci) <= 0) {
					return 'DISCI';
				}
	            for ($i = 0; $i < count($arrayDisci); $i++) {
	                $disci = $arrayDisci[$i];
	                if ($this->validarDisciplina($disci) == 'NAO') {
	                    return 'DISCI';
	                }
	            }
	        }
	        /* verificar senha */
	        if (strlen($senha) < 6) {
	            return 'SENHA';
	        }
	        $md5 = md5($senha);

	        /* SQL */
	        if ($tipo == 'ALU') {
	            $queryInsert = "
	                update alunos set `nome.preferencia` = '$nomePreferencial', senha = '$md5', turma = '$turma', email = '$email', estado = \"REG\" where id=\"$id\"";
	        }
	        if ($tipo == 'DOC' || $tipo == 'MON') {
	            $queryInsert = "
	                update docentes set `nome.preferencia` = '$nomePreferencial', senha = '$md5', disciplinas = '$disciplina', email = '$email', estado = \"REG\" where id=\"$id\"";
	        }
	        if ($tipo == 'ADM') {
	            $queryInsert = "
	                update admins set `nome.preferencia` = '$nomePreferencial', senha = '$md5', email = '$email', estado = \"REG\" where id=\"$id\"";
	        }
	        if (mysqli_query($this->conn, $queryInsert)) {
	            echo '{}';
	        } else {
	            echo 'ERROR';
	        }
	    } else {
	        return 'ID';
	    }
	}

	public function cancelarInscricao($id) {
	    $query = "
	        UPDATE alunos SET `nome.preferencia` = '', senha = '', turma = '', email = '', estado = 'NUL' WHERE id='$id' and estado='REG';
	        UPDATE docentes SET `nome.preferencia` = '', senha = '', disciplinas = '', email = '', estado = 'NUL' WHERE id='$id' and estado='REG';
	        UPDATE admins SET `nome.preferencia` = '', senha = '', email = '', estado = 'NUL' WHERE id='$id' and estado='REG';
	        DELETE FROM codigos_email WHERE id='$id'";
	    if (mysqli_multi_query($this->conn, $query)) {
	        $this->removerPermissao($id, "permissaoRegistro");
	        echo "OK";
	    } else {
	        echo "INV";
	    }
	}

	public function verificarSeJaValidou($id) {
		$queryString = "
			select id,estado from alunos
			where id='$id' and estado='ATV'
			union
			select id,estado from docentes
			where id='$id' and estado='ATV'
			union
			select id,estado from admins
			where id='$id' and estado='ATV'
			limit 1";
		$query = mysqli_query($this->conn, $queryString);
		if ($this->mysqli_exist($query)) {
			echo "SIM";
		} else {
			echo "NAO";
		}
	}

}

?>