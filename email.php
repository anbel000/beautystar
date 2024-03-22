<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["message"])) {
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'beautystar2812@gmail.com';                 // SMTP username
    $mail->Password = 'zmxyohqvvehjwjjh';                           // SMTP password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->From = 'beautystar2812@gmail.com';
    $mail->FromName = 'Beauty Star';
    $mail->addAddress('beautystar2812@gmail.com');               // Name is optional

    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->CharSet = "utf-8";
    $message = file_get_contents('assets/templates/contact.html');
    $message = str_replace('%name%', $_POST["name"], $message);
    $message = str_replace('%email%', $_POST["email"], $message);
    $message = str_replace('%message%', $_POST["message"], $message);

    $mail->MsgHTML($message);
    $mail->Subject = 'Formulario De Contacto - Beauty Star';
    //$mail->Body    = 'This is the HTML message body <b>in bold!</b>';

    if (!$mail->send()) {
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
        $response = new stdClass();
        $response->code = 400;
        $response->message = "No se ha podido enviar el formulario correctamente, intentalo más tarde.";
        echo json_encode($response);
    } else {

        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'beautystar2812@gmail.com';                 // SMTP username
        $mail->Password = 'zmxyohqvvehjwjjh';                           // SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->From = 'beautystar2812@gmail.com';
        $mail->FromName = 'Beauty Star';
        $mail->addAddress($_POST["email"]);               // Name is optional

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->CharSet = "utf-8";
        $message = file_get_contents('assets/templates/response_contact.html');
        $message = str_replace('%name%', $_POST["name"], $message);

        $mail->MsgHTML($message);
        $mail->Subject = 'Formulario De Contacto - Beauty Star';

        if (!$mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            $response = new stdClass();
            $response->code = 200;
            $response->message = "Se ha enviado el formulario correctamente, sin embargo, no hemos podido enviarte la confirmación a tu correo electronico, por favor revisa que este bien escrito, si es asi, no te preocupes, el equipo de Beauty Star se comunicara contigo lo más pronto posible.";
            echo json_encode($response);
        } else {
            $response = new stdClass();
            $response->code = 200;
            $response->message = "Se ha enviado el formulario correctamente.";
            echo json_encode($response);
        }
    }
} else {
    $response = new stdClass();
    $response->code = 400;
    $response->message = "Error al enviar el formulario de contacto.";
    echo json_encode($response);
}
