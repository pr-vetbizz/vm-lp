<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  die("Acesso direto não permitido.");
}

$name = htmlspecialchars($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars($_POST['phone'] ?? '');
$camv = htmlspecialchars($_POST['camv'] ?? '');
$role = htmlspecialchars($_POST['role'] ?? '');
$obs = htmlspecialchars($_POST['obs'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['success' => false, 'message' => "Email inválido."]);
  return;
}

$message = "
<h3>Novo pedido de contacto - Vet Manager</h3>
<p><strong>Nome:</strong> {$name}</p>
<p><strong>Email:</strong> {$email}</p>
<p><strong>Telefone:</strong> {$phone}</p>
<p><strong>CAMV:</strong> {$camv}</p>
<p><strong>Função:</strong> {$role}</p>
<p><strong>Observações:</strong><br>{$obs}</p>
";

$mail = new PHPMailer(true);

try {
  $mail->CharSet = "UTF-8";
  $mail->isSMTP();
  $mail->Host = 'mail.vet-manager.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'test@vet-manager.com';
  $mail->Password = '5[sod2_v?7HS';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;

  $mail->setFrom("documentos@vet-manager.com", "Vet Manager");
  // $mail->addAddress("info@vet-manager.com");
  $mail->addAddress("pedro.rocha@vetbizz.pt");
  $mail->addReplyTo($email, $name);

  $mail->isHTML(true);
  $mail->Subject = "VetManager - Formulário de Contacto";
  $mail->Body = $message;
  $mail->AltBody = strip_tags($message);

  $mail->send();
  echo json_encode(['success' => true, 'message' => 'Pedido de contacto enviado com sucesso.']);
} catch (Exception $e) {
  error_log($mail->ErrorInfo);
  echo json_encode(['success' => false, 'message' => 'Ocorreu um erro ao enviar o pedido de contacto.']);
}

exit;
