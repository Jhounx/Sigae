<?php
/* Hierarquia das classes
    Admin > Atendimento > Registro > Usuario > Dados > Email > Validacao > Banco

    Esta classe gerencia todas as funções relacionadas aos atendimentos
*/

class Atendimentos extends Registro {
    public function pegarAtendimentoQuery($resultadoQuery) {
        $array = [];
        while ($linha = mysqli_fetch_array($resultadoQuery)) {
            $ar = ['nome', 'descricao', 'data_formatada', 'horarioInicio', 'horarioFim', 'docente', 'materia', 'sala', 'campus', 'tipo', 'limite', 'dataAgendamento', 'ultimaModificacao', 'estado'];
            $idAtendimento = $linha['id'];
            foreach ($ar as &$nha) {
                $te = $linha[$nha];
                if ($nha == 'limite' && $te == null) {
                    $te = 'none';
                } elseif ($nha == 'docente') {
                    $te = $this->getUsuarioById($te)[0];
                } elseif ($nha == 'materia') {
                    $te = $this->getDisciplinaById($te);
                } elseif ($nha == 'campus') {
                    $te = $this->getCampusById($te);
                } elseif ($nha == 'data_formatada') {
                    $nha = 'data';
                }
                $array[$idAtendimento][$nha] = $te;
            }
            $alunos = $this->pegarTodosALunosNoAtendimento($idAtendimento);
            $array[$idAtendimento]['aluno'] = $alunos;
        }
        if (count($array) == 0) {
            return '{}';
        }

        return json_encode($array);
    }

    public function pegarTodosALunosNoAtendimento($id) {
        $query = "SELECT * FROM atendimentos_alunos where atendimento = '$id'";
        $resultadoQuery = $this->conn->query($query);
        $array = [];
        while ($linha = mysqli_fetch_array($resultadoQuery)) {
            $alunoID = $linha['aluno'];
            $alunoArray = $this->getUsuarioById($alunoID);
            $nomeCompleto = $alunoArray[0];
            $nomePreferivel = $alunoArray[1];
            $presente = $linha['presente'];
            $confirmado = $linha['confirmado'];
            $array[$alunoID]['nomeCompleto'] = $nomeCompleto;
            $array[$alunoID]['nomePreferivel'] = $nomePreferivel;
            $array[$alunoID]['presente'] = $presente;
            $array[$alunoID]['confirmado'] = $confirmado;
        }
        if (count($array) == 0) {
            return [];
        }

        return $array;
    }

    ##################################################
    # Requerimentos
    ##################################################

    public function pegarTodosAtendimentosDocente($id) {
        $query = "SELECT *, UNIX_TIMESTAMP(data) AS dataUNIX, DATE_FORMAT (data,'%d/%m/%Y') AS data_formatada  
        FROM atendimentos WHERE docente='$id' ORDER BY FIELD(estado, 'CON', 'NAO', 'CAN', 'FIN') ASC, dataUNIX ASC";
        $resultadoQuery = $this->conn->query($query);

        return $this->pegarAtendimentoQuery($resultadoQuery);
    }

    public function pegarTodosAtendimentosDiscente($id) {
        $query = "SELECT *, UNIX_TIMESTAMP(data) AS dataUNIX, DATE_FORMAT (data, '%d/%m/%Y') AS data_formatada FROM atendimentos as tabelaAtendimento 
        LEFT JOIN atendimentos_alunos as tabelaALunos on tabelaAtendimento.id = tabelaALunos.atendimento where tabelaALunos.aluno = '$id' 
        ORDER BY FIELD(estado, 'CON', 'NAO', 'CAN', 'FIN') ASC, dataUNIX ASC";
        $resultadoQuery = $this->conn->query($query);

        return $this->pegarAtendimentoQuery($resultadoQuery);
    }

    public function pegarAtendimentoByID($id) {
        $query = "SELECT *, DATE_FORMAT (data, '%d/%m/%Y') AS data_formatada FROM atendimentos where id = '$id' limit 1";
        $resultadoQuery = $this->conn->query($query);

        return $this->pegarAtendimentoQuery($resultadoQuery);
    }

