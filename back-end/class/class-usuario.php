<?php

class Usuario extends Dados {
    public function logar($login, $senha) {
        $senha_md5 = md5($senha);
        $queryString = "
		select id, matricula, senha, estado, tipo from alunos
		where matricula='$login' and estado='ATV'
		union
		select id, matricula, senha, estado, tipo from docentes
		where matricula= '$login' and estado='ATV'
		union
		select id, matricula, senha, estado, tipo from admins
		where matricula= '$login' and estado='ATV' limit 1
		";

        $query = mysqli_query($this->conn, $queryString);
        $array = mysqli_fetch_assoc($query);
        $numeroLinha = mysqli_num_rows($query);
        if ($numeroLinha == 1) {
            if ($senha_md5 == $array['senha']) {
                if ($array['estado'] == 'INA') {
                    return 'INA';
                }
                if ($array['estado'] == 'REG') {
                    return 'REG';
                }
                if ($array['estado'] == 'ATV') {
                    session_name('sessao');
                    session_set_cookie_params(3600 * 24);
                    session_start();
                    $_SESSION['permissaoSistema'] = $array['id'];
                    $_SESSION['tipo'] = $array['tipo'];

                    return 'CON';
                }
            } else {
                return 'SEN';
            }
        } else {
            return 'MAT';
        }
    }

    public function logout() {
        if (!isset($_COOKIE['sessao'])) {
            session_name('sessao');
            session_start();
        }
        session_destroy();
        session_write_close();
    }

