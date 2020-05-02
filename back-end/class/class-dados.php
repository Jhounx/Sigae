<?php
/* Hierarquia das classes
    Admin > Usuario > Atendimento > Dados > Registro > Validacao > Banco

    Esta classe gerencia dados de infraestrutura e outros diversos
*/

class Dados extends Registro {
    public function getTurmas() {
        $query = '
        SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
        FROM turmas as tabelaTurma
        LEFT JOIN cursos as tabelaCurso
        on tabelaTurma.curso = tabelaCurso.id';
        $resultadoQuery = $this->conn->query($query);
        $arr = [];
        while ($linha = mysqli_fetch_array($resultadoQuery)) {
            $turma = $linha['codigo'];
            $curso = $linha['nome'];
            $arr[$curso][] = $turma;
        }

        return json_encode($arr);
    }

    public function getTurmasByCampus($campus) {
        $query = "
        SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
        FROM turmas as tabelaTurma
        LEFT JOIN cursos as tabelaCurso
        on tabelaTurma.curso = tabelaCurso.id
        where tabelaTurma.campus='$campus';";
        $resultadoQuery = $this->conn->query($query);
        $arr = [];
        while ($linha = mysqli_fetch_array($resultadoQuery)) {
            $turma = $linha['codigo'];
            $curso = $linha['nome'];
            $arr[$curso][] = $turma;
        }

        return json_encode($arr);
    }

    public function getTurmasByCurso($curso, $campus, $echo = true) {
        $query = "
        SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
        FROM turmas as tabelaTurma
        LEFT JOIN cursos as tabelaCurso
        on tabelaTurma.curso = tabelaCurso.id where 
        tabelaTurma.curso='$curso' and tabelaTurma.campus='$campus'";

        $resultadoQuery = $this->conn->query($query);
        $arr = [];
        while ($linha = mysqli_fetch_array($resultadoQuery)) {
            $turma = $linha['codigo'];
            $curso = $linha['nome'];
            $arr[$curso][] = $turma;
        }
        if ($echo == true) {
            echo json_encode($arr);
        } else {
            return json_encode($arr);
        }
    }

    public function getDisciplinas($echo = true) {
        $query = 'SELECT * FROM disciplinas';
        $arr = [];
        if ($req = mysqli_query($this->conn, $query)) {
            while ($row = mysqli_fetch_array($req)) {
                $id = $row['id'];
                $nome = $row['nome'];
                $arr[$id][] = $nome;
            }
            if ($echo == true) {
                echo json_encode($arr);
            } else {
                return json_encode($arr);
            }
        }
    }

    public function getSalas($campus, $echo = true) {
        $query = "SELECT * FROM salas where campus = '$campus'";
        $arr = [];
        if ($req = mysqli_query($this->conn, $query)) {
            while ($row = mysqli_fetch_array($req)) {
                $sala = $row['sala'];
                array_push($arr, $sala);
            }
            if ($echo == true) {
                echo json_encode($arr);
            } else {
                return json_encode($arr);
            }
        }
    }

    public function getTodosDocentes($pagina, $campus, $value = null) {
        sleep(1);
        $pagina--;
        $minimo = $pagina * 10;
        if ($value != null) {
            $result = mysqli_query($this->conn, "SELECT id,nome,disciplinas,tipo from docentes where estado='ATV' and campus='$campus' and nome like '$value%' limit $minimo, 10");
        } else {
            $result = mysqli_query($this->conn, "SELECT id,nome,disciplinas,tipo from docentes where estado='ATV' and campus='$campus' limit $minimo, 10");
        }
        $array = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $nome = $row['nome'];
            $disci = $row['disciplinas'];
            $tipo = $row['tipo'];
            $array[$nome]['id'] = $id;
            $array[$nome]['disci'] = $disci;
            $array[$nome]['tipo'] = $tipo;
        }
        if (count($array) == 0) {
            return '{}';
        }

