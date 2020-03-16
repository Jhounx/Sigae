<?php
function enviarEmail($titulo, $body, $remetentes) {
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
    $mail->SMTPDebug = 1;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = $servidor;
    $mail->Port = $porta;
    $mail->IsHTML(true);
    $mail->Username = $endereco;
    $mail->Password = $senha;
    $mail->SetFrom($endereco);
    $mail->Subject = $titulo;
    $mail->Body = $body;
    for($i = 0; $i < count($remetentes); $i++) {
        $mail->AddAddress($remetentes[$i]);
    }

    if (!$mail->Send()) {
        return "SIM";
    } else {
        return "NAO";
    }
}

function enviarEmailConfirmacao() {
    
}