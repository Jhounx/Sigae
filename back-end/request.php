<?php
require_once("./security.php");
require_once("./main.php");
ob_start();

########## Permitir requisições sem identificador ##########
$array = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM global"));
if($array["debug"] == "SIM") {
    $GLOBALS["debug"] = true;
} else {
    $GLOBALS["debug"] = false;
}

##################################################
# TITULO I - Login e Registro
##################################################

/* verificar o login */
if (isset($_POST["login"]) and isset($_POST["senha"])) {
    require("./logar.php");
    $login = proteger($_POST["login"]);
    $senha = proteger($_POST["senha"]);
    $retorno = logar($login, $senha);
    if ($retorno == "") {
        return "Estado do usuário inválido";
    } else {
        echo $retorno;
    }
}

/* Validar key e retornar JSON dos dados */
if (isset($_GET["validarKey"]) && isset($_GET["codigo"])) {
    require("./registro.php");
    $codigo = proteger($_GET["codigo"]);
    echo (validarKey($codigo));
}

/* realizar o registro */
if (isset($_GET["registrarUsuario"]) && 
    isset($_GET["id"]) && 
    isset($_GET["nomePreferencial"]) && 
    isset($_GET["email"]) &&
    isset($_GET["senha"]) &&
    isset($_GET["turma"]) &&
    isset($_GET["disciplinas"])) {
        verificarSessao(["permissaoRegistro"]);
        require("./registro.php");
        $id = proteger($_GET["id"]);
        $nomePreferencial = proteger($_GET["nomePreferencial"]);
        $email = proteger($_GET["email"]);
        $senha = proteger($_GET["senha"]);
        $turma = proteger($_GET["turma"]);
        $disciplinas = proteger($_GET["disciplinas"]);
        echo(registrarAluno($id, $nomePreferencial, $email, $senha, $turma, $disciplinas));
}

/* Cancelar registro */
if (isset($_GET["cancelarInscricao"]) && isset($_GET["id"])) {
    verificarSessao(["permissaoRegistro"]);
    require("./registro.php");
    $id = proteger($_GET["id"]);
    echo (cancelarInscricao($id));
}

/* Verificar se o registro acabou */

if (isset($_GET["registroAcabou"]) && isset($_GET["id"])) {
    require("./registro.php");
    verificarSessao(["permissaoRegistro"]);
    $id = proteger($_GET["id"]);
    echo (registroAcabou($id));
}

##################################################
# TITULO II - JSON dos dados de infraestrutura
##################################################

/* Pegar todas as turmas */ 
if (isset($_GET["getTurmas"])) {
    verificarSessao(["permissaoSistema", "permissaoRegistro"]);
    $query = "SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
    FROM turmas as tabelaTurma
    LEFT JOIN cursos as tabelaCurso
    on tabelaTurma.curso = tabelaCurso.id";

    $resultadoQuery= $conn->query($query);
    $arr = array();
    while ($linha = mysqli_fetch_array($resultadoQuery)) {
        $turma = $linha["codigo"];
        $curso = $linha["nome"];
        $arr[$curso][] = $turma;
    }
    echo json_encode($arr);
}

/* Verificar se turma específica existe. SIM/NAO*/
if (isset($_GET["turmaExiste"]) && isset($_GET["turma"])) {
    include("./validacoes.php");
    verificarSessao(["permissaoSistema", "permissaoRegistro"]);
    $turma = proteger($_GET["turma"]);
    echo validarTurma($turma);
}

if (isset($_GET["getTurmasByCurso"]) && isset($_GET["curso"])) {
    verificarSessao(["permissaoSistema", "permissaoRegistro"]);
    $curso = proteger($_GET["curso"]);
    $query = "SELECT tabelaTurma.codigo, tabelaTurma.curso, tabelaCurso.nome
    FROM turmas as tabelaTurma
    LEFT JOIN cursos as tabelaCurso
    on tabelaTurma.curso = tabelaCurso.id where tabelaTurma.curso=\"$curso\"";

    $resultadoQuery= $conn->query($query);
    $arr = array();
    while ($linha = mysqli_fetch_array($resultadoQuery)) {
        $turma = $linha["codigo"];
        $curso = $linha["nome"];
        $arr[$curso][] = $turma;
    }
    echo json_encode($arr);
}

/* Pegar todas as disciplinas */ 
if (isset($_GET["getDisciplinas"])) {
    verificarSessao(["permissaoSistema", "permissaoRegistro"]);
    $query = "select * from disciplinas";
    $arr = array();
    if ($req = mysqli_query($conn, $query)) {
        while ($row = mysqli_fetch_array($req)) {
            $id = $row["id"];
            $nome = $row["nome"];
            $arr[$id][] = $nome;
        }
        echo json_encode($arr);
    }
}

/* Verificar se disciplinas específica existe. SIM/NAO*/
if (isset($_GET["disciplinasExiste"]) && isset($_GET["disciplina"])) {
    include("./validacoes.php");
    verificarSessao(["permissaoSistema", "permissaoRegistro"]);
    $disciplina = proteger($_GET["disciplina"]);
    echo validarDisciplina($disciplina);
}


$conteudo = ob_get_contents();
if(empty($conteudo)) {
    echo "
    <h1>SiGAÊ - Página de requisições</h1>
    Nenhum dado foi requisitado!
    ";
}