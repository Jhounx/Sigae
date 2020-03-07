<?php include("./misc.php"); include("./logar.php");
    /* Login */
    if(isset($_POST["login"]) and isset($_POST["senha"])) {
        $login = proteger($_POST["login"]);
        $senha = proteger($_POST["senha"]);
        sleep(2);
        echo(logar($login, $senha));

    }
