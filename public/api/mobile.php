<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=60');
function out(array $data,int $status=200):never{http_response_code($status);echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;}
$configFile=dirname(__DIR__,2).'/config/config.php';
if(!is_file($configFile))out(['ok'=>false,'message'=>'Platforma konfiqurasiya edilməyib.'],503);
$config=require $configFile;
try{$d=$config['database'];$pdo=new PDO("mysql:host={$d['host']};dbname={$d['name']};charset={$d['charset']}",$d['username'],$d['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}
catch(Throwable $e){out(['ok'=>false,'message'=>'Məlumat bazasına qoşulmaq mümkün olmadı.'],503);}
$resource=(string)($_GET['resource']??'home');
try{
 switch($resource){
 case 'home':
  $data=[
   'services'=>$pdo->query("SELECT id,title,slug,short_description,price,currency,price_label FROM services WHERE is_active=1 ORDER BY sort_order,id DESC LIMIT 6")->fetchAll(),
   'articles'=>$pdo->query("SELECT a.id,a.title,a.slug,a.excerpt,a.cover_image,a.published_at,a.views,c.name category_name FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.status='published' ORDER BY a.published_at DESC,a.id DESC LIMIT 5")->fetchAll(),
   'projects'=>$pdo->query("SELECT id,title,slug,summary,cover_image,project_url FROM projects WHERE is_published=1 ORDER BY sort_order,id DESC LIMIT 5")->fetchAll(),
   'portfolio'=>$pdo->query("SELECT title,content,image_url FROM portfolio WHERE is_published=1 ORDER BY sort_order,id LIMIT 6")->fetchAll(),
   'videos'=>$pdo->query("SELECT title,description,youtube_video_id FROM videos WHERE is_published=1 ORDER BY sort_order,id DESC LIMIT 4")->fetchAll()
  ];out(['ok'=>true,'data'=>$data]);
 case 'articles':
  $page=max(1,(int)($_GET['page']??1));$limit=10;$q=trim((string)($_GET['q']??''));$category=(int)($_GET['category']??0);$where=["a.status='published'"];$args=[];
  if($q!==''){$where[]='(a.title LIKE ? OR a.excerpt LIKE ? OR a.body LIKE ?)';$like='%'.mb_substr($q,0,100).'%';array_push($args,$like,$like,$like);}
  if($category>0){$where[]='a.category_id=?';$args[]=$category;}
  $clause=implode(' AND ',$where);$count=$pdo->prepare("SELECT COUNT(*) FROM articles a WHERE $clause");$count->execute($args);$total=(int)$count->fetchColumn();$stmt=$pdo->prepare("SELECT a.id,a.title,a.slug,a.excerpt,a.cover_image,a.published_at,a.views,c.name category_name FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE $clause ORDER BY a.published_at DESC,a.id DESC LIMIT $limit OFFSET ".(($page-1)*$limit));$stmt->execute($args);
  $cats=$pdo->query('SELECT id,name FROM categories WHERE is_active=1 ORDER BY sort_order,name')->fetchAll();out(['ok'=>true,'data'=>['items'=>$stmt->fetchAll(),'categories'=>$cats,'page'=>$page,'total'=>$total,'pages'=>max(1,(int)ceil($total/$limit))]]);
 case 'article':
  $slug=trim((string)($_GET['slug']??''));$s=$pdo->prepare("SELECT a.id,a.title,a.slug,a.excerpt,a.body,a.cover_image,a.source_url,a.published_at,a.views,c.name category_name FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.slug=? AND a.status='published' LIMIT 1");$s->execute([$slug]);$article=$s->fetch();if(!$article)out(['ok'=>false,'message'=>'Məqalə tapılmadı.'],404);
  $pdo->prepare('UPDATE articles SET views=views+1 WHERE id=?')->execute([$article['id']]);out(['ok'=>true,'data'=>$article]);
 case 'services':out(['ok'=>true,'data'=>$pdo->query('SELECT id,title,short_description,description,price,currency,price_label FROM services WHERE is_active=1 ORDER BY sort_order,id DESC')->fetchAll()]);
 case 'projects':out(['ok'=>true,'data'=>$pdo->query('SELECT id,title,summary,description,cover_image,project_url FROM projects WHERE is_published=1 ORDER BY sort_order,id DESC')->fetchAll()]);
 case 'portfolio':out(['ok'=>true,'data'=>$pdo->query('SELECT title,content,image_url FROM portfolio WHERE is_published=1 ORDER BY sort_order,id')->fetchAll()]);
 case 'inquiry':
  if($_SERVER['REQUEST_METHOD']!=='POST')out(['ok'=>false,'message'=>'Method not allowed.'],405);$in=json_decode(file_get_contents('php://input'),true)?:[];$name=trim((string)($in['full_name']??''));$email=trim((string)($in['email']??''));$message=trim((string)($in['message']??''));$phone=trim((string)($in['phone']??''));if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||$message===''||mb_strlen($message)>3000)out(['ok'=>false,'message'=>'Ad, düzgün e-mail və mesaj daxil edin.'],422);$s=$pdo->prepare('INSERT INTO inquiries(full_name,email,phone,message) VALUES(?,?,?,?)');$s->execute([mb_substr($name,0,180),mb_substr($email,0,190),mb_substr($phone,0,60),$message]);out(['ok'=>true,'message'=>'Müraciətiniz qəbul edildi.']);
 default:out(['ok'=>false,'message'=>'Naməlum resurs.'],404);
}
}catch(Throwable $e){out(['ok'=>false,'message'=>'Sorğu emal edilərkən xəta baş verdi.'],500);}