        return json_encode($array);
    }

    public function getTodosDiscentes($pagina, $campus, $value = null) {
        sleep(1);
        $pagina--;
        $minimo = $pagina * 10;
        if ($value != null) {
            $result = mysqli_query($this->conn, "SELECT id,nome,turma,tipo from alunos where estado='ATV' and campus='$campus' and nome like '$value%' limit $minimo, 10");
        } else {
            $result = mysqli_query($this->conn, "SELECT id,nome,turma,tipo from alunos where estado='ATV' and campus='$campus' limit $minimo, 10");
        }
        $array = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $nome = $row['nome'];
            $turma = $row['turma'];
            $tipo = $row['tipo'];
            $array[$nome]['id'] = $id;
            $array[$nome]['turma'] = $turma;
            $array[$nome]['tipo'] = $tipo;
        }
        if (count($array) == 0) {
            return '{}';
        }

        return json_encode($array);
    }

    public function getTodosDiscentesTurma($pagina, $turma, $campus, $value = null) {
        sleep(1);
        $pagina--;
        $minimo = $pagina * 10;
        if ($value != null) {
            $result = mysqli_query($this->conn, "SELECT id,nome,turma,tipo from alunos where estado='ATV' and turma='$turma' and campus='$campus' and nome like '$value%' limit $minimo, 10");
        } else {
            $result = mysqli_query($this->conn, "SELECT id,nome,turma,tipo from alunos where estado='ATV' and turma='$turma' and campus='$campus' limit $minimo, 10");
        }
        $array = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $nome = $row['nome'];
            $turma = $row['turma'];
            $tipo = $row['tipo'];
            $array[$nome]['id'] = $id;
            $array[$nome]['turma'] = $turma;
            $array[$nome]['tipo'] = $tipo;
        }
        if (count($array) == 0) {
            return '{}';
        }

        return json_encode($array);
    }

    public function quantidadeDeRegistrosDiscentes($campus, $busca = '') {
        if ($busca == '') {
            $query = mysqli_query($this->conn, "SELECT id FROM alunos where estado='ATV' and campus='$campus'");
        } else {
            $query = mysqli_query($this->conn, "SELECT id FROM alunos where estado='ATV' and campus='$campus' and nome like '$busca%'");
        }
        if ($this->mysqli_exist($query)) {
            return mysqli_num_rows($query);
        }

        return '0';
    }

    public function quantidadeDeRegistrosDocentes($campus, $busca = '') {
        if ($busca == '') {
            $query = mysqli_query($this->conn, "SELECT id FROM docentes where estado='ATV' and campus='$campus'");
        } else {
            $query = mysqli_query($this->conn, "SELECT id FROM docentes where estado='ATV' and campus='$campus' and nome like '$busca%'");
        }
        if ($this->mysqli_exist($query)) {
            return mysqli_num_rows($query);
        }

        return '0';
    }

    public function quantidadeDeRegistrosTurma($campus, $turma, $busca = '') {
        if ($busca == '') {
            $query = mysqli_query($this->conn, "SELECT id FROM alunos where estado='ATV' and turma='$turma' and campus='$campus'");
        } else {
            $query = mysqli_query($this->conn, "SELECT id FROM alunos where estado='ATV' and turma='$turma' and campus='$campus' and nome like '$busca%'");
        }
        if ($this->mysqli_exist($query)) {
            return mysqli_num_rows($query);
        }

        return '0';
    }

    ##################################################
    # Pegar nomes pelos id's
    ##################################################

    public function getUsuarioById($id) {
        $query = mysqli_query($this->conn, "
        select nome, `nome.preferencia` from alunos
		where id='$id' and estado='ATV'
		union
		select nome, `nome.preferencia` from docentes
		where id= '$id' and estado='ATV'
		union
		select nome, `nome.preferencia` from admins
		where id= '$id' and estado='ATV' limit 1");
        if ($this->mysqli_exist($query)) {
            $array = mysqli_fetch_assoc($query);
            $nomeCompleto = $array["nome"];
            $nomePreferencia = $array["nome.preferencia"];
            return [$nomeCompleto, $nomePreferencia];
        } else {
            return 'null';
        }
    }

    public function getDisciplinaById($id) {
        $query = mysqli_query($this->conn, "SELECT * FROM disciplinas where id='$id' limit 1");
        if ($this->mysqli_exist($query)) {
            return mysqli_fetch_assoc($query)['nome'];
        } else {
            return 'null'; 
        }
    }

    public function getCampusById($id) {
        $query = mysqli_query($this->conn, "SELECT * FROM campus where id='$id' limit 1");
        if ($this->mysqli_exist($query)) {
            return mysqli_fetch_assoc($query)['nome'];
        } else {
            return 'null'; 
        }
    }
}
