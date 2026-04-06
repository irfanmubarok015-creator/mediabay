<?php $pageTitle = htmlspecialchars($item['title']); ?>

<div class="page-hero" style="padding-bottom:40px">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/">Home</a><span>/</span>
      <a href="<?= BASE_URL ?>/informasi">Informasi</a><span>/</span>
      <span><?= htmlspecialchars(substr($item['title'], 0, 40)) ?>...</span>
    </div>
    <span class="news-card-tag" style="margin-bottom:16px;display:inline-block"><?= ucfirst($item['category']) ?></span>
    <h1 style="max-width:800px;margin:0 auto 12px"><?= htmlspecialchars($item['title']) ?></h1>
    <p style="color:var(--text-muted);font-size:0.9rem">
      <i class="fas fa-calendar" style="margin-right:6px"></i>
      <?= date('l, d F Y', strtotime($item['published_at'])) ?>
    </p>
  </div>
</div>

<section class="section-sm" style="padding-top:0">
  <div class="container-sm">

    <?php if ($item['image']): ?>
    <div style="border-radius:var(--radius-lg);overflow:hidden;margin-bottom:40px;aspect-ratio:16/9">
      <img src="<?= BASE_URL ?>/uploads/portfolio/<?= htmlspecialchars($item['image']) ?>"
           alt="<?= htmlspecialchars($item['title']) ?>"
           style="width:100%;height:100%;object-fit:cover">
    </div>
    <?php endif; ?>

    <?php if ($item['excerpt']): ?>
    <p style="font-size:1.1rem;color:var(--text-soft);line-height:1.8;margin-bottom:32px;padding:20px 24px;background:rgba(201,168,76,0.06);border-left:3px solid var(--gold);border-radius:0 var(--radius) var(--radius) 0">
      <?= htmlspecialchars($item['excerpt']) ?>
    </p>
    <?php endif; ?>

    <div style="font-size:1rem;line-height:1.9;color:var(--text-soft)">
      <?= nl2br(htmlspecialchars($item['content'] ?? '')) ?>
    </div>

    <div class="divider"></div>

    <div style="display:flex;gap:12px;align-items:center;justify-content:space-between;flex-wrap:wrap">
      <a href="<?= BASE_URL ?>/informasi" class="btn btn-ghost btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <a href="<?= BASE_URL ?>/booking" class="btn btn-primary btn-sm">
        <i class="fas fa-calendar-plus"></i> Booking Layanan Ini
      </a>
    </div>
  </div>
</section>
