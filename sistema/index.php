<?php

?>
<html>

<head>
    <title>SiGAÊ - IFBA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Pedro Cerqueira Mota, João Costa Neto, Rafael Requião" />
    <link rel="stylesheet" href="../componentes/APIs/bootstrap.min.css">
    <link rel="stylesheet" href="../componentes/APIs/materialize.min.css">
    <link rel="stylesheet" href="../componentes/APIs/hamburgers.min.css">
    <link rel="stylesheet" href="../componentes/APIs/icon.css">
    <link rel="stylesheet" href="./css.css">
    <link rel="stylesheet" href="./responsive.css">
    <link rel="stylesheet" href="../componentes/loading.css">
    <link rel="stylesheet" href="../componentes/popup.css">
    <link rel="icon" href="../icones/si.png">
    <script src="../componentes/APIs/jquery-3.1.1.min.js"></script>
    <script src="../componentes/APIs/sweetalert2@8.js"></script>
    <script src="../componentes/APIs/floatingLabel.js"></script>
    <script src="./javascript.js"></script>
    <script src="../componentes/ModuloRender.js"></script>
    <script src="../componentes/Popup.js"></script>
    <script src="../componentes/Modulos.js"></script>
    <script src="../componentes/Misc.js"></script>
    <script src="../componentes/Console.js"></script>
    <script src="../componentes/APIs/materialize.min.js"></script>
    <script src="../componentes/APIs/param.js"></script>
</head>

<body>
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

    <div class="tudo">
        <div class="sistema">
            <header style="height: 100px !important;">
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
                                    <img class="imgTitulo" src="../icones/sigae-min.png" width="155" height="54">
                                </a>
                            </div>
                        </div>
                        <div class="navbarDireita">
                            <h1 class="titulo">Sistema de Gerenciamento de Atendimento ao Estudante</h1>
                        </div>
                    </nav>
                </div>
            </header>
            <main style="flex: 1 0 auto;">
                <ul id="slide-out" class="side-nav fixed">
                    <div class="sideDiv">
                        <div class="divTitulo">
                            <div style="margin-top: 10px;margin-bottom: 10px;" class="row justify-content-center">
                                <img class="materialboxed circle" width="100" height="100" src="../icones/pp.jpg">
                            </div>
                            <h2 class="nome">Pedro Mota</h2>
                            <h2 class="tipo">Aluno(a)</h2>
                            <br>
                        </div>
                        <div class="divLinhas">

                        </div>
                    </div>
                </ul>
                <div class="content">
                    <div class="breadcrumbs breadcrumbs-tooltipped" data-position="bottom" data-tooltip="">
                        <i class="small material-icons breadcrumbs-icone"></i>
                        <h4 class="breadcrumbsTitulo"></h4>
                    </div>
                    <img class="imgCarregamento" id="carregamentoModulo" src="../icones/spinner.svg">
                </div>
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
            <script>
                init()
                document.addEventListener("DOMContentLoaded", function(event) {
                    setTimeout(function() {
                        $(".divCarregamento").hide()
                        $(".tudo").fadeIn(1200);
                    }, 500);
                });
            </script>
        </div>
        <div id="consoleModal" class="modal consoleModal bottom-sheet">
            <div class="modal-content">
                <b class="consoleTexto">Console</b>
                <div class="divBotoes">
                    <a class="dropdown-button consoleBotao consoleBotao-ferramentas" data-beloworigin="true" data-activates="dropdownConsole">Ferramentas do console</a>
                    <ul id="dropdownConsole" class="dropdown-content dropdown-console">
                        <li><a style="text-decoration: none; " href="javascript:void(0)" onclick="limparConsole()"><i class="material-icons">delete_forever</i>Limpar console</a></li>
                    </ul>
                    <a class="consoleBotao" onclick="fecharConsole()">Fechar</a>
                </div>
            </div>
            <div class="conteudoConsole"></div>
            <div class="noAlertas">Não há nenhum alerta ou erro</div>
        </div>
    </div>
</body>

</html>