<?php
require $_SERVER['DOCUMENT_ROOT'] . '/back-end/autoloader.php';
$user = new Admin();
$em = new Email();
$foto = new Foto();
ob_start();
sleep(1); //Delay proposital, para se evitar ataques de força bruta

########## Permitir requisições sem identificador ##########
$array = mysqli_fetch_assoc(mysqli_query($user->conn, 'SELECT * FROM global'));
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
    $login = $user->proteger($_POST['login']);
    $senha = $user->proteger($_POST['senha']);
    $retorno = $user->logar($login, $senha);
    if ($retorno == '') {
        return 'Estado do usuário inválido';
    }
    echo $retorno;
}

/* Validar key e retornar JSON dos dados */
if (isset($_GET['validarKey']) && isset($_GET['codigo'])) {
    $codigo = $user->proteger($_GET['codigo']);
    echo($user->validarKey($codigo));
}

/* Realizar o registro */
if (isset($_GET['registrarUsuario']) &&
    isset($_GET['id']) &&
    isset($_GET['nomePreferencial']) &&
    isset($_GET['email']) &&
    isset($_GET['senha']) &&
    isset($_GET['turma']) &&
    isset($_GET['disciplinas'])) {
    $user->verificarPermissao(['permissaoRegistro']);
    $id = $user->proteger($_GET['id']);
    $nomePreferencial = $user->proteger($_GET['nomePreferencial']);
    $email = $user->proteger($_GET['email']);
    $senha = $user->proteger($_GET['senha']);
    $turma = $user->proteger($_GET['turma']);
    $disciplinas = $user->proteger($_GET['disciplinas']);
    echo($user->registrar($id, $nomePreferencial, $email, $senha, $turma, $disciplinas));
}

/* Cancelar registro */
if (isset($_GET['cancelarInscricao']) && isset($_GET['id'])) {
    $user->verificarPermissao(['permissaoRegistro']);
    $id = $user->proteger($_GET['id']);
    echo($user->cancelarInscricao($id));
}

/* Trocar dados */
if (isset($_POST['alterarDados'])) {
    $user->verificarPermissao(['permissaoSistema']);
    $id = $user->proteger($_POST['id']);
    $nomePreferencia = null;
    $turma = null;
    $disci = null;
    $email = null;
    $img = null;
    if (isset($_POST['nomePreferencia'])) {
        $nomePreferencia = $user->proteger($_POST['nomePreferencia']);
    }
    if (isset($_POST['turma'])) {
        $turma = $user->proteger($_POST['turma']);
    }
    if (isset($_POST['disci'])) {
        $disci = $user->proteger($_POST['disci']);
    }
    if (isset($_POST['email'])) {
        $email = $user->proteger($_POST['email']);
    }
    if (isset($_POST['img'])) {
        $img = $user->proteger($_POST['img']);
    }
    echo($user->mudarDados($id, $nomePreferencia, $turma, $disci, $email, $img));
}

/* Trocar senha */
if (isset($_GET['trocarSenha']) && isset($_GET['codigo']) && isset($_GET['senha'])) {
    $codigo = $user->proteger($_GET['codigo']);
    $senha = $user->proteger($_GET['senha']);
    $id = $user->pegarIDporCodigoEmails($codigo);
    $user->verificarPermissao(['trocarSenha'], $id);
    echo($user->trocarSenha($id, $senha));
}

/* Verificar se o registro acabou */
if (isset($_GET['registroAcabou'])) {
    $user->verificarPermissao(['permissaoRegistro']);
    $id = $user->getIDnoCookie(["permissaoRegistro"]);
    echo($user->verificarSeJaValidou($id));
}

##################################################
# Parte II - JSON dos dados de infraestrutura
##################################################

/* Pegar todas as turmas */
if (isset($_GET['getTurmas']) && isset($_GET['campus'])) {
    $user->verificarPermissao(['permissaoSistema']);
    echo($user->getTurmas());
}

/* Verificar se turma específica existe. SIM/NAO*/
if (isset($_GET['turmaExiste']) && isset($_GET['turma'])) {
    $user->verificarPermissao(['permissaoSistema', 'permissaoRegistro']);
    $turma = $user->proteger($_GET['turma']);
    echo $user->validarTurma($turma);
}

/* Pegar turmas pelo curso */
if (isset($_GET['getTurmasByCurso']) && isset($_GET['curso']) && isset($_GET['campus'])) {
    $user->verificarPermissao(['permissaoSistema', 'permissaoRegistro']);
    $curso = $user->proteger($_GET['curso']);
    $campus = $user->proteger($_GET['campus']);
    echo $user->getTurmasByCurso($curso, $campus);
}

/* Pegar todas as disciplinas */
if (isset($_GET['getDisciplinas'])) {
    $user->verificarPermissao(['permissaoSistema', 'permissaoRegistro']);
    echo $user->getDisciplinas();
}

