<?php $pageTitle = htmlspecialchars($category['name']); ?>

<div class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/">Home</a><span>/</span>
      <a href="<?= BASE_URL ?>/layanan">Layanan</a><span>/</span>
      <span><?= htmlspecialchars($category['name']) ?></span>
    </div>
    <h1><?= htmlspecialchars($category['name']) ?></h1>
    <p><?= htmlspecialchars($category['description'] ?? '') ?></p>
  </div>
</div>

<section class="section-sm">
  <div class="container">

    <?php foreach($services as $svc): ?>
    <?php
      // Get packages for this service
      $pkgs = (new PackageModel())->getByService($svc['id']);
    ?>
    <div style="margin-bottom:64px">
      <div style="margin-bottom:32px" class="reveal">
        <div class="section-tag"><?= htmlspecialchars($category['name']) ?></div>
        <h2 style="margin-top:12px"><?= htmlspecialchars($svc['name']) ?></h2>
        <p style="color:var(--text-soft);margin-top:8px;max-width:600px"><?= htmlspecialchars($svc['description'] ?? '') ?></p>
      </div>

      <?php if ($pkgs): ?>
      <div class="packages-grid">
        <?php foreach($pkgs as $pi => $pkg):
          $features = json_decode($pkg['features'] ?? '[]', true);
          $dp = $pkg['price'] * ($pkg['dp_percentage'] / 100);
        ?>
        <div class="package-card reveal delay-<?= $pi+1 ?> <?= $pi === 1 ? 'featured' : '' ?>">
          <div class="package-name"><?= htmlspecialchars($pkg['name']) ?></div>
          <div class="package-price">Rp <?= number_format($pkg['price'], 0, ',', '.') ?><span> / event</span></div>
          <div class="package-dp">DP <?= $pkg['dp_percentage'] ?>% = Rp <?= number_format($dp, 0, ',', '.') ?></div>

          <?php if ($features): ?>
          <div class="package-features feature-list">
            <?php foreach($features as $feat): ?>
            <div class="feature-item">
              <i class="fas fa-check-circle"></i>
              <?= htmlspecialchars($feat) ?>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <div style="margin-top:4px;padding-top:20px;border-top:1px solid var(--border-soft);display:flex;align-items:center;justify-content:space-between">
            <span style="font-size:0.82rem;color:var(--text-muted)">
              <i class="fas fa-clock" style="color:var(--gold);margin-right:4px"></i>
              <?= $pkg['duration_hours'] ?> jam
            </span>
            <a href="<?= BASE_URL ?>/booking?package_id=<?= $pkg['id'] ?>" class="btn btn-primary btn-sm">
              <i class="fas fa-calendar-plus"></i> Booking
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div style="background:var(--dark-3);border:1px solid var(--border-soft);border-radius:var(--radius-lg);padding:32px;text-align:center;color:var(--text-muted)">
        <i class="fas fa-box-open" style="font-size:2rem;margin-bottom:12px"></i>
        <p>Paket belum tersedia. Hubungi kami untuk penawaran khusus.</p>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <!-- CTA -->
    <div style="text-align:center;padding:60px 0 20px" class="reveal">
      <h3 style="margin-bottom:12px">Butuh Paket <em>Custom</em>?</h3>
      <p style="color:var(--text-soft);margin-bottom:24px">Kami siap membuat paket sesuai kebutuhan dan budget Anda</p>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="<?= BASE_URL ?>/booking" class="btn btn-primary">
          <i class="fas fa-calendar-plus"></i> Buat Booking
        </a>
        <a href="<?= BASE_URL ?>/contact" class="btn btn-outline">
          <i class="fab fa-whatsapp"></i> Konsultasi Gratis
        </a>
      </div>
    </div>
  </div>
</section>
