<?php
function enviarEmail($titulo, $nameFile, $propsName, $propsValues, $remetentes) {
    require("./phpMailer/PHPMailer.php");
    require("./phpMailer/SMTP.php");
    require("../main.php");

    $query = $conn->query("SELECT * from email limit 1");
    $array = mysqli_fetch_array($query);
    $servidor = $array["servidor"];
    $porta = $array["porta"];
    $endereco = $array["endereco"];
    $senha = $array["senha"];

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = false;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->CharSet = "UTF-8";
    $mail->Host = $servidor;
    $mail->Port = $porta;
    $mail->IsHTML(true);
    $mail->Username = $endereco;
    $mail->Password = $senha;
    $mail->SetFrom($endereco);
    $mail->Subject = $titulo;

    $html = file_get_contents("./html/" . $nameFile);
    $body = str_replace($propsName, $propsValues, $html);

    $mail->Body = $body;
    for($i = 0; $i < count($remetentes); $i++) {
        $mail->AddAddress($remetentes[$i]);
    }

    if ($mail->Send()) {
        return "SIM";
    } else {
        return "NAO";
    }
}