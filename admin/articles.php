<?php
declare(strict_types=1);
require __DIR__.'/bootstrap.php';
$error='';$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$action=(string)($_POST['action']??'');
 try{
  if($action==='save'){
   $id=(int)($_POST['id']??0);$title=trim((string)($_POST['title']??''));$slug=trim((string)($_POST['slug']??''));$body=trim((string)($_POST['body']??''));
   if($title===''||$body==='')throw new RuntimeException('Başlıq və məqalə mətni mütləqdir.');
   if($slug==='')$slug=slugify($title);else $slug=slugify($slug);
   $category=(int)($_POST['category_id']??0);$category=$category>0?$category:null;
   $excerpt=trim((string)($_POST['excerpt']??''));$image=trim((string)($_POST['cover_image']??''));$source=trim((string)($_POST['source_url']??''));$status=in_array($_POST['status']??'draft',['draft','published','archived'],true)?$_POST['status']:'draft';
   foreach(['cover_image'=>$image,'source_url'=>$source] as $field=>$url){if($url!==''&&!filter_var($url,FILTER_VALIDATE_URL))throw new RuntimeException('Şəkil və mənbə linki düzgün URL olmalıdır.');}
   if($id>0){$q=$pdo->prepare("UPDATE articles SET category_id=?,title=?,slug=?,excerpt=?,body=?,cover_image=?,source_url=?,status=?,published_at=CASE WHEN ?='published' THEN COALESCE(published_at,NOW()) ELSE published_at END WHERE id=?");$q->execute([$category,$title,$slug,$excerpt,$body,$image?:null,$source?:null,$status,$status,$id]);}
   else{$q=$pdo->prepare("INSERT INTO articles(category_id,title,slug,excerpt,body,cover_image,source_url,status,published_at) VALUES(?,?,?,?,?,?,?,?,IF(?='published',NOW(),NULL))");$q->execute([$category,$title,$slug,$excerpt,$body,$image?:null,$source?:null,$status,$status]);}
   $success='Məqalə yadda saxlanıldı.';
  }elseif($action==='delete'){$q=$pdo->prepare('DELETE FROM articles WHERE id=?');$q->execute([(int)($_POST['id']??0)]);$success='Məqalə silindi.';}
 }catch(Throwable $ex){$error=$ex instanceof PDOException?'Slug təkrarlanır və ya məlumat bazası xətası baş verdi.':$ex->getMessage();}
}
$edit=null;if(isset($_GET['edit'])){$q=$pdo->prepare('SELECT * FROM articles WHERE id=?');$q->execute([(int)$_GET['edit']]);$edit=$q->fetch()?:null;}
$categories=$pdo->query('SELECT id,name,parent_id FROM categories WHERE is_active=1 ORDER BY parent_id,sort_order,name')->fetchAll();
$articles=$pdo->query('SELECT a.id,a.title,a.slug,a.status,a.views,a.created_at,c.name AS category_name FROM articles a LEFT JOIN categories c ON c.id=a.category_id ORDER BY a.created_at DESC LIMIT 100')->fetchAll();
admin_header('Məqalələr','articles');
?>
<?php if($error):?><div class="notice error"><?=e($error)?></div><?php endif;?><?php if($success):?><div class="notice success"><?=e($success)?></div><?php endif;?>
<div class="content-grid article-admin-grid"><section class="panel"><div class="panel-head"><div><small><?= $edit?'REDAKTƏ':'YENİ MƏQALƏ' ?></small><h2><?= $edit?'Məqaləni redaktə et':'Məqalə əlavə et' ?></h2></div></div>
<form class="admin-form" method="post"><?=csrf_field()?><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=e((string)($edit['id']??0))?>">
<label>Başlıq<input name="title" required maxlength="220" value="<?=e($edit['title']??'')?>"></label><label>Slug<input name="slug" maxlength="240" value="<?=e($edit['slug']??'')?>" placeholder="avtomatik yaradılır"></label>
<label>Kateqoriya<select name="category_id"><option value="0">Kateqoriya seçin</option><?php foreach($categories as $c):?><option value="<?=$c['id']?>" <?=((int)($edit['category_id']??0)===(int)$c['id'])?'selected':''?>><?=!empty($c['parent_id'])?'— ':''?><?=e($c['name'])?></option><?php endforeach;?></select></label>
<label>Qısa təsvir<textarea name="excerpt" rows="3" maxlength="2000"><?=e($edit['excerpt']??'')?></textarea></label><label>Məqalə mətni<textarea name="body" rows="12" required><?=e($edit['body']??'')?></textarea></label>
<label>Cover şəkil URL<input type="url" name="cover_image" value="<?=e($edit['cover_image']??'')?>" placeholder="https://..."></label><label>Mənbə linki<input type="url" name="source_url" value="<?=e($edit['source_url']??'')?>" placeholder="https://..."></label>
<label>Status<select name="status"><option value="draft" <?=($edit['status']??'draft')==='draft'?'selected':''?>>Qaralama</option><option value="published" <?=($edit['status']??'')==='published'?'selected':''?>>Dərc edilsin</option><option value="archived" <?=($edit['status']??'')==='archived'?'selected':''?>>Arxiv</option></select></label>
<div class="form-actions"><button class="btn-admin" type="submit">Yadda saxla</button><?php if($edit):?><a class="btn-secondary" href="articles.php">Ləğv et</a><?php endif;?></div></form></section>
<section class="panel"><div class="panel-head"><div><small>SON QEYDLƏR</small><h2>Məqalə siyahısı</h2></div><span class="count"><?=count($articles)?> / 100</span></div><div class="table-wrap"><table><thead><tr><th>Məqalə</th><th>Kateqoriya</th><th>Status</th><th>Baxış</th><th>Əməliyyat</th></tr></thead><tbody><?php foreach($articles as $a):?><tr><td><b><?=e($a['title'])?></b><small class="subline"><?=e($a['slug'])?></small></td><td><?=e($a['category_name']??'—')?></td><td><span class="status"><?=['draft'=>'Qaralama','published'=>'Dərc edilib','archived'=>'Arxiv'][$a['status']]?></span></td><td><?=$a['views']?></td><td class="actions"><a href="?edit=<?=$a['id']?>">Redaktə</a><form method="post" onsubmit="return confirm('Məqalə silinsin?')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$a['id']?>"><button class="link-danger">Sil</button></form></td></tr><?php endforeach;?><?php if(!$articles):?><tr><td colspan="5">Hələ məqalə yoxdur.</td></tr><?php endif;?></tbody></table></div></section></div>
<?php admin_footer(); ?>
