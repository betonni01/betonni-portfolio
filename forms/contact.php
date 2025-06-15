<?php
// Adresse de réception
$receiving_email_address = 'contact@tondomaine.com';

// Honeypot anti-bot
if (!empty($_POST['website'])) {
  // Champ invisible rempli → bot détecté
  die('Spam détecté.');
}

// Chargement de la librairie de formulaire
if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
  include($php_email_form);
} else {
  die('Librairie PHP Email Form non trouvée.');
}

$contact = new PHP_Email_Form;
$contact->ajax = true;
$contact->to = $receiving_email_address;

//  Sécurisation des champs
function sanitize_input($data) {
  return htmlspecialchars(strip_tags(trim($data)));
}

//  Validation
$name = sanitize_input($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$subject = sanitize_input($_POST['subject'] ?? '');
$message = sanitize_input($_POST['message'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');

if (!$name || !$email || !$subject || !$message) {
  die('Tous les champs requis ne sont pas correctement remplis.');
}

$contact->from_name = $name;
$contact->from_email = $email;
$contact->subject = $subject;

$contact->add_message($name, 'Nom');
$contact->add_message($email, 'Email');
if (!empty($phone)) {
  $contact->add_message($phone, 'Téléphone');
}
$contact->add_message($message, 'Message', 10);

//  (Optionnel) SMTP sécurisé
/*
$contact->smtp = array(
  'host' => 'smtp.tondomaine.com',
  'username' => 'tonemail@tondomaine.com',
  'password' => 'mot_de_passe_fort',
  'port' => '587',
  'encryption' => 'tls'
);
*/

echo $contact->send();
?>
