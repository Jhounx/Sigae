<?php
require $_SERVER['DOCUMENT_ROOT'] . '/back-end/autoloader.php';
$user = new Usuario();
session_name('sessao');
session_set_cookie_params(3600 * 24);
session_start();
if (isset($_GET['finalizarSessao'])) {
    $user->logout();
    header('location: ../login');
}
if (!isset($_SESSION['permissaoSistema'])) {
    header('location: ../login?expirado');
}
$id = $_SESSION['permissaoSistema'];
$dados = $user->pegarDadosUsuario($_SESSION['permissaoSistema']);
?>
<html>

<head>
    <title>SiGAÊ - IFBA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Pedro Cerqueira Mota, João Costa Neto, Rafael Requião">
    <link rel="icon" href="./icones/si.png">
    <link rel="stylesheet" href="./componentes/APIs/materialDesignIcons.css">
    <link rel="stylesheet" href="./componentes/APIs/bootstrap.min.css">
    <link rel="stylesheet" href="./componentes/APIs/materialize.min.css">
    <link rel="stylesheet" href="./componentes/APIs/hamburgers.min.css">
    <link rel="stylesheet" href="./componentes/APIs/bootstrap-select.min.css">
    <link rel="stylesheet" href="./componentes/css/loading.css">
    <link rel="stylesheet" href="./componentes/css/select.css">
    <link rel="stylesheet" href="./componentes/css/popup.css">
    <link rel="stylesheet" href="./css.css">
    <link rel="stylesheet" href="./responsive.css">
    <script src="./componentes/APIs/jquery.min.js"></script>
    <script src="./componentes/APIs/popper.min.js"></script>
    <script src="./componentes/APIs/bootstrap.min.js"></script>
    <script src="./componentes/APIs/sweetalert2@8.js"></script>
    <script src="./componentes/APIs/calendarize.js"></script>
    <script src="./componentes/APIs/moment.js"></script>
    <script src="./componentes/APIs/materialize.min.js"></script>
    <script src="./componentes/APIs/bootstrap-select.min.js"></script>
    <script src="./componentes/APIs/jquery.mask.min.js"></script>
    <script src="./componentes/Modulos.js"></script>
    <script src="./componentes/ModulosSeparados.js"></script>
    <script src="./componentes/Atendimento.js"></script>
    <script src="./componentes/Console.js"></script>
    <script src="./componentes/Request.js"></script>
    <script src="./componentes/DadosGlobais.js"></script>
    <script src="./componentes/Utils.js"></script>
    <script src="./javascript.js"></script>
</head>

<body style="width: 100% !important">
    <div class="divCarregamento">
        <img class="imgCarregamento" src="./icones/loading.svg">
        <div class="noJS">Essa página precisa de Javascript. Ative-o!</div>
    </div>
    <noscript>
        <style type="text/css">
            .noJS {
                display: block;
            }
        </style>
    </noscript>
    <div class="tudo">
        <div class="sistema">
            <header style="height: 100px !important;min-height: 100px!important;">
                <div class="navbar-fixed">
                    <nav class="navbar">
                        <div class="navbarEsquerda">
                            <a data-activates="slide-out" class="button-collapse">
                                <div class="hamburger hamburger--collapse js-hamburger">
                                    <div class="hamburger-box">
                                        <div class="hamburger-inner"></div>
                                    </div>
                                </div>
                            </a>
                            <div class="row justify-content-center linhaTituloImg">
                                <a href="./">
                                    <img class="imgTitulo" src="./icones/sigae-min.png" width="155" height="54">
                                </a>
                            </div>
                        </div>
                        <div class="navbarDireita">
                            <h1 class="titulo">Sistema de Gerenciamento de Atendimento ao Estudante</h1>
                        </div>
                    </nav>
                </div>
            </header>
            <main>
                <ul id="slide-out" class="side-nav fixed">
                    <div class="sideDiv">
                        <div class="divTitulo">
                            <div style="margin-top: 10px;margin-bottom: 10px;" class="row justify-content-center">
                                <img class="materialboxed circle" width="100" height="100" src="./back-end/request.php?pegarFoto">
                            </div>
                            <h2 class="nome"></h2>
                            <h2 class="tipo"></h2>
                            <br>
                        </div>
                        <div class="divLinhas">

                        </div>
                    </div>
                </ul>
                <div class="content">
                    <div class="breadcrumbsDiv">
                        <div class="breadcrumbs breadcrumbs-tooltipped" data-position="bottom" data-tooltip="">
                            <i class="small material-icons breadcrumbs-icone"></i>
                            <h4 class="breadcrumbsTitulo"></h4>
                        </div>
                        <img class="imgCarregamento" id="carregamentoModulo" src="./icones/spinner.svg">
                    </div>
                </div>
                <div class="content-libs"></div>
                <div class="content-head"></div>
            </main>
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
            <script id="scriptJson">
                <?php
                echo "init_sigae('$dados')"
                ?>
            </script>
        </div>
        <!-- console -->
        <div id="consoleModal" class="modal consoleModal bottom-sheet">
            <div class="modal-content">
                <div class="consoleTituloDiv">
                    <img src="./icones/si.png" width="40" height="40">
                    <b class="consoleTexto">Abrir console</b>
                    <div class="divBotoes">
                        <a class="dropdown-button consoleBotao consoleBotao-ferramentas" data-beloworigin="true" data-activates="dropdownConsole"><i class="material-icons">arrow_drop_down</i></a>
                        <ul id="dropdownConsole" class="dropdown-content dropdown-console">
                            <li><a style="text-decoration: none; " href="javascript:void(0)" onclick="limparConsole()"><i class="material-icons">delete_forever</i>Limpar</a></li>
                        </ul>
                        <a class="consoleBotao" onclick="fecharConsole()">Fechar</a>
                    </div>
                </div>
            </div>
            <div class="conteudoConsole"></div>
            <div class="noAlertas">Não há nenhum novo alerta ou erro</div>
        </div>
        <!-- notificacoes -->
        <!-- <div class="notificacoesDiv"></div>
        <div class="circuloNotificacao">
            <i class="material-icons notificacoesMenu">menu</i>
        </div> -->
    </div>
    <div class="headerPopup"></div>
</body>

</html>