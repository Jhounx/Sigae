<html>

<head>
    <title>SiGAÊ - IFBA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Pedro Cerqueira Mota, João Costa Neto, Rafael Requião" />
    <link rel="stylesheet" href="../componentes/APIs/bootstrap.min.css">
    <link rel="stylesheet" href="../componentes/APIs/materialize.min.css">
    <link rel="stylesheet" href="./css.css">
    <link rel="stylesheet" href="./responsive.css">
    <link rel="stylesheet" href="../componentes/loading.css">
    <link rel="icon" href="../icones/si.png">
    <link rel="stylesheet" href="../componentes/APIs/icon.css">
    <link rel="stylesheet" href="../componentes/APIs/bootstrap-select.min.css">
    <link rel="stylesheet" href="../componentes/select.css">
    <script src="../componentes/APIs/jquery.min.js"></script>
    <script src="../componentes/APIs/sweetalert2@8.js"></script>
    <script src="../componentes/APIs/popper.min.js"></script>
    <script src="../componentes/APIs/bootstrap.min.js"></script>
    <script src="../componentes/APIs/jquery.mask.min.js"></script>
    <script src="../componentes/APIs/param.js"></script>
    <script src="./javascript.js"></script>
    <script src="./validacao.js"></script>
    <script src="../componentes/request.js"></script>
    <script src="../componentes/Misc.js"></script>
    <script src="../componentes/APIs/materialize.min.js"></script>
    <script src="../componentes/APIs/bootstrap-select.min.js"></script>
</head>

