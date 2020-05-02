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
if ($array['debug'] == 'SIM') {
    $GLOBALS['debug'] = true;
} else {
    $GLOBALS['debug'] = false;
}

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

/* Realizar o registro */
if (isset($_GET['registrarUsuario']) &&
    isset($_GET['id']) &&
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
if (isset($_GET['cancelarInscricao']) && isset($_GET['id'])) {
    $sys->verificarPermissao(['permissaoRegistro']);
    $id = $sys->getIDnoCookie(['permissaoRegistro']);
    echo($sys->cancelarInscricao($id));
}

/* Trocar dados */
if (isset($_POST['alterarDados'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $id = $sys->getIDnoCookie(['permissaoRegistro']);
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

/* Verificar se o registro acabou */
if (isset($_GET['registroAcabou'])) {
    $sys->verificarPermissao(['permissaoRegistro']);
    $id = $sys->getIDnoCookie(['permissaoRegistro']);
    echo($sys->verificarSeJaValidou($id));
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
    $id = $sys->getIDnoCookie(['permissaoSistema']);
    $pedido = $sys->proteger($_GET['pedido']);
    $sys->verificarPermissao(['permissaoSistema'], $id);
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
    $pagina = $sys->proteger($_GET['pagina']);
    $campus = $sys->proteger($_GET['campus']);
    $sys->verificarPermissao(['permissaoSistema']);
    if (isset($_GET['busca'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->getTodosDocentes($pagina, $campus, $busca));
    } else {
        echo($sys->getTodosDocentes($pagina, $campus));
    }
}

/* Pegar todos os discentes */
if (isset($_GET['pegarTodosDiscentes']) && isset($_GET['pagina']) && isset($_GET['campus'])) {
    $pagina = $sys->proteger($_GET['pagina']);
    $campus = $sys->proteger($_GET['campus']);
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    if (isset($_GET['value'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->getTodosDiscentes($pagina, $campus, $busca));
    } else {
        echo($sys->getTodosDiscentes($pagina, $campus));
    }
}

/* Pegar todos os discentes de uma turma */
if (isset($_GET['pegarTodaTurma']) && isset($_GET['pagina']) && isset($_GET['turma']) && isset($_GET['campus'])) {
    $pagina = $sys->proteger($_GET['pagina']);
    $turma = $sys->proteger($_GET['turma']);
    $campus = $sys->proteger($_GET['campus']);
    $sys->verificarPermissao(['permissaoSistema']);
    if (isset($_GET['busca'])) {
        $value = $sys->proteger($_GET['busca']);
        echo($sys->getTodosDiscentesTurma($pagina, $turma, $campus, $value));
    } else {
        echo($sys->getTodosDiscentesTurma($pagina, $turma, $campus));
    }
}

/* Quantidade de registros de docentes */
if (isset($_GET['quantidadeDeRegistrosDocentes']) && isset($_GET['campus'])) {
    $campus = $sys->proteger($_GET['campus']);
    $sys->verificarPermissao(['permissaoSistema']);
    if (isset($_GET['busca'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->quantidadeDeRegistrosDocentes($campus, $busca));
    } else {
        echo($sys->quantidadeDeRegistrosDocentes($campus));
    }
}

/* Quantidade de registros de discentes */
if (isset($_GET['quantidadeDeRegistrosDiscentes']) && isset($_GET['campus'])) {
    $campus = $sys->proteger($_GET['campus']);
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    if (isset($_GET['busca'])) {
        $busca = $sys->proteger($_GET['busca']);
        echo($sys->quantidadeDeRegistrosDiscentes($campus, $busca));
    } else {
        echo($sys->quantidadeDeRegistrosDiscentes($campus));
    }
}

/* Quantidade de registros de discentes em uma turma */
if (isset($_GET['quantidadeDeRegistrosTurma']) && isset($_GET['campus']) && isset($_GET['turma'])) {
    $campus = $sys->proteger($_GET['campus']);
    $turma = $sys->proteger($_GET['turma']);
    $sys->verificarPermissao(['permissaoSistema']);
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

/* Pegar todos os atendimentos agendados (só para docentes) */
if (isset($_GET['pegarTodosAtendimentosDocente'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    $id = $sys->getIDnoCookie(['permissaoSistema']);
    echo $sys->pegarTodosAtendimentosDocente($id);
}

if (isset($_GET['pegarTodosAtendimentosDiscente'])) {
    $sys->verificarPermissao(['permissaoSistema'], '', ['ALU']);
    $id = $sys->getIDnoCookie(['permissaoSistema']);
    echo $sys->pegarTodosAtendimentosDiscente($id);
}

/* Pegar atendimento específico pelo seu ID */
if (isset($_GET['pegarAtendimentoByID']) && isset($_GET['id'])) {
    $sys->verificarPermissao(['permissaoSistema']);
    $id = $sys->proteger($_GET['id']);
    echo $sys->pegarAtendimentoByID($id);
}

##################################################
# Parte VI - Emails
##################################################

/* Email de validação de registro */
if (isset($_GET['enviarEmailValidacao']) && isset($_GET['id'])) {
    $id = $sys->proteger($_GET['id']);
    $queryPessoaTexto = "
    select id, `nome.preferencia`, email, estado from alunos where id= '$id' and estado='REG'
    union
    select id, `nome.preferencia`, email, estado from docentes where id= '$id' and estado='REG'
    union
    select id, `nome.preferencia`, email, estado from admins where id= '$id' and estado='REG'
    limit 1";
    $queryPessoa = mysqli_query($sys->conn, $queryPessoaTexto);
    if ($sys->mysqli_exist($queryPessoa)) {
        $arrayPessoa = mysqli_fetch_assoc($queryPessoa);
        $email = $arrayPessoa['email'];
        $nome = $arrayPessoa['nome.preferencia'];

        $queryCodigosTexto = "SELECT * FROM codigos_email where id='$id' and tipo = 'VAL' limit 1";
        $queryCodigo = mysqli_query($sys->conn, $queryCodigosTexto);
        $array = mysqli_fetch_assoc($queryCodigo);
        if ($sys->mysqli_exist($queryCodigo)) {
            $link = $sys->host . '/back-end/requestEmail.php?codigo=' . $array['valor'];
            if ($em->enviarEmail('SiGAÊ - Validação de email', 'validarEmail.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        } else {
            $codigo = $sys->randomString('1234567890', 50);
            $link = $sys->host . '/back-end/requestEmail.php?codigo=' . $codigo;
            $queryInsert = "INSERT INTO codigos_email (id, valor, tipo) VALUES ('$id', '$codigo', 'VAL');";
            if (!mysqli_query($sys->conn, $queryInsert)) {
                echo('Error grave: ' . $sys->conn -> error);
                die();
            }
            if ($em->enviarEmail('SiGAÊ - Validação de email', 'validarEmail.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        }
    } else {
        echo 'INV';
    }
}

/* Email de validação de trocar senha */
if (isset($_GET['enviarEmailTrocarSenha']) && isset($_GET['email'])) {
    $email = $sys->proteger($_GET['email']);
    $queryPessoaTexto = "
    select id, `nome.preferencia`, email, estado from alunos where email='$email' and estado='ATV' 
    union
    select id, `nome.preferencia`, email, estado from docentes where email='$email' and estado='ATV'
    union
    select id, `nome.preferencia`, email, estado from admins where email='$email' and estado='ATV'
    limit 1";
    $queryPessoa = mysqli_query($sys->conn, $queryPessoaTexto);
    if ($sys->mysqli_exist($queryPessoa)) {
        $arrayPessoa = mysqli_fetch_assoc($queryPessoa);
        $id = $arrayPessoa['id'];
        $nome = $arrayPessoa['nome.preferencia'];
        $queryCodigosTexto = "SELECT * FROM codigos_email where id='$id' and tipo = 'REC' limit 1";

        $queryCodigo = mysqli_query($sys->conn, $queryCodigosTexto);
        $array = mysqli_fetch_assoc($queryCodigo);
        if ($sys->mysqli_exist($queryCodigo)) {
            $link = $sys->host . '/back-end/requestEmail.php?codigo=' . $array['valor'];
            if ($em->enviarEmail('SiGAÊ - Recuperação de senha', 'recuperacaoSenha.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        } else {
            $codigo = $sys->randomString('1234567890', 50);
            $link = $sys->host . '/back-end/requestEmail.php?codigo=' . $codigo;
            $queryInsert = "INSERT INTO codigos_email (id, valor, tipo) VALUES ('$id', '$codigo', 'REC');";
            if (!mysqli_query($sys->conn, $queryInsert)) {
                echo('Error grave: ' . $sys->conn -> error);
                die();
            }
            if ($em->enviarEmail('SiGAÊ - Recuperação de senha', 'recuperacaoSenha.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        }
    } else {
        echo 'INV';
    }
}

/* Validar código email registro*/
if (isset($_GET['validacaoRegistro']) && isset($_GET['codigo'])) {
    $value = $sys->proteger($_GET['codigo']);

    $queryCodigosTexto = "SELECT * FROM codigos_email where valor='$value' and tipo = 'VAL' limit 1";
    $queryCodigo = mysqli_query($sys->conn, $queryCodigosTexto);
    $array = mysqli_fetch_assoc($queryCodigo);
    if ($sys->mysqli_exist($queryCodigo)) {
        $id = $array['id'];
        $queryPessoaTexto = "
        UPDATE alunos SET estado = 'ATV' WHERE id='$id';
        UPDATE docentes SET estado = 'ATV' WHERE id='$id';
        UPDATE admins SET estado = 'ATV' WHERE id='$id';
        DELETE FROM codigos_email WHERE id='$id'";
        if (mysqli_multi_query($sys->conn, $queryPessoaTexto)) {
            header('Location: ../../?reg=true');
        } else {
            echo('Error grave: ' . $sys->conn -> error);
        }
    } else {
        echo 'Esse código não é válido. Tente novamente';
    }
}

##################################################
# Footer
##################################################

$conteudo = ob_get_contents();
if (empty($conteudo) && $conteudo != '0') {
    echo '
<h1>SiGAÊ - Página de requisições</h1>
Nenhum dado foi requisitado!
';
}