/* Verificar se disciplinas específica existe. SIM/NAO*/
if (isset($_GET['disciplinasExiste']) && isset($_GET['disciplina'])) {
    $user->verificarPermissao(['permissaoSistema', 'permissaoRegistro']);
    $disciplina = $user->proteger($_GET['disciplina']);
    echo $user->validarDisciplina($disciplina);
}

/* Pegar todas as salas */
if (isset($_GET['getSalas']) && isset($_GET['campus'])) {
    //$user->verificarPermissao(['permissaoSistema']);
    $campus = $user->proteger($_GET['campus']);
    echo $user->getSalas($campus);
}

##################################################
# Parte III - JSON dos dados de pessoas
##################################################

/* Pegar dados de um usuário qualquer */
if (isset($_GET['pegarDadoUsuario'])) {
    $id = $user->proteger($_GET['id']);
    $pedido = $user->proteger($_GET['pedido']);
    $user->verificarPermissao(['permissaoSistema'], $id);
    if (isset($_GET['turma'])) {
        $turma = $user->proteger($_GET['turma']);
        echo($user->pegarDadosUsuario($pedido, $id, false, $turma));
    } else {
        echo($user->pegarDadosUsuario($pedido, $id, false));
    }
}

/* Pegar a foto de um usuário */
if (isset($_GET['pegarFoto'])) {
    if (isset($_GET['id'])) {
        $id = $user->proteger($_GET['id']);
        $foto->show($id);
    } else {
        $foto->show();
    }
}

/* Pegar todos os docentes */
if (isset($_GET['pegarTodosDocentes']) && isset($_GET['pagina']) && isset($_GET['campus'])) {
    $pagina = $user->proteger($_GET['pagina']);
    $campus = $user->proteger($_GET['campus']);
    $user->verificarPermissao(['permissaoSistema']);
    if (isset($_GET['busca'])) {
        $busca = $user->proteger($_GET['busca']);
        echo($user->getTodosDocentes($pagina, $campus, $busca));
    } else {
        echo($user->getTodosDocentes($pagina, $campus));
    }
}

/* Pegar todos os discentes */
if (isset($_GET['pegarTodosDiscentes']) && isset($_GET['pagina']) && isset($_GET['campus'])) {
    $pagina = $user->proteger($_GET['pagina']);
    $campus = $user->proteger($_GET['campus']);
    $user->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    if (isset($_GET['value'])) {
        $busca = $user->proteger($_GET['busca']);
        echo($user->getTodosDiscentes($pagina, $campus, $busca));
    } else {
        echo($user->getTodosDiscentes($pagina, $campus));
    }
}

/* Pegar todos os discentes de uma turma */
if (isset($_GET['pegarTodaTurma']) && isset($_GET['pagina']) && isset($_GET['turma']) && isset($_GET['campus'])) {
    $pagina = $user->proteger($_GET['pagina']);
    $turma = $user->proteger($_GET['turma']);
    $campus = $user->proteger($_GET['campus']);
    $user->verificarPermissao(['permissaoSistema']);
    if (isset($_GET['busca'])) {
        $value = $user->proteger($_GET['busca']);
        echo($user->getTodosDiscentesTurma($pagina, $turma, $campus, $value));
    } else {
        echo($user->getTodosDiscentesTurma($pagina, $turma, $campus));
    }
}

/* Quantidade de registros de docentes */
if (isset($_GET['quantidadeDeRegistrosDocentes']) && isset($_GET['campus'])) {
    $campus = $user->proteger($_GET['campus']);
    $user->verificarPermissao(['permissaoSistema']);
    if (isset($_GET['busca'])) {
        $busca = $user->proteger($_GET['busca']);
        echo($user->quantidadeDeRegistrosDocentes($campus, $busca));
    } else {
        echo($user->quantidadeDeRegistrosDocentes($campus));
    }
}

/* Quantidade de registros de discentes */
if (isset($_GET['quantidadeDeRegistrosDiscentes']) && isset($_GET['campus'])) {
    $campus = $user->proteger($_GET['campus']);
    $user->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    if (isset($_GET['busca'])) {
        $busca = $user->proteger($_GET['busca']);
        echo($user->quantidadeDeRegistrosDiscentes($campus, $busca));
    } else {
        echo($user->quantidadeDeRegistrosDiscentes($campus));
    }
}

/* Quantidade de registros de discentes em uma turma */
if (isset($_GET['quantidadeDeRegistrosTurma']) && isset($_GET['campus']) && isset($_GET['turma'])) {
    $campus = $user->proteger($_GET['campus']);
    $turma = $user->proteger($_GET['turma']);
    $user->verificarPermissao(['permissaoSistema']);
    if (isset($_GET['busca'])) {
        $busca = $user->proteger($_GET['busca']);
        echo($user->quantidadeDeRegistrosTurma($campus, $turma, $busca));
    } else {
        echo($user->quantidadeDeRegistrosTurma($campus, $turma));
    }
}

##################################################
# Parte V - Atendimentos
##################################################

/* Pegar todos os atendimentos agendados (só para docentes) */
if (isset($_GET['pegarTodosAtendimentosDocente'])) {
    $user->verificarPermissao(['permissaoSistema'], '', ['DOC', 'MON']);
    $id = $user->getIDnoCookie(["permissaoSistema"]);
    echo $user->pegarTodosAtendimentosDocente($id);
}

