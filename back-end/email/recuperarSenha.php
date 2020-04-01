<?php
    //sleep(2);
    require('../main.php');
    require('../security.php');
    if (isset($_GET['codigo'])) {
        $codigo = proteger($_GET['codigo']);
        $query = mysqli_query($conn, "SELECT * FROM codigos_email where valor='$codigo' and tipo = 'REC' limit 1");
        if (!mysqli_exist($query)) {
            echo 'Código inválido';
            die();
        }
        $array = mysqli_fetch_assoc($query);
        $id = $array['id'];
        addPermissao($id, 'trocarSenha');
    } else {
        echo 'Código inválido';
        die();
    }
?>
<html>

<head>
    <title>SiGAÊ - Nova senha</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Pedro Cerqueira Mota, João Costa Neto, Rafael Requião" />
    <link rel="stylesheet" href="../../componentes/APIs/bootstrap.min.css">
    <link rel="stylesheet" href="../../componentes/APIs/materialize.min.css">
    <link rel="icon" href="../../icones/si.png">
    <link rel="stylesheet" href="../../componentes/APIs/icon.css">
    <link rel="stylesheet" href="../../componentes/popup.css">
    <script src="../../componentes/APIs/jquery.min.js"></script>
    <script src="../../componentes/APIs/sweetalert2@8.js"></script>
    <script src="../../componentes/APIs/bootstrap.min.js"></script>
    <script src="../../componentes/APIs/param.js"></script>
    <script src="../../componentes/request.js"></script>
    <script src="../../componentes/Popup.js"></script>
    <script src="../../componentes/Misc.js"></script>
    <script src="../../componentes/APIs/materialize.min.js"></script>
</head>

<body style="overflow-y:hidden!important">
    <style>
        body {
            width: 100%;
            height: 100%;
            background: linear-gradient(#606c88, #3f4c6b);
            position: relative !important;
        }
    </style>
    <script>
        codigo = <?php echo "'$codigo';\n";?>
        $(document).ready(function() {
            recuperarSenha = new Popup("recuperarSenha", "../../modulos/login/recuperarSenha", "Digitar nova senha", null, "420px");
            recuperarSenha.setScroll(false)
            recuperarSenha.setCss(true)
            recuperarSenha.setJS(true)
            recuperarSenha.setImgPath("../../icones/sigae.svg")
            recuperarSenha.clicarFora(false)
            recuperarSenha.setBotao(false)
            recuperarSenha.invoker()
            recuperarSenha.show()
        });
    </script>
    <div class="headerPopup"></div>
</body>

</html>