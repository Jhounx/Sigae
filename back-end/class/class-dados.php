<?php

class Dados extends Registro {

    public function getTurmas() {
        $query = "
        SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
        FROM turmas as tabelaTurma
        LEFT JOIN cursos as tabelaCurso
        on tabelaTurma.curso = tabelaCurso.id";
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
}
