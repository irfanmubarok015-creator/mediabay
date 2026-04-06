<?php $pageTitle = 'Portfolio & Informasi'; ?>

<div class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/">Home</a><span>/</span><span>Informasi</span>
    </div>
    <h1>Portfolio & <em>Informasi</em></h1>
    <p>Karya terbaik dan informasi terbaru dari Mediabay</p>
  </div>
</div>

<section class="section-sm">
  <div class="container">
    <?php if ($items): ?>
    <div class="news-grid">
      <?php foreach($items as $i => $item): ?>
      <article class="news-card reveal delay-<?= ($i%3)+1 ?>">
        <div class="news-card-img">
          <?php if ($item['image']): ?>
          <img data-src="<?= BASE_URL ?>/uploads/portfolio/<?= htmlspecialchars($item['image']) ?>"
               src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="
               alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
          <?php else: ?>
          <div class="news-card-img-placeholder"><i class="fas fa-image"></i></div>
          <?php endif; ?>
        </div>
        <div class="news-card-body">
          <span class="news-card-tag"><?= ucfirst($item['category']) ?></span>
          <h3><a href="<?= BASE_URL ?>/informasi/<?= $item['slug'] ?>"><?= htmlspecialchars($item['title']) ?></a></h3>
          <p><?= htmlspecialchars(substr($item['excerpt'] ?? $item['content'] ?? '', 0, 120)) ?>...</p>
        </div>
        <div class="news-card-footer">
          <span class="news-card-date">
            <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($item['published_at'])) ?>
          </span>
          <a href="<?= BASE_URL ?>/informasi/<?= $item['slug'] ?>" class="news-card-read">
            Baca <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
      <i class="fas fa-newspaper"></i>
      <h3>Belum Ada Artikel</h3>
      <p>Konten sedang dalam persiapan. Kunjungi kembali nanti.</p>
    </div>
    <?php endif; ?>
  </div>
</section>
