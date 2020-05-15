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
            foreach ($ar as & $value) {
                $te = $linha[$value];
                if ($value == 'limite' && $te == null) {
                    $te = 'none';
                } elseif ($value == 'docente') {
                    $te = $this->getUsuarioById($te)[0];
                } elseif ($value == 'materia') {
                    $te = $this->getDisciplinaById($te);
                } elseif ($value == 'campus') {
                    $te = $this->getCampusById($te);
                } elseif ($value == 'data_formatada') {
                    $value = 'data';
                }
                $array[$idAtendimento][$value] = $te;
            }
            $alunos = $this->pegarTodosALunosNoAtendimento($idAtendimento);
            $array[$idAtendimento]['aluno'] = $alunos;
        }
        if (count($array) == 0) {
            return '{}';
        }
        return json_encode($array);
    }

    ##################################################
    # Requerimentos
    ##################################################

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

    public function pegarNumeroALunosNoAtendimento($id) {
        $query = "SELECT * FROM atendimentos_alunos where atendimento = '$id'";
        $resultadoQuery = $this->conn->query($query);
        return mysqli_num_rows($resultadoQuery);
    }

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

    public function pegarAtendimentoByID($id, $idRemetente = null) {
        if($idRemetente == null) {
            $query = "SELECT *, DATE_FORMAT (data, '%d/%m/%Y') AS data_formatada FROM atendimentos where id = '$id' limit 1";
        } else {
            $query = "SELECT *, DATE_FORMAT (data, '%d/%m/%Y') AS data_formatada FROM atendimentos where id = '$id' and docente='$idRemetente' limit 1";
        }
        $resultadoQuery = $this->conn->query($query);
        if($this->mysqli_exist($resultadoQuery)) {
            return $this->pegarAtendimentoQuery($resultadoQuery);
        } else {
            $this->deletarRegistroAlunosAtendimento($id);
            return $query;
        }
    }

    /* Adicionar atendimento */
    public function agendarAtendimento($id, $nome, $desc, $data, $horarioInicio, $horarioFim, $sala, $materia, $tipo, $limite) {
        if ($this->validarString($nome, 5, 30) and
        $this->validarString($desc, 5, 60) and
        $this->isValidDate($data) and
        $this->validarHorarioAtendimento($horarioInicio, $horarioFim) and
        $this->validarSala($sala) and
        $this->verificarConflitos($data, $horarioInicio, $horarioFim, $sala) and
        $this->validarDisciplina($materia, true) and
        $this->validarTipoAtendimento($tipo) and
        $this->isLimit($limite)) {
            $idAtendimento = $this->createIDAtendimento();
            $campus = $this->campusUsuario($id);
            $dataAtual = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('d/m/Y H:i');
            $dataFormatada = DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');
            $queryString = "INSERT INTO atendimentos(id, nome, descricao, `data`, horarioInicio, horarioFim, docente, materia, sala, campus, tipo, limite, dataAgendamento) values(
                '$idAtendimento', '$nome', '$desc', '$dataFormatada', '$horarioInicio', '$horarioFim', '$id', '$materia', '$sala', '$campus', '$tipo', '$limite', '$dataAtual'
            )";
            if (mysqli_query($this->conn, $queryString)) {
                return "{\"id\": \"$idAtendimento\"}";
            }
            return '{1}';
        }
        return '{2}';
    }

    public function alterarAtendimento($idDocente, $idAtendimento, $nome, $desc, $data, $horarioInicio, $horarioFim, $sala, $materia, $tipo, $limite) {
        if ($this->validarString($nome, 5, 30) and
        $this->verificarDonoAtendimento($idDocente, $idAtendimento) and
        $this->isValidDate($data) and
        $this->validarHorarioAtendimento($horarioInicio, $horarioFim) and
        $this->validarSala($sala) and
        $this->verificarConflitos($data, $horarioInicio, $horarioFim, $sala, $idAtendimento) and
        $this->validarDisciplina($materia, true) and
        $this->validarTipoAtendimento($tipo) and
        $this->isLimit($limite) and 
        $this->verifyLimit($limite, $idAtendimento)) {
            $dataFormatada = DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');
            $dataAtual = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('d/m/Y H:i');
            $queryString = "
            UPDATE atendimentos SET 
                nome = '$nome',
                descricao = '$desc',
                data = '$dataFormatada',
                horarioInicio = '$horarioInicio',
                horarioFim = '$horarioFim',
                materia = '$materia',
                sala = '$sala',
                limite = '$limite',
                ultimaModificacao = '$dataAtual' WHERE id='$idAtendimento';
            ";
            if (mysqli_query($this->conn, $queryString)) {
                return "{\"id\": \"$idAtendimento\"}";
            }
            return mysqli_error($this->conn);
        }
        return '{2}';
    }

    public function deletarRegistroAlunosAtendimento($id) {
        $queryString = "DELETE * FROM atendimentos_alunos WHERE atendimento = '$id'";
        return mysqli_query($this->conn, $queryString);
    }
}
