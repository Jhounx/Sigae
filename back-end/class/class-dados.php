<?php

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

    public function getTodosDocentes($pagina, $campus, $value = null) {
        sleep(1);
        $pagina--;
        $minimo = $pagina * 10;
        if($value != null) {
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
        if(count($array) == 0) {
            return "{}";
        }
        return json_encode($array);
    }

    public function getTodosDiscentes($pagina, $campus, $value = null) {
        sleep(1);
        $pagina--;
        $minimo = $pagina * 10;
        if($value != null) {
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
        if(count($array) == 0) {
            return "{}";
        }
        return json_encode($array);
    }

    public function getTodosDiscentesTurma($pagina, $turma, $campus, $value = null) {
        sleep(1);
        $pagina--;
        $minimo = $pagina * 10;
        if($value != null) {
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
        if(count($array) == 0) {
            return "{}";
        }
        return json_encode($array);
    }

    function quantidadeDeRegistrosDiscentes($campus, $busca = '') {
        if($busca == '') {
            $query = mysqli_query($this->conn, "SELECT id FROM alunos where estado='ATV' and campus='$campus'");
        } else {
            $query = mysqli_query($this->conn, "SELECT id FROM alunos where estado='ATV' and campus='$campus' and nome like '$busca%'");
        }
        if($this->mysqli_exist($query)) {
            return mysqli_num_rows($query);
        } else {
            return "0";
        }
    }

    function quantidadeDeRegistrosDocentes($campus, $busca = '') {
        if($busca == '') {
            $query = mysqli_query($this->conn, "SELECT id FROM docentes where estado='ATV' and campus='$campus'");
        } else {
            $query = mysqli_query($this->conn, "SELECT id FROM docentes where estado='ATV' and campus='$campus' and nome like '$busca%'");
        }
        if($this->mysqli_exist($query)) {
            return mysqli_num_rows($query);
        } else {
            return "0";
        }
    }

    function quantidadeDeRegistrosTurma($campus, $turma, $busca = '') {
        if($busca == '') {
            $query = mysqli_query($this->conn, "SELECT id FROM alunos where estado='ATV' and turma='$turma' and campus='$campus'");
        } else {
            $query = mysqli_query($this->conn, "SELECT id FROM alunos where estado='ATV' and turma='$turma' and campus='$campus' and nome like '$busca%'");
        }
        if($this->mysqli_exist($query)) {
            return mysqli_num_rows($query);
        } else {
            return "0";
        }
    }
}