<body style="overflow: hidden !important;">
    <div class="divCarregamento">
        <img class="imgCarregamento" src="../icones/loading.svg">
        <div class="noJS">Essa página precisa de Javascript. Ative-o!</div>
    </div>
    <noscript>
        <style type="text/css">
            .noJS {
                display: block;
            }
        </style>
    </noscript>
    <div class="sistema">
        <div class="tudo">
            <div class="topo">
                <div class="row justify-content-center">
                    <img src="../icones/sigae.svg">
                </div>
                <h1 class="titulo">Registrar usuário - <b id="partes">Parte 1</b></h1>
            </div>
            <!-- Parte 1 -->
            <div class="parte1 parte">
                <div class="row justify-content-center">
                    <div class="contentChave">
                        <div class="input-field inputChave">
                            <input id="campoChave" type="tel">
                            <label id="labelChave" for="campoChave">Digite sua chave de acesso</label>
                        </div>
                        <a class="waves-effect waves-light btn btnChave" onclick="verificarChave()">Próxima etapa</a>
                        <div class="waiting" id="waiting">
                            <div class="row justify-content-center">
                                <div class="spinner-border text-secondary" id="spinner-login" role="status"></div>
                            </div>
                        </div>
                        <div class="erro" id="erro">
                            <div class="row justify-content-center">
                                <div id="alert-login" class="alert alert-danger alert-dismissible fade show alert-login alerta-codigo" role="alert">
                                    <h8 id="erroTexto" style="text-align:center"></h8>
                                    <button type="button" class="close" onclick="removerErroCodigo()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Parte 2 -->
            <div class="parte2 parte">
                <form id="formValidate">
                    <div class="row justify-content-center linha">
                        <div>
                            <div class="row justify-content-center linha">
                                <div class="containerTipo">
                                    <i class="tipoParte2" style="margin-left:5px;font-style: normal;">Docente</i>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div>
                            <div class="row justify-content-center linha">
                                <div class="parte2Painel">
                                    <div class="parte2Esquerda">
                                        <h2 class="perguntaInfo" id="perg1"><i class="material-icons iconePerg">people</i>Nome:</h2>
                                        <h2 class="perguntaInfo" id="perg2"><i class="material-icons iconePerg">account_circle</i>Matrícula: </h2>
                                        <h2 class="perguntaInfo" id="perg3"><i class="material-icons iconePerg">school</i>Curso:</h2>
                                        <h2 class="perguntaInfo" id="perg4"><i class="material-icons iconePerg">account_balance</i>Campus:</h2>
                                    </div>
                                    <div class="parte2Direita">
                                        <h2 class="perguntaResp" id="resp1"></h2>
                                        <h2 class="perguntaResp" id="resp2"></h2>
                                        <h2 class="perguntaResp" id="resp3"></h2>
                                        <h2 class="perguntaResp" id="resp4"></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Abreviação do nome -->
                    <div class="row justify-content-center linha">
                        <div class="alert alert-primary">
                            <div style="display:inline-flex;margin-bottom:10px;">
                                <i class="material-icons">account_box</i>
                                <i style="margin-left:5px;font-style: normal;">Abreviação do nome</i>
                            </div>
                            <br>
                            Essa configuração define a abreviação do seu nome.
                            Seu nome aparecerá com essa abreviação na maioria das vezes.
                        </div>
                        <div class="input-field input-select inputSelectNome">
                            <select class="selectpicker selectNome">
                                <option disabled selected>Escolha uma opção</option>
                            </select>
                        </div>
                        <label class="msgInputError" id="erro1"></label>
                    </div>
                    <!-- Sua turma -->
                    <div class="row justify-content-center linha linhaTurma" style="display:none">
                        <div class="alert alert-primary">
                            <div style="display:inline-flex;margin-bottom:10px;">
                                <i class="material-icons">people</i>
                                <i style="margin-left:5px;font-style: normal;">Sua turma</i>
                            </div>
                            <br>
                            Escolha a opção correspondente a sua turma atual.
                            <br>
                            Essa opção pode ser alterada posteriormente
                        </div>
                        <div class="input-field input-select inputSelectTurma">
                            <select id="selectTurma" class="selectTurma" data-live-search="true">
                                <option disabled selected>Escolha uma opção</option>
                            </select>
                        </div>
                        <label class="msgInputError" id="erro2"></label>
                    </div>
                    <!-- Disciplinas ministradas -->
                    <div class="row justify-content-center linha linhaDisciplina" style="display:none">
                        <div class="alert alert-primary">
                            <div style="display:inline-flex;margin-bottom:10px;">
                                <i class="material-icons">school</i>
                                <i style="margin-left:5px;font-style: normal;">Suas disciplinass ministradas</i>
                            </div>
                            <br>
                            Selecione as disciplinas que você ministra.
                            <br>
                            Essa opção pode ser alterada posteriormente
                        </div>
                        <div class="input-disciplinas inputSelectDisciplinas">
                            <select id="selectDisciplinas" multiple data-live-search="true" class="selectDisciplinas">
                            </select>
                        </div>
                        <label class="msgInputError" id="erro3"></label>
                    </div>
                    <!-- E-mail -->
                    <div class="row justify-content-center linha">
                        <div class="alert alert-primary">
                            <div style="display:inline-flex;margin-bottom:10px;">
                                <i class="material-icons">email</i>
                                <i style="margin-left:5px;font-style: normal;">Seu E-mail</i>
                            </div>
                            <br>
                            O SiGAÊ enviará e-mails para a conta, informando a situação dos atendimentos, essa opção pode ser desativada posteriormente.
                            <br>
                            O e-mail também servirá para recuperar a conta caso haja perda de senha
                        </div>
                        <div class="input-field">
                            <input spellcheck="false" id="email" type="email" autocomplete="false" onfocusout="perdeuFocus('#email')" oninput="this.value=this.value.toLowerCase()">
                            <label for="email">E-mail</label>
                        </div>
                        <label class="msgInputError" id="erro4"></label>
                    </div>
                    <!-- Senha -->
                    <div class="row justify-content-center linha">
                        <div class="alert alert-primary">
                            <div style="display:inline-flex;margin-bottom:10px;">
                                <i class="material-icons">vpn_key</i>
                                <i style="margin-left:5px;font-style: normal;">Sua senha</i>
                            </div>
                            <br>
                            Digite uma senha que contenha entre 6 e 30 caracteres:
                        </div>
                        <div class="input-field">
                            <input spellcheck="false" id="senha1" type="password" autocomplete="false" onfocusout="perdeuFocus('#senha1')">
                            <label for="senha1">Senha</label>
                        </div>
                        <label class="msgInputError" id="erro5"></label>
                        <div class="input-field">
                            <input spellcheck="false" id="senha2" type="password" autocomplete="false" onfocusout="perdeuFocus('#senha2')">
                            <label for="senha2">Repetir senha</label>
                        </div>
                        <label class="msgInputError" id="erro6"></label>
                    </div>
                    <div class="row justify-content-center linha">
                        <a class="waves-effect waves-light btn btn-confirmar" id="botaoFinalizar" onclick="inscreverUsuario()">Finalizar registro</a>
                    </div>
                    <script>
                        $(window).on("resize", function() {
                            resizer()
                        });

                        function resizer() {
                            $("#perg1").height($("#resp1").outerHeight())
                            $("#perg2").height($("#resp2").outerHeight())
                            $("#perg3").height($("#resp3").outerHeight())
                            $("#perg4").height($("#resp4").outerHeight())
                        }
                    </script>
                </form>
            </div>
            <!-- Parte 3 -->
            <div class="parte3 parte">
                <div class="row justify-content-center">
                    <div>
                        <div class="row justify-content-center">
                            <img class="imgEmail" src="../icones/email-IMG.png">
                        </div>
                        <h2 class="emailTexto">Enviamos um e-mail para você confirmar sua inscrição!</h2>
                        <hr>
                        <h2 class="emailTexto">Confira sua caixa de e-mails, incluíndo o lixo</h2>
                        <div class="row justify-content-center" style="margin-top:40px;margin-bottom: 5px;">
                            <a class="waves-effect waves-light btn btn-parte4" id="botaoReeenviar" onclick="enviarEmailConfirmação(true)">Enviar E-mail novamente</a>
                        </div>
                        <div class="row justify-content-center">
                            <a class="waves-effect waves-light btn btn-parte4" id="botaoCancelar" onclick="refazerInscrição()">Refazer minha inscrição</a>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="row justify-content-center footerLinha">
                    <h4 class="copyright-titulo">Sistema de Gerenciamento de<br id="brTitulo"> Atendimento ao Estudante</h4>
                </div>
                <div class="row justify-content-center footerLinha">
                    <h4 class="copyright">© 2020 SiGAÊ | Desenvolvimento:
                        <a class="linkFooter" id="linkFooter1" href="https://portal.ifba.edu.br/" target="_blank">Instituto Federal da Bahia</a>
                        <a class="linkFooter" id="linkFooter2" href="https://portal.ifba.edu.br/" target="_blank">IFBA</a></p>
                    </h4>
                </div>
                <div class="row justify-content-center footerLinha">
                    <h6 class="versao">null</h6>
                </div>
            </footer>
        </div>
    </div>
    <script>
        window.onload = function() {
            setTimeout(function() {
                sairCarregamento()
                init()
            }, 500);
        }
    </script>
</body>

</html>