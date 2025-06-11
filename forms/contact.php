<?php

/**
 * Secure PHP Email Form Handler
 * Based on BootstrapMade's PHP Email Form
 */

// Dirección de correo que recibirá los mensajes
$receiving_email_address = 'ramonnuia22@gmail.com';

// Verifica si la librería está disponible
if (!file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
  die('Unable to load the "PHP Email Form" Library!');
}
include($php_email_form);

// Función para detectar inyección de encabezados
function has_header_injection($str)
{
  return preg_match("/[\r\n]/", $str);
}

// Sanitización y validación básica de entradas
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

// Validaciones
if (empty($name) || empty($email) || empty($message)) {
  die('All fields are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  die('Invalid email address.');
}

if (has_header_injection($name) || has_header_injection($email)) {
  die('Header injection detected.');
}

// Instancia del formulario
$contact = new PHP_Email_Form;
$contact->ajax = true;

$contact->to = $receiving_email_address;
$contact->from_name = $name;
$contact->from_email = $email;
$contact->subject = $subject;

// Si querés usar SMTP, descomentá y configurá esto:
/*
$contact->smtp = array(
  'host' => 'smtp.tuservidor.com',
  'username' => 'tuusuario',
  'password' => 'tucontraseña',
  'port' => '587'
);
*/

// Cuerpo del mensaje
$contact->add_message($name, 'From');
$contact->add_message($email, 'Email');
$contact->add_mess_age($subject, 'Subject');
$contact->add_message($message, 'Message', 10);
// Envío del mensaje
if ($contact->send()) {
  echo 'Message sent successfully!';
} else {
  echo 'Failed to send message. Please try again later.';
}
// Fin del script