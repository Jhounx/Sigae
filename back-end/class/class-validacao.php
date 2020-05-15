<?php
/* Hierarquia das classes
    Admin > Atendimento > Registro > Usuario > Dados > Email > Validacao > Banco

    Esta classe gerencia todas as validações de dados do sistema
*/

class Validacao extends Banco {
    /* Validação de registros */
    public function validarKey($key) {
        $queryString = "
        select id, nome,matricula,campus,estado,tipo,codigo_acesso,curso from alunos
        where codigo_acesso='$key' union
        select id, nome,matricula,campus,estado,tipo,codigo_acesso,disciplinas from docentes
        where codigo_acesso= '$key' union
        select id, nome,matricula,campus,estado,tipo,codigo_acesso,senha from admins
        where codigo_acesso= '$key' limit 1";
        $query = mysqli_query($this->conn, $queryString);
        $array = mysqli_fetch_assoc($query);
        $numeroLinha = mysqli_num_rows($query);
        if ($numeroLinha == 1) {
            $est = $array['estado'];
            if ($est == 'ATV' || $est == 'INA') {
                return "{'estado': '$est'}";
            } elseif ($est == 'REG' || $est == 'NUL') {
                $this->addPermissao($array['id'], 'permissaoRegistro');
                $cur = $array['curso'];
                $cpm = $array['campus'];
                $array['cursoID'] = $cur;
                $array['campusID'] = $cpm;
                $array['campus'] = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM campus where id='$cpm'"))['nome'];
                $array['curso'] = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM cursos where id='$cur'"))['nome'];
                unset($array['id']);

                return json_encode($array);
            }

            return '{}';
        }

        return '{}';
    }

    /* Validar existência dos dados */
    public function emailJaCadastrado($email) {
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

    public function validarNomePreferivel($nomeCompleto, $teste) {
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

    public function validarTurma($turma) {
        $turma = $this->proteger($_GET['turma']);
        $queryString = "SELECT * FROM turmas where codigo='$turma' limit 1";
        $query = $this->conn->query($queryString);
        $numeroLinha = mysqli_num_rows($query);
        if ($numeroLinha == 0) {
            return 'NAO';
        }

        return 'SIM';
    }

    public function validarSala($sala) {
        $query = mysqli_query($this->conn, "SELECT * FROM salas where sala='$sala'");
        if ($this->mysqli_exist($query)) {
            return true;
        }

        return false;
    }

    public function validarDisciplina($disciplina, $boolean = false) {
        $queryString = "SELECT * FROM disciplinas where id='$disciplina' limit 1";
        $query = $this->conn->query($queryString);
        $numeroLinha = mysqli_num_rows($query);
        if ($numeroLinha == 0) {
            if ($boolean) {
                return false;
            }

            return 'NAO';
        }
        if ($boolean) {
            return true;
        }

        return 'SIM';
    }

    /* Validação de atendimentos */

    public function isValidDate($date) {
        return DateTime::createFromFormat('d/m/Y', $date) ? true : false;
    }

    public function validarHorarioAtendimento($inicio, $fim) {
        return strtotime($fim) >= strtotime($inicio) ? true : false;
    }

    public function verificarDonoAtendimento($idDocente, $idAtendimento) {
        $queryString = "SELECT * FROM atendimentos where id='$idAtendimento' and docente='$idDocente' limit 1";
        $query = $this->conn->query($queryString);
        return $this->mysqli_exist($query);
    }

    public function verificarConflitos($data, $agoraInicio, $agoraFim, $sala, $excecao = null) {
        $data = DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        $queryString = "SELECT id, DATE_FORMAT (`data`,'%d/%m/%Y') AS data_formatada, 
        horarioInicio, horarioFim, sala FROM atendimentos where `data`='$data' and sala='$sala' and (estado = 'CON' or estado = 'NAO')";
        $query = $this->conn->query($queryString);
        if ($this->mysqli_exist($query)) {
            while ($linha = mysqli_fetch_array($query)) {
                foreach ($linha as $key => $v) {
                    if (is_int($key)) {
                        unset($linha[$key]);
                    }
                }
                $id = $linha['id'];
                $inicioDB = $linha['horarioInicio'];
                $fimDB = $linha['horarioFim'];
                if ($excecao != $id) {
                    if (((strtotime($agoraInicio) >= strtotime($inicioDB))
                    and (strtotime($agoraInicio) <= strtotime($fimDB)))
                    or (strtotime($agoraFim) >= strtotime($inicioDB))
                     and (strtotime($agoraFim) <= strtotime($fimDB))) {
                        return json_encode($linha);
                    }
                }
            }
        }

        return '{}';
    }

    public function isLimit($lim) {
        if ($lim == -1) {
            return true;
        }
        $lim = intval($lim);
        if ($lim < 1) {
            return false;
        }

        return true;
    }

    public function validarTipoAtendimento($tipo) {
        $ar = ['ATE', 'MON', 'EXT'];

        return in_array($tipo, $ar);
    }

    public function validarString($str, $tamanhoMin, $tamanhoMax) {
        if ($str == '') {
            return true;
        }
        if (strlen($str) < $tamanhoMin or strlen($str) > $tamanhoMax) {
            return false;
        }

        return true;
    }

    public function pegarNumeroALunosNoAtendimento($id) {
        $query = "SELECT atendimento FROM atendimentos_alunos where atendimento = '$id'";
        $resultadoQuery = $this->conn->query($query);
        return mysqli_num_rows($resultadoQuery);
    }

    public function verifyLimit($num_limit, $id) {
        if($num_limit == -1) {
            return true;
        }
        if($num_limit < $this->pegarNumeroALunosNoAtendimento($id)){
            return false;
        }
        return true;
    }
}