    /*
    public function adicionarAtendimento($array) {
        $id = $this->getIDnoCookie(['permissaoSistema']);
        if (isset($array['descricao']) and !$this->tamanhoString($array['descricao'], 50)) {
            return 'NO';
        }
        if ($this->isDate($array['data']) and
        $this->validarHorarioAtendimento($array['horarioFim'], $array['horarioInicio']) and
        $this->validarSala($array['sala']) and
        $this->validarDisciplina($array['materia']) == 'SIM' and
        $this->atendimentoExistInSala($array['sala'], $array['horarioInicio'], $array['horarioFim'], $array['data']) and
        $this->tamanhoString($array['nome'], 30)) {
            $queryString = 'INSERT INTO atendimentos (id, tipo, dataAgendamento, ultimaModificacao, estado, limite, campus, docente,';
            $sala = $this->createIDAtendimento('atendimentos');
            $campus = $this->getCampusNameById($this->campusUsuario($id));
            date_default_timezone_set('America/Sao_Paulo');
            $date_now = date('d/m/') . '20' . date('y') . ' ' . date('H:i');
            $limite = '-1';
            if (isset($array['limite']) and $this->isLimit($array['limite'])) {
                $limite = $array['limite'];
            }
            $res_query = " VALUES ('$sala', 'ATE', '$date_now', '$date_now', 'NAO', '$limite', '$campus', '$id',";
            foreach ($array as $key => $value) {
                $queryString = $queryString . " $key,";
                $res_query = $res_query . " '$value',";
            }
            $res_query = substr_replace($res_query, ')', strlen($res_query) - 1, 1);
            $queryString = substr_replace($queryString, ')', strlen($queryString) - 1, 1);
            $queryString = $queryString . $res_query;
            if (mysqli_query($this->conn, $queryString)) {
                return 'OK';
            }
            return 'ERROR';
        }
        return 'NO';
    }
    */

    /* Adicionar atendimento */
    public function agendarAtendimento($id, $nome, $desc, $data, $horarioInicio, $horarioFim, $sala, $materia, $tipo, $limite) {
        if ($this->tamanhoString($nome, 5, 30) and
        $this->tamanhoString($desc, 5, 50) and
        $this->isDate($data) and
        $this->validarHorarioAtendimento($horarioInicio, $horarioFim) and
        $this->validarSala($sala) and
        $this->verificarConflitos($data, $horarioInicio, $horarioFim, $sala) and
        $this->validarDisciplina($materia, true) and
        $this->validarTipo($tipo) and
        $this->isLimit($limite)) {
            $idAtendimento = $this->createIDAtendimento();
            $campus = $this->campusUsuario($id);
            $dataAtual = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('d/m/Y');
            $dataFormatada = DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');
            $queryString = "INSERT INTO atendimentos(id, nome, descricao, `data`, horarioInicio, horarioFim, docente, materia, sala, campus, tipo, limite, dataAgendamento) values(
                '$idAtendimento', '$nome', '$desc', '$dataFormatada', '$horarioInicio', '$horarioFim', '$id', '$materia', '$sala', '$campus', '$tipo', '$limite', '$dataAtual'
            )";
            if (mysqli_query($this->conn, $queryString)) {
                return "{\"id\": \"$idAtendimento\"}";
            }
            return '{}';
        } else {
            return '{}';
        }
    }

    public function setAtendimentos($array) {
        // $ar = $array[0];
        // $idAtend = $array[1];
        // $idDoc = $this->getIDnoCookie(['permissaoSistema']);
        // if (!$this->validarHorarioAtendimento($idAtend, $idDoc)[0]) {
        //     return 'NO_EXI';
        // }
        // $qr = $this->validarHorarioAtendimento($idAtend, $idDoc)[1];
        // foreach ($ar as $key => $value) {
        //     $essenc = ['data' => $this->isDate($value), 'sala' => $this->validarSala($value),
        //     'materia' => $this->validarDisciplina($value), 'horarioInicio' => true, 'horarioFim' => true, 'nome' => $this->tamanhoString($value, 30), 'descricao' => $this->tamanhoString($value, 50), 'limite' => $this->isLimit($value), ];
        //     if ($essenc[$key] == 'SIM' or $essenc[$key]) {
        //         $qr[$key] = $ar[$key];
        //     } else {
        //         return 'NO_VALID';
        //     }
        // }
        // if (isset($ar['horarioInicio']) or isset($ar['horarioFim'])) {
        //     if (!$this->verificarConflitos($qr['sala'], $qr['horarioInicio'], $qr['horarioFim'], $qr['data'], $idDoc)) {
        //         return 'NO_VALID';
        //     }
        // }
        // $queryString = 'UPDATE atendimentos SET';
        // $ar['ultimaModificacao'] = date('d/m/Y');
        // foreach ($ar as $key => $value) {
        //     $queryString = $queryString . " `$key` = '$value',";
        // }
        // $queryString = substr_replace($queryString, ' ', strlen($queryString) - 1, 1) . " WHERE id = '$idAtend'";
        // if (mysqli_query($this->conn, $queryString)) {
        //     return 'UPDATED';
        // }

        // return 'NO_UPDATE';
    }
}
