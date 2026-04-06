<?php $pageTitle = 'Layanan Kami'; ?>

<div class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/">Home</a><span>/</span><span>Layanan</span>
    </div>
    <h1>Layanan <em>Kami</em></h1>
    <p>Photography, Videography, dan Live Streaming profesional untuk setiap momen</p>
  </div>
</div>

<section class="section-sm">
  <div class="container">
    <!-- Category Tabs -->
    <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-bottom:48px">
      <a href="<?= BASE_URL ?>/layanan" class="btn btn-sm <?= !isset($activeCategory) ? 'btn-primary' : 'btn-ghost' ?>">Semua</a>
      <?php foreach($categories as $cat): ?>
      <a href="<?= BASE_URL ?>/layanan/<?= $cat['slug'] ?>" class="btn btn-sm btn-ghost">
        <i class="fas fa-<?= $cat['icon'] ?>"></i> <?= htmlspecialchars($cat['name']) ?>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Services Grid -->
    <div class="services-grid" style="grid-template-columns:repeat(auto-fill,minmax(360px,1fr))">
      <?php $bgClasses = ['bg-photo','bg-video','bg-live']; ?>
      <?php foreach($services as $i => $svc): ?>
      <a href="<?= BASE_URL ?>/layanan/<?= $svc['category_slug'] ?>" class="service-card reveal delay-<?= ($i%3)+1 ?>" style="aspect-ratio:4/3">
        <div class="service-card-bg <?= $bgClasses[$i % 3] ?>"
          <?= $svc['image'] ? 'style="background-image:url('.BASE_URL.'/uploads/services/'.htmlspecialchars($svc['image']).')"' : '' ?>>
        </div>
        <div class="service-card-overlay"></div>
        <div class="service-card-body">
          <div class="service-card-icon">
            <i class="fas fa-camera"></i>
          </div>
          <div style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-bottom:6px;letter-spacing:1px;text-transform:uppercase"><?= htmlspecialchars($svc['category_name']) ?></div>
          <h3><?= htmlspecialchars($svc['name']) ?></h3>
          <p><?= htmlspecialchars(substr($svc['description'] ?? '', 0, 80)) ?>...</p>
          <div class="service-card-cta">Lihat Paket <i class="fas fa-arrow-right"></i></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($services)): ?>
    <div class="empty-state">
      <i class="fas fa-search"></i>
      <h3>Tidak Ada Layanan</h3>
      <p>Belum ada layanan yang tersedia saat ini</p>
    </div>
    <?php endif; ?>

    <div style="text-align:center;margin-top:60px" class="reveal">
      <p style="color:var(--text-muted);margin-bottom:20px">Tidak yakin harus pilih yang mana?</p>
      <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-lg">
        <i class="fab fa-whatsapp"></i> Konsultasi Gratis via WhatsApp
      </a>
    </div>
  </div>
</section>
