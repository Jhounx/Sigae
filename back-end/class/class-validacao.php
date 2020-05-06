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
                unset($array["id"]);
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
            if($boolean) {
                return false;
            } else {
                return 'NAO';
            }
        }
        if($boolean) {
            return true;
        } else {
            return 'SIM';
        }
    }

    /* Validação de atendimentos */

    public function horaToMin($hora) {
        $ar = explode(':', $hora);
        $soma = intval($ar[0]) * 60 + intval($ar[1]);

        return $soma;
    }

    public function isHour($hora) {
        try {
            $ar = explode(':', $hora);
            if (count($ar) == 2) {
                $hour = $ar[0];
                $min = $ar[1];
                if (strlen($hour) != 2 || strlen($hour) != 2) {
                    return false;
                }
                $hour = intval($hour);
                $min = intval($min);
                if ($hour >= 24 || $hour < 0 || $min >= 60 || $min < 0) {
                    return false;
                }

                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function isDate($date) {
        try {
            $ar = explode('/', $date);
            if (count($ar) < 3) {
                return false;
            }
            $day = intval($ar[0]);
            $ms = intval($ar[1]);
            $ano = intval($ar[2]);
            if ($ano != date('Y')) {
                return false;
            } elseif ($ms < date('m')) {
                return false;
            } elseif ($ms == date('m') and $day < date('d')) {
                return false;
            }

            return checkdate($ms, $day, $ano);
        } catch (Exception $e) {
            return false;
        }
    }

    public function validarTipo($tipo){
        $ar = array('ATE', 'MON', 'EXT');
        return in_array($tipo, $ar);
    }

    public function validarHorarioAtendimento($tmpInicial, $tmpFinal) {
        $tmpFinal = explode(':', $tmpFinal);
        $ss_fn = ($tmpFinal[0] * 60) + $tmpFinal[1];

        $tmpInicial = explode(':', $tmpInicial);
        $ss_in = ($tmpInicial[0] * 60) + $tmpInicial[1];

        $ss_rs = $ss_fn - $ss_in;
        if ($ss_rs <= 0) {
            return false;
        }

        return true;
    }

    public function atendimentoExist($id, $docente) {
        $query = mysqli_query($this->conn, "SELECT * FROM atendimentos where id='$id' AND docente='$docente'");
        if ($this->mysqli_exist($query)) {
            return [true, mysqli_fetch_assoc($query)];
        }

        return false;
    }

    public function verificarConflitos($data, $agoraInicio, $agoraFim, $sala) {
        $data = DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        $queryString = "SELECT DATE_FORMAT (`data`,'%d/%m/%Y') AS data_formatada, horarioInicio, horarioFim, sala FROM atendimentos where `data`='$data' and sala='$sala' and (estado = 'CON' or estado = 'NAO')";
        $query = $this->conn->query($queryString);
        if ($this->mysqli_exist($query)) {
            $ar = mysqli_fetch_assoc($query);
            $inicioDB = $ar['horarioInicio'];
            $fimDB = $ar['horarioFim'];
            if (((strtotime($agoraInicio) >= strtotime($inicioDB)) 
            and (strtotime($agoraInicio) <= strtotime($fimDB))) 
            or (strtotime($agoraFim) >= strtotime($inicioDB)) 
            and (strtotime($agoraFim) <= strtotime($fimDB))) {
                return json_encode($ar);
            } 
            return '{}';
        }
        return '{}';
    }

    public function isLimit($lim) {
        if($lim == -1) {
            return true;
        }
        try {
            $lim = intval($lim);
            if ($lim < 1) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function tamanhoString($str, $tamanhoMin, $tamanhoMax) {
        if($str == '') {
            return true;
        }
        if (strlen($str) <= $tamanhoMin or strlen($str) >= $tamanhoMax) {
            return false;
        }

        return true;
    }
}
