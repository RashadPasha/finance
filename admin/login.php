<?php
declare(strict_types=1);
$configFile=dirname(__DIR__).'/config/config.php';if(!is_file($configFile)){http_response_code(503);exit('Config faylını yaradın.');}
$c=require $configFile;session_name($c['security']['session_name']??'rashadfinance_admin');session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']),'samesite'=>'Strict']);session_start();
if(!empty($_SESSION['rf_admin'])){header('Location: index.php');exit;}
if(empty($_SESSION['rf_csrf']))$_SESSION['rf_csrf']=bin2hex(random_bytes(32));$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!hash_equals($_SESSION['rf_csrf'],(string)($_POST['csrf']??''))){$error='Sorğu təsdiqlənmədi.';}
 elseif(password_verify((string)($_POST['password']??''),(string)$c['security']['admin_password_hash'])){session_regenerate_id(true);$_SESSION['rf_admin']=true;header('Location: index.php');exit;}
 else{$error='Şifrə yanlışdır.';}
}
?><!doctype html><html lang="az"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin giriş — RashadFinance</title><link rel="stylesheet" href="../public/assets/css/admin.css"></head><body class="login-body"><form class="login-card" method="post"><div class="login-logo"><b>R</b> RASHAD<span>FINANCE</span></div><small>ADMINISTRATOR ACCESS</small><h1>İdarəetmə paneli</h1><p>Davam etmək üçün administrator şifrəsini daxil edin.</p><?php if($error):?><div class="error"><?=htmlspecialchars($error)?></div><?php endif;?><input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['rf_csrf'])?>"><label>Şifrə<input type="password" name="password" required autofocus autocomplete="current-password"></label><button type="submit">Daxil ol <span>↗</span></button><a href="../public/">← Sayta qayıt</a></form></body></html>