    /* PEGAR DADOS DO USUÁRIO */
    public function pegarDadosUsuario($id, $remetente = false, $dadosGerais = true, $turmaRemetente = null) {
        $queryString = "
	    select nome,`nome.preferencia`,matricula,email,campus,tipo,turma,curso,estado from alunos
	    where id='$id' and estado='ATV'
	    union
	    select nome,`nome.preferencia`,matricula,email,campus,tipo,disciplinas,email,estado from docentes
	    where id= '$id' and estado='ATV'
	    union
	    select nome,`nome.preferencia`,matricula,email,campus,tipo,email,email,estado from admins
	    where id= '$id' and estado='ATV'
	    limit 1";
		$query = mysqli_query($this->conn, $queryString);
		if(!$this->mysqli_exist($query)) {
			echo "{}";
			exit();
		}
		$array = mysqli_fetch_assoc($query);
        $nome = $array['nome'];
        $nomePreferencia = $array['nome.preferencia'];
        $matricula = $array['matricula'];
        $email = $array['email'];
        $tipo = $array['tipo'];
        $cursoID = $array['curso'];
        $curso = 'null';
        $turma = $array['turma'];
		$campusID = $array['campus'];
		
		if($remetente != false) {
			if (!isset($_SESSION)) {
                session_name('sessao');
                session_set_cookie_params(3600 * 24);
                session_start();
			}
			$tipoRemetente = $_SESSION["tipo"];
			if($tipoRemetente == "ALU") {
				if($tipo == "ALU" || $tipo == "ADM") {
                    if($turmaRemetente != $turma) {
                        echo "{}";
                        exit();
                    }
				}
			}
			if($tipoRemetente == "DOC") {
				if($tipo == "ADM") {
					echo "{}";
					exit();
				}
			}
		}

        $campus = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM campus where id='$campusID'"))['nome'];
        if ($tipo == 'ALU') {
            $curso = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM cursos where id='$cursoID'"))['nome'];
        }
        $jsonDisci = '';
        if ($tipo == 'DOC' || $tipo == 'MON') {
            $cursoID = 'null';
            $jsonDisci = $this->disciplinasUsuario($id);
            $turma = 'null';
        }
        if ($tipo == 'ADM') {
            $turma = 'null';
        }

        $turmas = $this->getTurmasByCurso($cursoID, $campusID, false);
        $disciplinas = $this->getDisciplinas(false);

        if ($dadosGerais == true) {
            $retorno = "{
				\"id\": \"$id\", 
				\"nome\": \"$nome\", 
				\"nomePreferencia\":\"$nomePreferencia\",
				\"matricula\":\"$matricula\",
				\"email\":\"$email\",
				\"tipo\":\"$tipo\",
				\"cursoID\":\"$cursoID\",
				\"curso\":\"$curso\", 
				\"turma\":\"$turma\", 
				\"campusID\":\"$campusID\", 
				\"campus\":\"$campus\", 
				\"disciplinas\": {
					$jsonDisci
				},
				\"todasTurmas\": 
					$turmas
				,
				\"todasDisciplinas\": 
					$disciplinas
			 }";
        } else {
			$retorno = "{
				\"id\": \"$id\", 
				\"nome\": \"$nome\", 
				\"nomePreferencia\":\"$nomePreferencia\",
				\"matricula\":\"$matricula\",
				\"email\":\"$email\",
				\"tipo\":\"$tipo\",
				\"cursoID\":\"$cursoID\",
				\"curso\":\"$curso\", 
				\"turma\":\"$turma\", 
				\"campusID\":\"$campusID\", 
				\"campus\":\"$campus\",
				\"disciplinas\": {
					$jsonDisci
				}
			 }";
		}
        $retorno = preg_replace("/\r|\n/", '', $retorno);
        return $retorno;
    }

    public function disciplinasUsuario($id) {
        $stringTodas = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT disciplinas FROM docentes where id = '$id' and estado = 'ATV'"))['disciplinas'];
        $array = explode('-', $stringTodas);
        $arr = [];
        for ($i = 0; $i < count($array); $i++) {
            $disciplinaID = $array[$i];
            $nomeDisciplina = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM disciplinas where id='$disciplinaID'"))['nome'];
            $arr[$disciplinaID][] = $nomeDisciplina;
        }
        $json = json_encode($arr) . '';
        $json = str_replace('{', ' ', $json);
        $json = str_replace('}', ' ', $json);

        return $json;
    }

    /* Mudar dados */
    public function mudarDados($id, $nomePreferencia, $turma, $disci, $email, $img) {
        $queryPessoaString = "
	        select id,estado from alunos
	        where id='$id' and estado='ATV'
	        union
	        select id,estado from docentes
	        where id='$id' and estado='ATV'
	        union
	        select id,estado from admins
	        where id='$id' and estado='ATV'
	        limit 1";
        $queryPessoa = mysqli_query($this->conn, $queryPessoaString);
        if ($this->mysqli_exist($queryPessoa)) {
            if ($nomePreferencia != null) {
                $queryString =
                "UPDATE alunos SET `nome.preferencia` = '$nomePreferencia' WHERE id='$id' and estado='ATV';
	            UPDATE docentes SET `nome.preferencia` = '$nomePreferencia' WHERE id='$id' and estado='ATV';
	            UPDATE admins SET `nome.preferencia` = '$nomePreferencia' WHERE id='$id' and estado='ATV';";
                $result = mysqli_multi_query($this->conn, $queryString);
                if ($result == false) {
                    echo(mysqli_error($this->conn));
                }
                $this->conn->next_result();
                $this->conn->next_result();
            }
            if ($turma != null) {
                $queryString =
                "UPDATE alunos SET turma = '$turma' WHERE id='$id' and estado='ATV'";
                $result = mysqli_query($this->conn, $queryString);
                if ($result == false) {
                    echo(mysqli_error($this->conn));
                }
            }
            if ($disci != null) {
                $queryString =
                "UPDATE docentes SET disciplinas = '$disci' WHERE id='$id' and estado='ATV';";
                $result = mysqli_query($this->conn, $queryString);
                if ($result == false) {
                    echo(mysqli_error($this->conn));
                }
            }
            if ($email != null) {
                if (!$this->emailJaCadastrado($email)) {
                    $queryString =
                    "UPDATE alunos SET email = '$email' WHERE id='$id' and estado='ATV';
	                UPDATE docentes SET email = '$email' WHERE id='$id' and estado='ATV';
	                UPDATE admins SET email = '$email' WHERE id='$id' and estado='ATV';";
                    $result = mysqli_multi_query($this->conn, $queryString);
                    if ($result == false) {
                        echo(mysqli_error($this->conn));
                    }
                } else {
                    return 'EML';
                }
            }
            if ($img != null) {
                $f = new Foto();
                if ($img == 'REMOVE') {
                    if (!$f->removerImagem($id)) {
                        return 'IMG';
                    }
                } else {
                    if (!$f->gravarImagem($id, $img)) {
                        return 'IMG';
                    }
                }
            }
            echo 'OK';
        } else {
            echo 'ID';
        }
    }

    public function trocarSenha($id, $senha) {
        $this->removerPermissao($id, 'trocarSenha');
        if (strlen($senha) < 6) {
            return 'INV';
        }
        $md5 = md5($senha);
        $query = "
	        UPDATE alunos SET senha = '$md5' WHERE id='$id' and estado='ATV';
	        UPDATE docentes SET senha = '$md5' WHERE id='$id' and estado='ATV';
	        UPDATE admins SET senha = '$md5' WHERE id='$id' and estado='ATV';
	        DELETE FROM codigos_email WHERE id='$id';";
        if (mysqli_multi_query($this->conn, $query)) {
            echo 'OK';
        } else {
            echo 'INV';
        }
    }
}
