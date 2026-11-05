<?php
declare(strict_types=1);
require __DIR__.'/bootstrap.php';
$error='';$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$action=(string)($_POST['action']??'');
 try{
  if($action==='save'){
   $id=(int)($_POST['id']??0);$name=trim((string)($_POST['name']??''));$slug=trim((string)($_POST['slug']??''));$parent=(int)($_POST['parent_id']??0);$order=(int)($_POST['sort_order']??0);
   if($name==='') throw new RuntimeException('Kateqoriya adı mütləqdir.');
   if($slug==='')$slug=slugify($name);else $slug=slugify($slug);
   if($id>0 && $parent===$id) throw new RuntimeException('Kateqoriya özünün alt-kateqoriyası ola bilməz.');
   $parentValue=$parent>0?$parent:null;
   if($id>0){$q=$pdo->prepare('UPDATE categories SET name=?,slug=?,parent_id=?,sort_order=?,is_active=? WHERE id=?');$q->execute([$name,$slug,$parentValue,$order,isset($_POST['is_active'])?1:0,$id]);}
   else{$q=$pdo->prepare('INSERT INTO categories(name,slug,parent_id,sort_order,is_active) VALUES(?,?,?,?,?)');$q->execute([$name,$slug,$parentValue,$order,isset($_POST['is_active'])?1:0]);}
   $success='Kateqoriya yadda saxlanıldı.';
  }elseif($action==='delete'){
   $id=(int)($_POST['id']??0);$q=$pdo->prepare('DELETE FROM categories WHERE id=?');$q->execute([$id]);$success='Kateqoriya silindi.';
  }
 }catch(Throwable $ex){$error=$ex instanceof PDOException?'Bu slug artıq istifadə olunur və ya məlumat bazası xətası baş verdi.':$ex->getMessage();}
}
$edit=null;if(isset($_GET['edit'])){$q=$pdo->prepare('SELECT * FROM categories WHERE id=?');$q->execute([(int)$_GET['edit']]);$edit=$q->fetch()?:null;}
$categories=$pdo->query('SELECT c.*,p.name AS parent_name,(SELECT COUNT(*) FROM articles a WHERE a.category_id=c.id) AS article_count FROM categories c LEFT JOIN categories p ON p.id=c.parent_id ORDER BY COALESCE(c.parent_id,c.id),c.sort_order,c.name')->fetchAll();
$parents=$pdo->query('SELECT id,name FROM categories WHERE parent_id IS NULL ORDER BY sort_order,name')->fetchAll();
admin_header('Kateqoriyalar','categories');
?>
<?php if($error):?><div class="notice error"><?=e($error)?></div><?php endif;?><?php if($success):?><div class="notice success"><?=e($success)?></div><?php endif;?>
<div class="content-grid"><section class="panel"><div class="panel-head"><div><small><?= $edit?'REDAKTƏ':'YENİ QEYD' ?></small><h2><?= $edit?'Kateqoriyanı redaktə et':'Kateqoriya əlavə et' ?></h2></div></div>
<form class="admin-form" method="post"><?=csrf_field()?><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=e((string)($edit['id']??0))?>">
<label>Kateqoriya adı<input name="name" required maxlength="160" value="<?=e($edit['name']??'')?>"></label><label>Slug <small>URL üçün unikal ad; boş saxlasanız avtomatik yaradılır.</small><input name="slug" maxlength="180" value="<?=e($edit['slug']??'')?>"></label>
<label>Üst kateqoriya<select name="parent_id"><option value="0">Əsas kateqoriya</option><?php foreach($parents as $p):if((int)$p['id']===(int)($edit['id']??0))continue;?><option value="<?=$p['id']?>" <?=((int)($edit['parent_id']??0)===(int)$p['id'])?'selected':''?>><?=e($p['name'])?></option><?php endforeach;?></select></label>
<label>Sıralama<input type="number" name="sort_order" value="<?=e((string)($edit['sort_order']??0))?>"></label><label class="check-label"><input type="checkbox" name="is_active" value="1" <?=(!$edit||!empty($edit['is_active']))?'checked':''?>> Aktiv</label>
<div class="form-actions"><button class="btn-admin" type="submit">Yadda saxla</button><?php if($edit):?><a class="btn-secondary" href="categories.php">Ləğv et</a><?php endif;?></div></form></section>
<section class="panel"><div class="panel-head"><div><small>STRUKTUR</small><h2>Kateqoriya siyahısı</h2></div><span class="count"><?=count($categories)?> qeyd</span></div><div class="table-wrap"><table><thead><tr><th>Ad</th><th>Slug</th><th>Məqalə</th><th>Status</th><th>Əməliyyat</th></tr></thead><tbody><?php foreach($categories as $c):?><tr><td><?=!empty($c['parent_id'])?'↳ ':''?><?=e($c['name'])?><small class="subline"><?=e($c['parent_name']??'Əsas kateqoriya')?></small></td><td><?=e($c['slug'])?></td><td><?=$c['article_count']?></td><td><?=!empty($c['is_active'])?'Aktiv':'Passiv'?></td><td class="actions"><a href="?edit=<?=$c['id']?>">Redaktə</a><form method="post" onsubmit="return confirm('Kateqoriya silinsin?')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$c['id']?>"><button class="link-danger">Sil</button></form></td></tr><?php endforeach;?><?php if(!$categories):?><tr><td colspan="5">Kateqoriya yoxdur.</td></tr><?php endif;?></tbody></table></div></section></div>
<?php admin_footer(); ?>
