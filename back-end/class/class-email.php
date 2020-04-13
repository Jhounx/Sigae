<?php

class Email extends Banco {

    public function enviarEmail($titulo, $nameFile, $propsName, $propsValues, $remetentes) {
        require($_SERVER['DOCUMENT_ROOT'] . "/back-end/class/phpMailer/PHPMailer.php");
        require($_SERVER['DOCUMENT_ROOT'] . "/back-end/class/phpMailer/SMTP.php");

        $query = $this->conn->query('SELECT * from email limit 1');
        $array = mysqli_fetch_array($query);
        $servidor = $array['servidor'];
        $porta = $array['porta'];
        $endereco = $array['endereco'];
        $senha = $array['senha'];

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = false;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->CharSet = 'UTF-8';
        $mail->Host = $servidor;
        $mail->Port = $porta;
        $mail->IsHTML(true);
        $mail->Username = $endereco;
        $mail->Password = $senha;
        $mail->SetFrom($endereco);
        $mail->Subject = $titulo;

        $html = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/back-end/class/html/' . $nameFile);
        $body = str_replace($propsName, $propsValues, $html);

        $mail->Body = $body;
        for ($i = 0; $i < count($remetentes); $i++) {
            $mail->AddAddress($remetentes[$i]);
        }

        if ($mail->Send()) {
            return 'SIM';
        }

        return 'NAO';
    }
}