/* Pegar atendimento específico pelo seu ID */
if (isset($_GET['pegarAtendimentoByID']) && isset($_GET['id'])) {
    $user->verificarPermissao(['permissaoSistema']);
    $id = $user->proteger($_GET['id']);
    echo $user->pegarAtendimentoByID($id);
}


##################################################
# Parte VI - Emails
##################################################

/* Email de validação de registro */
if (isset($_GET['enviarEmailValidacao']) && isset($_GET['id'])) {
    $id = $user->proteger($_GET['id']);
    $queryPessoaTexto = "
    select id, `nome.preferencia`, email, estado from alunos where id= '$id' and estado='REG'
    union
    select id, `nome.preferencia`, email, estado from docentes where id= '$id' and estado='REG'
    union
    select id, `nome.preferencia`, email, estado from admins where id= '$id' and estado='REG'
    limit 1";
    $queryPessoa = mysqli_query($user->conn, $queryPessoaTexto);
    if ($user->mysqli_exist($queryPessoa)) {
        $arrayPessoa = mysqli_fetch_assoc($queryPessoa);
        $email = $arrayPessoa['email'];
        $nome = $arrayPessoa['nome.preferencia'];

        $queryCodigosTexto = "SELECT * FROM codigos_email where id='$id' and tipo = 'VAL' limit 1";
        $queryCodigo = mysqli_query($user->conn, $queryCodigosTexto);
        $array = mysqli_fetch_assoc($queryCodigo);
        if ($user->mysqli_exist($queryCodigo)) {
            $link = $user->host . '/back-end/requestEmail.php?codigo=' . $array['valor'];
            if ($em->enviarEmail('SiGAÊ - Validação de email', 'validarEmail.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        } else {
            $codigo = $user->randomString('1234567890', 50);
            $link = $user->host . '/back-end/requestEmail.php?codigo=' . $codigo;
            $queryInsert = "INSERT INTO codigos_email (id, valor, tipo) VALUES ('$id', '$codigo', 'VAL');";
            if (!mysqli_query($user->conn, $queryInsert)) {
                echo('Error grave: ' . $user->conn -> error);
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
    $email = $user->proteger($_GET['email']);
    $queryPessoaTexto = "
    select id, `nome.preferencia`, email, estado from alunos where email='$email' and estado='ATV' 
    union
    select id, `nome.preferencia`, email, estado from docentes where email='$email' and estado='ATV'
    union
    select id, `nome.preferencia`, email, estado from admins where email='$email' and estado='ATV'
    limit 1";
    $queryPessoa = mysqli_query($user->conn, $queryPessoaTexto);
    if ($user->mysqli_exist($queryPessoa)) {
        $arrayPessoa = mysqli_fetch_assoc($queryPessoa);
        $id = $arrayPessoa['id'];
        $nome = $arrayPessoa['nome.preferencia'];
        $queryCodigosTexto = "SELECT * FROM codigos_email where id='$id' and tipo = 'REC' limit 1";

        $queryCodigo = mysqli_query($user->conn, $queryCodigosTexto);
        $array = mysqli_fetch_assoc($queryCodigo);
        if ($user->mysqli_exist($queryCodigo)) {
            $link = $user->host . '/back-end/requestEmail.php?codigo=' . $array['valor'];
            if ($em->enviarEmail('SiGAÊ - Recuperação de senha', 'recuperacaoSenha.html', ['{nome}', '{codigo}'], [$nome, $link], [$email]) == 'SIM') {
                echo 'OK';
            } else {
                echo 'EML';
            }
        } else {
            $codigo = $user->randomString('1234567890', 50);
            $link = $user->host . '/back-end/requestEmail.php?codigo=' . $codigo;
            $queryInsert = "INSERT INTO codigos_email (id, valor, tipo) VALUES ('$id', '$codigo', 'REC');";
            if (!mysqli_query($user->conn, $queryInsert)) {
                echo('Error grave: ' . $user->conn -> error);
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
    $value = $user->proteger($_GET['codigo']);

    $queryCodigosTexto = "SELECT * FROM codigos_email where valor='$value' and tipo = 'VAL' limit 1";
    $queryCodigo = mysqli_query($user->conn, $queryCodigosTexto);
    $array = mysqli_fetch_assoc($queryCodigo);
    if ($user->mysqli_exist($queryCodigo)) {
        $id = $array['id'];
        $queryPessoaTexto = "
        UPDATE alunos SET estado = 'ATV' WHERE id='$id';
        UPDATE docentes SET estado = 'ATV' WHERE id='$id';
        UPDATE admins SET estado = 'ATV' WHERE id='$id';
        DELETE FROM codigos_email WHERE id='$id'";
        if (mysqli_multi_query($user->conn, $queryPessoaTexto)) {
            header('Location: ../../?reg=true');
        } else {
            echo('Error grave: ' . $user->conn -> error);
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
