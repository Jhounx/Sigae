<?php
/* Hierarquia das classes
    Admin > Atendimento > Registro > Usuario > Dados > Email > Validacao > Banco

    Esta classe gerencia todas as funções relacionadas aos dados pessoais
*/

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
                    $this->init_session();
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
        $this->init_session();
        session_destroy();
        session_write_close();
    }

    /* PEGAR DADOS DO USUÁRIO */
    public function pegarDadosUsuario($id, $remetente = false, $dadosGerais = true, $turmaRemetente = null) {
        $this->init_session();
        $tipoRemetente = $_SESSION['tipo'];
        $queryString = "
	    select nome,`nome.preferencia`,matricula,email,campus,tipo,turma,curso,estado from alunos
	    where id='$id' and estado='ATV' union
	    select nome,`nome.preferencia`,matricula,email,campus,tipo,disciplinas,email,estado from docentes
	    where id= '$id' and estado='ATV' union
	    select nome,`nome.preferencia`,matricula,email,campus,tipo,email,email,estado from admins
	    where id= '$id' and estado='ATV' limit 1";
        $jsonDisci = ' ';
        $query = mysqli_query($this->conn, $queryString);
        if (!$this->mysqli_exist($query)) {
            echo '{}';
            exit();
        }
        $array = mysqli_fetch_assoc($query);
        $tipo = $array['tipo'];
        $turma = $array['turma'];
        if ($tipo == 'DOC' || $tipo == 'MON') {
            $jsonDisci = $this->tirar_fundo(json_encode($this->disciplinasUsuario($id)));
        }
        if ($remetente != false) {
            if ($tipoRemetente == 'ALU' and ($tipo == 'ALU' || $tipo == 'ADM') and $turmaRemetente != $turma) {
                echo '{}';
                exit();
            }
            if ($tipoRemetente == 'DOC' and $tipo == 'ADM') {
                echo '{}';
                exit();
            }
        }
        $campus = $array['campus'];
        $array['nomePreferencia'] = $array['nome.preferencia'];
        $array['campusID'] = $campus;
        $array['campus'] = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM campus where id='$campus'"))['nome'];
        $curso = 'null';
        if ($tipo == 'ALU') {
            $curso = $array['curso'];
            $array['cursoID'] = $curso;
            $array['curso'] = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM cursos where id='$curso'"))['nome'];
        }
        $enc = json_encode($array);
        $enc = substr_replace($enc, ',', strlen($enc) - 1, 1);
        $enc = $enc . ' "disciplinas":{' . $jsonDisci . '}}';
        if ($dadosGerais) {
            $enc = substr_replace($enc, ',', strlen($enc) - 1, 1);
            $salas = $this->getSalas($campus, false);
            $todasTurmas = $this->tirar_fundo(json_encode($this->getTurmasByCurso($curso, $campus, false)));
            $todasDisciplinas = $this->tirar_fundo(json_encode($this->getDisciplinas(false)));
            $enc = $enc . '"todasSalas":' . $salas . ', "todasTurmas" :' . $todasTurmas . ', "todasDisciplinas" :' . $todasDisciplinas . '}';
        }

        return $enc;
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

                    return 'ERR';
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

                    return 'ERR';
                }
            }
            if ($disci != null) {
                if (count(explode('-', $disci)) > 5 || count(explode('-', $disci)) <= 0) {
                    return 'DIS';
                }
                $queryString =
                "UPDATE docentes SET disciplinas = '$disci' WHERE id='$id' and estado='ATV';";
                $result = mysqli_query($this->conn, $queryString);
                if ($result == false) {
                    echo(mysqli_error($this->conn));

                    return 'ERR';
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

                        return 'ERR';
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

    /*### Dados do usuário ###*/

    public function disciplinasUsuario($id) {
        $stringTodas = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT disciplinas FROM docentes where id = '$id' and estado = 'ATV'"))['disciplinas'];
        $array = explode('-', $stringTodas);
        $arr = [];
        for ($i = 0; $i < count($array); $i++) {
            $disciplinaID = $array[$i];
            $nomeDisciplina = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM disciplinas where id='$disciplinaID'"))['nome'];
            $arr[$disciplinaID][] = $nomeDisciplina;
        }
        return $arr;
    }

    public function cursoUsuario($id) {
        return mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT curso FROM alunos where id = '$id'"))['curso'];
    }

    public function campusUsuario($id) {
        $queryString = "
        select id,campus from alunos
        where id='$id'
        union
        select id,campus from docentes
        where id='$id'
        union
        select id,campus from admins
        where id='$id'
        limit 1";
        $query = mysqli_query($this->conn, $queryString);
        return mysqli_fetch_assoc($query)['campus'];
    }
}
