<?php 
    require $_SERVER['DOCUMENT_ROOT'] . '/back-end/autoloader.php';
    $sys = new Admin();
    if(isset($_GET['codigo'])) {
        $codigo = $sys->proteger($_GET['codigo']);
        if(!$sys->validarCodigoEmail($codigo)) {
            echo "Código inválido";
            exit();
        }
    } else {
        echo "Não foi declarado um código válido";
        exit();
    }
?>
<html>

<head>
    <title>SiGAÊ - IFBA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Pedro Cerqueira Mota, João Costa Neto, Rafael Requião">
    <link rel="icon" href="../icones/si.png">
    <link rel="stylesheet" href="../componentes/APIs/bootstrap.min.css">
    <link rel="stylesheet" href="../componentes/APIs/materialize.min.css">
    <link rel="stylesheet" href="../componentes/APIs/materialDesignIcons.css">
    <link rel="stylesheet" href="../componentes/css/loading.css">
    <link rel="stylesheet" href="./css.css">
    <link rel="stylesheet" href="./responsive.css">
    <script src="../componentes/APIs/jquery.min.js"></script>
    <script src="../componentes/APIs/bootstrap.min.js"></script>
    <script src="../componentes/APIs/materialize.min.js"></script>
    <script src="../componentes/Request.js"></script>
    <script src="../componentes/Utils.js"></script>
    <script src="./javascript.js"></script>
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
    <div class="centro">
        <div class="linhaTitulo row justify-content-center">
            <img class="imgSigae" src="../icones/sigae.svg" width="150" height="91">
        </div>
        <h1 class="tituloDigitar">Digitar nova senha</h1>
        <div class="popupShow popupShow1">
            <h7 class="forcaTitulo"><b>Força de senha: </b><a id="força">Inválida</a></h7>
            <div class="progress">
                <div class="determinate"></div>
            </div>
            <h6 class="forcaSub">
                Mínimo de 6 caracteres. Não use uma senha de outros sites, ou algo muito óbvio - como o seu próprio nome
            </h6>
        </div>
        <div class="popupShow popupShow2">
            <h6 class="diferentes">
                As duas senhas não coincidem
            </h6>
        </div>
        <div class="row justify-content-center linhaCampo">
            <div class="input-field">
                <input id="senha1" type="password" autocomplete="false" onfocus="ganhouFocus(1)" onfocusout="perdeuFocus(1)">
                <label id="label1" for="senha1">Senha</label>
            </div>
            <label class="msgInputError" id="erro1"></label>
        </div>
        <div class="row justify-content-center linhaCampo">
            <div class="input-field">
                <input id="senha2" type="password" autocomplete="false" onfocus="ganhouFocus(2)" onfocusout="perdeuFocus(2)">
                <label id="label2" for="senha2">Repetir senha</label>
            </div>
            <label class="msgInputError" id="erro2"></label>
        </div>
        <div class="row justify-content-center">
            <a disabled class="waves-effect waves-light btn btn-confirmar" id="botaoFinalizar" onclick="enviarSenha()">Trocar senha</a>
        </div>
    </div>
    <script>
        $(window).on("load", function() {
            setTimeout(function() {
                $(".divCarregamento").fadeOut(500);
                $(".centro").fadeIn(500);
                init()
            }, 500);
        })
    </script>
</body>

</html>