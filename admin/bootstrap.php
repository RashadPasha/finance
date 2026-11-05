<?php
declare(strict_types=1);
$configFile=dirname(__DIR__).'/config/config.php';
if(!is_file($configFile)){http_response_code(503);exit('Əvvəlcə config/config.php faylını yaradın.');}
$config=require $configFile;
date_default_timezone_set($config['app']['timezone']??'Asia/Baku');
session_name($config['security']['session_name']??'rashadfinance_admin');
session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']),'samesite'=>'Strict']);
if(session_status()!==PHP_SESSION_ACTIVE) session_start();
if(empty($_SESSION['rf_admin'])){header('Location: login.php');exit;}
$db=$config['database'];
$pdo=new PDO("mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",$db['username'],$db['password'],[
 PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
 PDO::ATTR_EMULATE_PREPARES=>false
]);
if(empty($_SESSION['rf_csrf'])) $_SESSION['rf_csrf']=bin2hex(random_bytes(32));
function e(?string $v):string{return htmlspecialchars($v??'',ENT_QUOTES,'UTF-8');}
function csrf_field():string{return '<input type="hidden" name="csrf" value="'.e($_SESSION['rf_csrf']).'">';}
function verify_csrf():void{if(!hash_equals($_SESSION['rf_csrf']??'',(string)($_POST['csrf']??''))){http_response_code(419);exit('Sorğu müddəti bitib. Səhifəni yeniləyin.');}}
function slugify(string $value):string{
 $map=['ə'=>'e','Ə'=>'e','ı'=>'i','İ'=>'i','ö'=>'o','Ö'=>'o','ü'=>'u','Ü'=>'u','ğ'=>'g','Ğ'=>'g','ş'=>'s','Ş'=>'s','ç'=>'c','Ç'=>'c'];
 $value=strtr(trim(mb_strtolower($value,'UTF-8')),$map);
 $value=preg_replace('/[^a-z0-9]+/u','-',$value)??'';
 return trim($value,'-')?:'item-'.bin2hex(random_bytes(3));
}
function admin_header(string $title,string $active):void{ ?>
<!doctype html><html lang="az"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title)?> — RashadFinance</title><link rel="stylesheet" href="../public/assets/css/admin.css"></head><body>
<aside class="sidebar"><a class="brand" href="index.php"><b>R</b> RASHAD<span>FINANCE</span></a><small>İDARƏETMƏ PANELİ</small><nav>
<a class="<?=$active==='dashboard'?'active':''?>" href="index.php">◫ &nbsp; Dashboard</a><a class="<?=$active==='articles'?'active':''?>" href="articles.php">▤ &nbsp; Məqalələr</a><a class="<?=$active==='categories'?'active':''?>" href="categories.php">▦ &nbsp; Kateqoriyalar</a><a href="videos.php">▷ &nbsp; Videolar</a><a href="services.php">◇ &nbsp; Xidmətlər</a><a href="projects.php">▧ &nbsp; Layihələr</a><a href="portfolio.php">◎ &nbsp; Portfolio</a><a href="inquiries.php">✉ &nbsp; Müraciətlər</a><a href="settings.php">⚙ &nbsp; Parametrlər</a></nav><a class="logout" href="logout.php">Çıxış ↗</a></aside>
<main class="main"><header><div><small>RASHADFINANCE / CONTENT MANAGEMENT</small><h1><?=e($title)?></h1></div><span class="admin-user">Administrator</span></header>
<?php }
function admin_footer():void{echo '<p class="footnote">RashadFinance Admin · İdarəetmə paneli</p></main></body></html>';}
