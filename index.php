<?php
require $_SERVER['DOCUMENT_ROOT'] . "/back-end/autoloader.php";
$user = new Usuario();
session_name('sessao');
session_set_cookie_params(3600 * 24);
session_start();
if (isset($_SESSION['permissaoSistema'])) {
    $param = "";
    $i = 1;
    foreach ($_GET as $key => $value) {
        if(count($_GET) == $i) {
            $param = $param . $key . "=" . $value;
        } else {
            $param = $param . $key . "=" . $value . "&";
        }
        $i++;
    }
    if($param == "") {
        header('location: ../sistema');
    } else {
        header('location: ../sistema?' . $param);
    }
}
?>
<html>

<head>
    <title>SiGAÊ - IFBA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Pedro Cerqueira Mota, João Costa Neto, Rafael Requião" />
    <link rel="stylesheet" href="./componentes/APIs/bootstrap.min.css">
    <link rel="stylesheet" href="./componentes/APIs/icon.css">
    <link rel="stylesheet" href="./css.css">
    <link rel="stylesheet" href="./componentes/popup.css">
    <link rel="stylesheet" href="./responsive.css">
    <link rel="stylesheet" href="./componentes/loading.css">
    <link rel="icon" href="./icones/si.png">
    <script src="./componentes/APIs/jquery.min.js"></script>
    <script src="./componentes/APIs/sweetalert2@8.js"></script>
    <script src="./componentes/APIs/floatingLabel.js"></script>
    <script src="./componentes/APIs/popper.min.js"></script>
    <script src="./componentes/APIs/bootstrap.min.js"></script>
    <script src="./componentes/APIs/jquery.mask.min.js"></script>
    <script src="./componentes/parans.js"></script>
    <script src="./javascript.js"></script>
    <script src="./componentes/Popup.js"></script>
    <script src="./componentes/request.js"></script>
    <script src="./componentes/Misc.js"></script>
</head>

<body style="overflow-x: hidden !important;overflow-y: hidden !important;">
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
    <div class="centro">
        <div class="parteBaixo">
            <h2 class="copyright">© 2020 SiGAÊ | <a onclick="sobre.show()" class="desenvolvimento">Desenvolvimento</a>: <a style="color: rgb(40, 167, 69)" href="https://portal.ifba.edu.br" target="_blank">IFBA</a></h2>
        </div>
        <div class="row justify-content-center">
            <a style="text-decoration: none; text-align:center" href="./">
                <img class="img" src="./icones/sigae.svg" width="224" height="136">
                <h1 class="titulo">Sistema de Gerenciamento de<br> Atendimento ao Estudante</h1>
            </a>
        </div>
        <form class="form">
            <div class="row justify-content-center linha1">
                <div class="divInput">
                    <label id="login-label" for="login">Sua matrícula</label>
                    <input type="number" class="campo" id="login" placeholder="Sua matrícula" spellcheck="false" autocomplete="off">
                </div>
            </div>
            <div class="row justify-content-center linha2">
                <div class="divInput">
                    <label id="senha-label" for="senha">Sua senha</label>
                    <input type="password" class="campo" id="senha" placeholder="Sua senha" spellcheck="false" autocapitalize="none">
                </div>
            </div>
            <div class="row justify-content-center linha3">
                <button type="button" onclick="logar()" class="btn btn-success botaoLogin" id="botao">Acessar</button>
            </div>
            <div class="row justify-content-center linha4">
                <div class="baixo">
                    <h2 class="links" onclick="comoCriar.show()">Como realizar meu registro?</h2>
                    <h2 class="links direita" onclick="esqueciSenha.show()">Esqueci minha senha</h2>
                </div>
            </div>
            <div id="waiting">
                <div class="row justify-content-center">
                    <div class="spinner-border text-secondary" id="spinner-login" role="status"></div>
                </div>
            </div>
            <div id="erro">
                <div class="row justify-content-center">
                    <div id="alert-login" class="alert alert-danger alert-dismissible fade show alert-login" role="alert">
                        <h8 class="erroTexto" style="text-align:center"></h8>
                        <button type="button" class="close" onclick="main();">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script class="script">
        window.onload = function() {
            setTimeout(function() {
                $(".divCarregamento").fadeOut(500);
                $(".centro").fadeIn(500);
                rightAlertas()
                posInit()
            }, 500);
        }
        init()
    </script>
    <div class="headerPopup"></div>
</body>

</html>