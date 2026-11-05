<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['message'=>'Method not allowed']); exit; }
$configPath = dirname(__DIR__, 2) . '/config/config.php';
if (!is_file($configPath)) { http_response_code(503); echo json_encode(['message'=>'Sistem hələ quraşdırılmayıb.']); exit; }
$config = require $configPath;
$name = trim((string)($_POST['full_name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '' || mb_strlen($message)>3000) { http_response_code(422); echo json_encode(['message'=>'Məlumatları düzgün doldurun.']); exit; }
try {
 $db=$config['database']; $pdo=new PDO("mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",$db['username'],$db['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
 $stmt=$pdo->prepare('INSERT INTO inquiries(full_name,email,phone,message) VALUES(:n,:e,:p,:m)');
 $stmt->execute(['n'=>$name,'e'=>$email,'p'=>$phone,'m'=>$message]);
 echo json_encode(['ok'=>true,'message'=>'Müraciətiniz qəbul edildi.']);
} catch(Throwable $e) { http_response_code(500); echo json_encode(['message'=>'Müraciəti saxlamaq mümkün olmadı.']); }
