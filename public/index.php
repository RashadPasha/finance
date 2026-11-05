<?php
declare(strict_types=1);
$configPath = dirname(__DIR__) . '/config/config.php';
$config = is_file($configPath) ? require $configPath : require dirname(__DIR__) . '/config/config.example.php';
date_default_timezone_set($config['app']['timezone'] ?? 'Asia/Baku');
$articles = $services = $projects = [];
try {
    $db = $config['database'];
    $pdo = new PDO(
        "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",
        $db['username'], $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $articles = $pdo->query("SELECT title, slug, excerpt, cover_image, published_at FROM articles WHERE status='published' ORDER BY published_at DESC LIMIT 3")->fetchAll();
    $services = $pdo->query("SELECT title, short_description, price, currency, price_label FROM services WHERE is_active=1 ORDER BY sort_order LIMIT 3")->fetchAll();
    $projects = $pdo->query("SELECT title, slug, summary, cover_image FROM projects WHERE is_published=1 ORDER BY sort_order LIMIT 3")->fetchAll();
} catch (Throwable $e) { /* The public shell remains viewable until DB setup is complete. */ }
function h(?string $v): string { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="az">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#102b4e">
<title>RashadFinance — Maliyyə, Analitika və Konsaltinq</title>
<meta name="description" content="Maliyyə idarəetməsi, restoran maliyyəsi, Cost Control və biznes konsaltinqi.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/site.css"><link rel="stylesheet" href="assets/css/chat.css">
</head>
<body>
<header class="site-header"><a class="brand" href="#"><span class="brand-mark">R</span><span>RASHAD<span class="brand-light">FINANCE</span></span></a>
<nav><a href="#about">Haqqımda</a><a href="#services">Xidmətlər</a><a href="#projects">Layihələr</a><a href="#insights">Bloq</a><a href="#contact">Əlaqə</a></nav>
<a class="header-cta" href="#contact">Konsultasiya al <span>↗</span></a></header>
<main>
<section class="hero">
<div class="hero-copy"><div class="eyebrow"><i></i> FINANCE · STRATEGY · CONTROL</div>
<h1>Rəqəmləri anlayın.<br><em>Biznesinizi</em> idarə edin.</h1>
<p class="hero-lead">Maliyyə idarəetməsi, biznes analitikası və restoran konsaltinqi ilə daha dəqiq qərarlar verin.</p>
<div class="hero-actions"><a class="btn btn-primary" href="#services">Xidmətləri kəşf et <span>↗</span></a><a class="text-link" href="#insights">Maliyyə bloqu <span>→</span></a></div>
<div class="hero-proof"><div class="proof-avatars"><b>RF</b><b>₼</b><b>✓</b></div><span>Analitik yanaşma. Ölçülə bilən nəticə.</span></div></div>
<div class="hero-art"><div class="art-glow"></div><div class="finance-card"><div class="card-top"><span>BUSINESS PERFORMANCE</span><span class="live-dot">● LIVE</span></div><div class="card-label">Maliyyə göstəriciləri</div><div class="card-number">360° <small>ANALİTİKA</small></div><div class="chart"><i style="height:32%"></i><i style="height:46%"></i><i style="height:39%"></i><i style="height:62%"></i><i style="height:55%"></i><i style="height:78%"></i><i style="height:94%"></i><i style="height:72%"></i><i style="height:100%"></i></div><div class="card-bottom"><span>STRATEGİYA</span><span>PERFORMANS</span><span>NƏZARƏT</span></div></div><div class="floating-note"><span class="note-icon">↗</span><span><b>Data-driven</b><small>Qərarvermə mədəniyyəti</small></span></div><div class="hero-index">01 <span>/ 04</span></div></div>
</section>
<section class="trust-strip"><span>MALİYYƏ İDARƏETMƏSİ</span><i></i><span>COST CONTROL</span><i></i><span>BİZNES ANALİTİKASI</span><i></i><span>KONSALTİNQ</span></section>
<section class="intro section" id="about"><div class="section-kicker">01 / YANAŞMAM</div><div class="intro-grid"><h2>Sadəcə hesabat deyil.<br><em>Strateji baxış.</em></h2><div><p>Biznesin real vəziyyətini rəqəmlərlə görmək, riskləri vaxtında müəyyən etmək və dayanıqlı inkişaf üçün düzgün qərarlar vermək.</p><a class="text-link dark-link" href="#contact">Birlikdə işləyək <span>↗</span></a></div></div></section>
<section class="services section" id="services"><div class="section-heading"><div><div class="section-kicker">02 / XİDMƏTLƏR</div><h2>Biznesiniz üçün<br><em>dəqiq həllər.</em></h2></div><p>Fərdi yanaşma, şəffaf metodologiya və ölçülə bilən nəticələr.</p></div><div class="service-grid">
<?php if ($services): foreach ($services as $i=>$s): ?><article class="service-card"><span class="service-no">0<?= $i+1 ?></span><h3><?=h($s['title'])?></h3><p><?=h($s['short_description'])?></p><div class="service-price"><?=h($s['price_label'] ?: ($s['price'] !== null ? number_format((float)$s['price'],2).' '.($s['currency'] ?? 'AZN') : 'Fərdi təklif'))?></div><a href="#contact" class="arrow-link">Müraciət et ↗</a></article><?php endforeach; else: ?>
<article class="service-card"><span class="service-no">01</span><h3>Maliyyə konsaltinqi</h3><p>Maliyyə hesabatları, büdcələmə və idarəetmə qərarları.</p><div class="service-price">Fərdi təklif</div><a href="#contact" class="arrow-link">Müraciət et ↗</a></article>
<article class="service-card featured"><span class="service-no">02</span><h3>Restaurant Cost Control</h3><p>Food cost, anbar, maya dəyəri və gəlirlilik təhlili.</p><div class="service-price">Fərdi təklif</div><a href="#contact" class="arrow-link">Müraciət et ↗</a></article>
<article class="service-card"><span class="service-no">03</span><h3>Biznes analitikası</h3><p>KPI dashboard, satış analizi və performans ölçümü.</p><div class="service-price">Fərdi təklif</div><a href="#contact" class="arrow-link">Müraciət et ↗</a></article><?php endif; ?></div></section>
<section class="projects section" id="projects"><div class="section-kicker">03 / LAYİHƏLƏR</div><div class="section-heading"><h2>İdeyadan <em>nəticəyə.</em></h2><p>Həyata keçirilən işlər və inkişaf etdirdiyimiz həllər.</p></div><div class="project-grid"><?php if($projects): foreach($projects as $p): ?><article class="project-card"><div class="project-visual"><span>CASE STUDY</span><b><?=h(mb_strtoupper(mb_substr($p['title'],0,1)))?></b></div><div><h3><?=h($p['title'])?></h3><p><?=h($p['summary'])?></p></div></article><?php endforeach; else: ?><article class="project-card"><div class="project-visual"><span>FINANCIAL SYSTEM</span><b>01</b></div><div><h3>Financial Intelligence</h3><p>Maliyyə məlumatlarını qərarvermə alətinə çevirən yanaşma.</p></div></article><article class="project-card"><div class="project-visual alt"><span>HOSPITALITY</span><b>02</b></div><div><h3>Restaurant Performance</h3><p>Restoranlar üçün maliyyə nəzarəti və analitika.</p></div></article><?php endif; ?></div></section>
<section class="insights section" id="insights"><div class="section-heading"><div><div class="section-kicker">04 / BİLİK VƏ ANALİTİKA</div><h2>Maliyyə <em>baxışı.</em></h2></div><a class="text-link dark-link" href="blog.php">Bütün məqalələr <span>↗</span></a></div><div class="article-grid"><?php if($articles): foreach($articles as $a): ?><article class="article-card"><span class="article-date"><?=h($a['published_at'])?></span><h3><?=h($a['title'])?></h3><p><?=h($a['excerpt'])?></p><a href="article.php?slug=<?=rawurlencode($a['slug'])?>" class="arrow-link">Oxumağa davam et ↗</a></article><?php endforeach; else: ?><article class="article-card"><span class="article-date">FINANCE INSIGHT / 01</span><h3>Food Cost: restoran gəlirliliyinin əsas göstəricisi</h3><p>Restoran biznesində maya dəyərini ölçmək və idarə etmək üçün əsas prinsiplər.</p><a href="#contact" class="arrow-link">Tezliklə dərc olunacaq ↗</a></article><article class="article-card"><span class="article-date">FINANCE INSIGHT / 02</span><h3>Cash Flow və mənfəət arasındakı fərq</h3><p>Şirkətin maliyyə sağlamlığını düzgün qiymətləndirmək üçün vacib anlayışlar.</p><a href="#contact" class="arrow-link">Tezliklə dərc olunacaq ↗</a></article><article class="article-card"><span class="article-date">FINANCE INSIGHT / 03</span><h3>ABC analizi ilə anbar nəzarəti</h3><p>Ən vacib məhsulları müəyyən etməklə inventar idarəetməsini gücləndirin.</p><a href="#contact" class="arrow-link">Tezliklə dərc olunacaq ↗</a></article><?php endif; ?></div></section>
<section class="contact section" id="contact"><div><div class="section-kicker">05 / ƏLAQƏ</div><h2>Biznesinizin növbəti<br><em>addımını planlayaq.</em></h2><p>Müraciətinizi göndərin, sizinlə əlaqə saxlayaq.</p></div><form class="contact-form" method="post" action="api/inquiry.php"><label>Ad və soyad<input name="full_name" required maxlength="180" placeholder="Adınız"></label><label>E-mail<input type="email" name="email" required maxlength="190" placeholder="email@example.com"></label><label>Telefon<input name="phone" maxlength="60" placeholder="+994"></label><label>Mesaj<textarea name="message" required maxlength="3000" placeholder="Sizə necə kömək edə bilərik?"></textarea></label><button class="btn btn-primary" type="submit">Müraciəti göndər <span>↗</span></button></form></section>
</main>
<footer><a class="brand footer-brand" href="#"><span class="brand-mark">R</span><span>RASHAD<span class="brand-light">FINANCE</span></span></a><span>© <?=date('Y')?> RashadFinance. Bütün hüquqlar qorunur.</span><a href="admin/">Admin panel ↗</a></footer>
<div id="rf-chat"><button class="chat-toggle" type="button" aria-label="RashadFinance AI köməkçisi" aria-expanded="false">✦</button><section class="chat-panel" hidden><div class="chat-head"><b>RashadFinance AI</b><small>Məqalələr və xidmətlər haqqında sual verin</small></div><div class="chat-messages"><div class="chat-bubble assistant">Salam! RashadFinance haqqında və ümumi maliyyə mövzularında suallarınızı cavablandıra bilərəm.</div></div><form><input type="text" maxlength="1500" placeholder="Sualınızı yazın..." aria-label="Mesajınız" required><button type="submit">Göndər</button></form></section></div><script src="assets/js/site.js"></script><script src="assets/js/chat.js"></script>
</body></html>
