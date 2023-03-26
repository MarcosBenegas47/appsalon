<?php
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    public $email;
    public $nombre;
    public $token;
    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;

    }
    public function enviarConfirmacion(){
        //crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '25dddf8a5301aa';
        $mail->Password = '9b7778473878d3';
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');     //Add a recipient
        $mail->Subject = "Confirma tu cuenta";
        //set html
        $mail->isHTML(TRUE);


        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> has creado tu cuenta en App Salon
        solo debes confirmarla precionando el siguiente enlace</p>";
        $contenido .= "<p>Preciona aqui: <a href='http://localhost:3000/confirmar-cuenta?token=".
        $this->token."'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no soloicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        //enviar email
        $mail->send();
    }
    public function enviarInstrucciones(){
                //crear el objeto de email
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'sandbox.smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Port = 2525;
                $mail->Username = '25dddf8a5301aa';
                $mail->Password = '9b7778473878d3';
                $mail->setFrom('cuentas@appsalon.com');
                $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');     //Add a recipient
                $mail->Subject = "Restablece tu contraseña";
                //set html
                $mail->isHTML(TRUE);
        
        
                $contenido = "<html>";
                $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Has soliitado reestablecer tu
                contraseña, sigue el siguiente enlace para hacerlo</p>";
                $contenido .= "<p>Preciona aqui: <a href='http://localhost:3000/recuperar?token=".
                $this->token."'>Reestablecer contraseña</a></p>";
                $contenido .= "<p>Si tu no soloicitaste esta cuenta, puedes ignorar el mensaje</p>";
                $contenido .= "</html>";
                $mail->Body = $contenido;
                //enviar email
                $mail->send();
    }
}