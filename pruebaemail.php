<?php

   error_reporting( E_ALL & ~( E_NOTICE | E_STRICT | E_DEPRECATED ) ); //Aquí se genera un control de errores "NO BORRAR NI SUSTITUIR"

   require_once "Mail.php"; //Aquí se llama a la función mail "NO BORRAR NI SUSTITUIR"

   $to = 'msredhotero@gmail.com'; //Aquí definimos quien recibirá el formulario
   $from = 'adminriderz@areariderz.es'; //Aquí definimos que cuenta mandará el correo, generalmente perteneciente al mismo dominio
   $host = '217.116.0.228'; //Aquí definimos cual es el servidor de correo saliente desde el que se enviaran los correos
   $username = 'adminriderz@areariderz.es'; //Aqui se define el usuario de la cuenta de correo
   $password = '_Riderzapp123'; //Aquí se define la contraseña de la cuenta d ecorreo que enviará el mensaje
   $subject = 'Prueba'; //Aquí se define el asunto del correo
   $body = 'Marcos prueba'; //Aquí se define el cuerpo de correo

   //A partir de aquí empleamos la función mail para enviar el formulario

   $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
   $smtp = Mail::factory('smtp',
   array ('host' => $host,
   'auth' => true,
   'username' => $username,
   'password' => $password));

   $mail = $smtp->send($to, $headers, $body);

   //Una vez aquí habremos enviado el mensaje mediante el formulario

   //El siguiente codigo muestra en pantalla un mensaje indicando que el mensaje ha sido enviado y a que cuenta ES OPCIONAL. Lo incluimos para verificar que el formulario de prueba esta funcionando

   if (PEAR::isError($mail)) {
      echo("".$mail->getMessage()."");
   } else {
      echo "Mensaje enviado desde POA a ". $to ;
   }

 ?>
