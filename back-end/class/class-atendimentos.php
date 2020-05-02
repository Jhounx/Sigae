<?php
/* Hierarquia das classes
    Admin > Usuario > Atendimento > Dados > Registro > Validacao > Banco

    Esta classe gerencia todas as funções relacionadas aos atendimentos
*/

class Atendimentos extends Dados {
    public function pegarAtendimentoQuery($resultadoQuery) {
        $array = [];
        while ($linha = mysqli_fetch_array($resultadoQuery)) {
            $idAtendimento = $linha['id'];
            $nome = $linha['nome'];
            $descricao = $linha['descricao'];
            $data = $linha['data_formatada'];
            $horarioInicio = $linha['horarioInicio'];
            $horarioFim = $linha['horarioFim'];
            $docentes = $linha['docente'];
            $materia = $linha['materia'];
            $sala = $linha['sala'];
            $campus = $linha['campus'];
            $tipo = $linha['tipo'];
            $limite = $linha['limite'];
            if ($limite == null) {
                $limite = 'none';
            }
            $dataAgendamento = $linha['dataAgendamento'];
            $ultimaModificacao = $linha['ultimaModificacao'];
            $estado = $linha['estado'];

            $array[$idAtendimento]['data'] = $data;
            $array[$idAtendimento]['nome'] = $nome;
            $array[$idAtendimento]['descricao'] = $descricao;
            $array[$idAtendimento]['horarioInicio'] = $horarioInicio;
            $array[$idAtendimento]['horarioFim'] = $horarioFim;
            $array[$idAtendimento]['docente'] = $docentes;
            $array[$idAtendimento]['docenteNome'] = $this->getUsuarioById($docentes)[0];
            $array[$idAtendimento]['materia'] = $this->getDisciplinaById($materia);
            $array[$idAtendimento]['sala'] = $sala;
            $array[$idAtendimento]['campus'] = $this->getCampusById($campus);
            $array[$idAtendimento]['tipo'] = $tipo;
            $array[$idAtendimento]['dataAgendamento'] = $dataAgendamento;
            $array[$idAtendimento]['ultimaModificacao'] = $ultimaModificacao;
            $array[$idAtendimento]['limite'] = $limite;
            $array[$idAtendimento]['estado'] = $estado;
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
}
