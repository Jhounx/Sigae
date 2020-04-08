<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/back-end/main.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/back-end/dados.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/back-end/security.php");
ob_start();

########## Permitir requisições sem identificador ##########
$array = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT * FROM global'));
if ($array['debug'] == 'SIM') {
    $GLOBALS['debug'] = true;
} else {
    $GLOBALS['debug'] = false;
}

##################################################
# TITULO I - Login e Registro
##################################################

/* Verificar o login */
if (isset($_POST['login']) and isset($_POST['senha'])) {
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/logar.php");
    $login = proteger($_POST['login']);
    $senha = proteger($_POST['senha']);
    $retorno = logar($login, $senha);
    if ($retorno == '') {
        return 'Estado do usuário inválido';
    }
    echo $retorno;
}

/* Validar key e retornar JSON dos dados */
if (isset($_GET['validarKey']) && isset($_GET['codigo'])) {
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/registro.php");
    $codigo = proteger($_GET['codigo']);
    echo(validarKey($codigo));
}

/* Realizar o registro */
if (isset($_GET['registrarUsuario']) &&
    isset($_GET['id']) &&
    isset($_GET['nomePreferencial']) &&
    isset($_GET['email']) &&
    isset($_GET['senha']) &&
    isset($_GET['turma']) &&
    isset($_GET['disciplinas'])) {
    verificarSessao(['permissaoRegistro']);
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/registro.php");
    $id = proteger($_GET['id']);
    $nomePreferencial = proteger($_GET['nomePreferencial']);
    $email = proteger($_GET['email']);
    $senha = proteger($_GET['senha']);
    $turma = proteger($_GET['turma']);
    $disciplinas = proteger($_GET['disciplinas']);
    echo(registrarAluno($id, $nomePreferencial, $email, $senha, $turma, $disciplinas));
}

/* Cancelar registro */
if (isset($_GET['cancelarInscricao']) && isset($_GET['id'])) {
    verificarSessao(['permissaoRegistro']);
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/registro.php");
    $id = proteger($_GET['id']);
    echo(cancelarInscricao($id));
}

/* Trocar dados */

if (isset($_POST['alterarDados'])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/back-end/dados.php");
    verificarSessao(['permissaoSistema']);
    $id = proteger($_POST['id']);
    $nomePreferencia = null;
    $turma = null;
    $disci = null;
    $email = null;
    $img = null;
    if (isset($_POST['nomePreferencia'])) {
        $nomePreferencia = proteger($_POST['nomePreferencia']);
    }
    if (isset($_POST['turma'])) {
        $turma = proteger($_POST['turma']);
    }
    if (isset($_POST['disci'])) {
        $disci = proteger($_POST['disci']);
    }
    if (isset($_POST['email'])) {
        $email = proteger($_POST['email']);
    }
    if (isset($_POST['img'])) {
        $img = proteger($_POST['img']);
    }
    echo(mudarDados($id, $nomePreferencia, $turma, $disci, $email, $img));
}

/* Trocar senha */
if (isset($_GET['trocarSenha']) && isset($_GET['codigo']) && isset($_GET['senha'])) {
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/main.php");
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/registro.php");

    $codigo = proteger($_GET['codigo']);
    $senha = proteger($_GET['senha']);
    $id = getIDByCodigo($codigo);
    verificarSessao(['trocarSenha'], $id);
    echo(trocarSenha($id, $senha));
}

/* Verificar se o registro acabou */
if (isset($_GET['registroAcabou']) && isset($_GET['id'])) {
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/registro.php");
    verificarSessao(['permissaoRegistro']);
    $id = proteger($_GET['id']);
    echo(registroAcabou($id));
}

##################################################
# TITULO II - JSON dos dados de infraestrutura
##################################################

/* Pegar todas as turmas */
if (isset($_GET['getTurmas']) && isset($_GET['campus'])) {
    verificarSessao(['permissaoSistema']);
    echo(getTurmas());
}

/* Verificar se turma específica existe. SIM/NAO*/
if (isset($_GET['turmaExiste']) && isset($_GET['turma'])) {
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/registro.php");
    verificarSessao(['permissaoSistema', 'permissaoRegistro']);
    $turma = proteger($_GET['turma']);
    echo validarTurma($turma);
}

/* Pegar turmas pelo curso */
if (isset($_GET['getTurmasByCurso']) && isset($_GET['curso']) && isset($_GET['campus'])) {
    verificarSessao(['permissaoSistema', 'permissaoRegistro']);
    $curso = proteger($_GET['curso']);
    $campus = proteger($_GET['campus']);
    echo getTurmasByCurso($curso, $campus);
}

/* Pegar todas as disciplinas */
if (isset($_GET['getDisciplinas'])) {
    verificarSessao(['permissaoSistema', 'permissaoRegistro']);
    echo getDisciplinas();
}

/* Verificar se disciplinas específica existe. SIM/NAO*/
if (isset($_GET['disciplinasExiste']) && isset($_GET['disciplina'])) {
    require($_SERVER['DOCUMENT_ROOT'] . "/back-end/dados.php");
    verificarSessao(['permissaoSistema', 'permissaoRegistro']);
    $disciplina = proteger($_GET['disciplina']);
    echo validarDisciplina($disciplina);
}

$conteudo = ob_get_contents();
if (empty($conteudo)) {
    echo '
    <h1>SiGAÊ - Página de requisições</h1>
    Nenhum dado foi requisitado!
    ';
}
