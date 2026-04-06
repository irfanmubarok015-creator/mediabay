<?php $pageTitle = 'Relasi Kategori → Layanan → Paket'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Relasi <em>Lengkap</em></h1>
    <p>Lihat hubungan Kategori → Layanan → Paket Harga dalam satu tampilan</p>
  </div>
  <div class="admin-page-header-actions">
    <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-ghost btn-sm"><i class="fas fa-th-large"></i> Kategori</a>
    <a href="<?= BASE_URL ?>/admin/services"   class="btn btn-ghost btn-sm"><i class="fas fa-concierge-bell"></i> Layanan</a>
    <a href="<?= BASE_URL ?>/admin/packages"   class="btn btn-primary btn-sm"><i class="fas fa-box"></i> Paket</a>
  </div>
</div>

<!-- Stats strip -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px">
  <?php
  $nCat = count($tree);
  $nSvc = array_sum(array_map(fn($c)=>count($c['services']), $tree));
  $nPkg = array_sum(array_map(fn($c)=>array_sum(array_map(fn($s)=>count($s['packages']),$c['services'])), $tree));
  ?>
  <div class="stat-card" style="text-align:center">
    <div class="stat-icon stat-icon-gold" style="margin:0 auto 10px"><i class="fas fa-th-large"></i></div>
    <div class="stat-value"><?= $nCat ?></div>
    <div class="stat-label">Kategori</div>
  </div>
  <div class="stat-card" style="text-align:center">
    <div class="stat-icon stat-icon-blue" style="margin:0 auto 10px"><i class="fas fa-concierge-bell"></i></div>
    <div class="stat-value"><?= $nSvc ?></div>
    <div class="stat-label">Layanan</div>
  </div>
  <div class="stat-card" style="text-align:center">
    <div class="stat-icon stat-icon-green" style="margin:0 auto 10px"><i class="fas fa-box"></i></div>
    <div class="stat-value"><?= $nPkg ?></div>
    <div class="stat-label">Paket</div>
  </div>
</div>

<?php if (empty($tree)): ?>
<div class="admin-card"><div class="admin-card-body">
  <div class="empty-state">
    <i class="fas fa-sitemap"></i>
    <h3>Belum Ada Data</h3>
    <p>Mulai dengan menambah kategori, layanan, dan paket</p>
  </div>
</div></div>
<?php else: ?>

<div class="relation-tree">
  <?php
  $iconMap = ['photography'=>'camera','videography'=>'film','live-streaming'=>'tower-broadcast'];
  foreach($tree as $cat):
    $icon = $iconMap[$cat['slug']] ?? ($cat['icon'] ?? 'circle');
  ?>
  <div class="relation-category">
    <div class="relation-category-header">
      <div class="relation-cat-icon"><i class="fas fa-<?= $icon ?>"></i></div>
      <div>
        <div class="relation-cat-title"><?= htmlspecialchars($cat['name']) ?></div>
        <div class="relation-cat-meta">
          <?= count($cat['services']) ?> layanan
          · <?= array_sum(array_map(fn($s)=>count($s['packages']), $cat['services'])) ?> paket total
          · <span class="badge <?= $cat['is_active']?'badge-approved':'badge-rejected' ?>" style="font-size:0.65rem"><?= $cat['is_active']?'Aktif':'Nonaktif' ?></span>
        </div>
      </div>
      <div style="margin-left:auto;display:flex;gap:8px;align-items:center">
        <a href="<?= BASE_URL ?>/layanan/<?= htmlspecialchars($cat['slug']) ?>" target="_blank"
           class="btn btn-ghost btn-sm" title="Lihat di website" onclick="event.stopPropagation()">
          <i class="fas fa-external-link-alt"></i>
        </a>
        <i class="fas fa-chevron-down relation-toggle"></i>
      </div>
    </div>

    <div class="relation-services">
      <?php if (empty($cat['services'])): ?>
      <div style="padding:12px 8px;font-size:0.82rem;color:var(--text-muted)">
        <i class="fas fa-info-circle"></i> Belum ada layanan di kategori ini.
        <a href="<?= BASE_URL ?>/admin/services" style="color:var(--gold)">Tambah Layanan →</a>
      </div>
      <?php else: foreach($cat['services'] as $svc): ?>

      <div class="relation-service">
        <div class="relation-service-header">
          <div class="relation-svc-dot"></div>
          <div style="flex:1">
            <div class="relation-svc-name"><?= htmlspecialchars($svc['name']) ?></div>
            <div style="font-size:0.72rem;color:var(--text-muted)"><?= count($svc['packages']) ?> paket tersedia</div>
          </div>
          <span class="badge <?= $svc['is_active']?'badge-approved':'badge-rejected' ?>" style="font-size:0.65rem;margin-right:8px">
            <?= $svc['is_active']?'Aktif':'Nonaktif' ?>
          </span>
          <i class="fas fa-chevron-down relation-svc-toggle"></i>
        </div>

        <div class="relation-packages">
          <?php if (empty($svc['packages'])): ?>
          <div style="padding:8px;font-size:0.8rem;color:var(--text-muted)">
            <i class="fas fa-box-open"></i> Belum ada paket.
            <a href="<?= BASE_URL ?>/admin/packages" style="color:var(--gold)">Tambah Paket →</a>
          </div>
          <?php else: foreach($svc['packages'] as $pi => $pkg): ?>
          <div class="relation-package <?= $pi===1?'featured':'' ?>">
            <div>
              <div class="relation-pkg-name"><?= htmlspecialchars($pkg['name']) ?></div>
              <div class="relation-pkg-dp">
                DP <?= $pkg['dp_percentage'] ?>% · <?= $pkg['duration_hours'] ?>h
                <?php if (!$pkg['is_active']): ?><span class="badge badge-rejected" style="font-size:0.6rem;margin-left:4px">Nonaktif</span><?php endif; ?>
              </div>
            </div>
            <div style="text-align:right">
              <div class="relation-pkg-price">Rp <?= number_format($pkg['price'],0,',','.') ?></div>
              <div style="font-size:0.72rem;color:var(--text-muted)">
                DP: Rp <?= number_format($pkg['price']*$pkg['dp_percentage']/100,0,',','.') ?>
              </div>
            </div>
          </div>
          <?php endforeach; endif; ?>

          <div style="padding:8px 4px 4px;display:flex;gap:8px">
            <a href="<?= BASE_URL ?>/admin/packages" class="btn btn-ghost btn-sm" style="font-size:0.76rem;padding:6px 12px">
              <i class="fas fa-plus"></i> Tambah Paket
            </a>
            <a href="<?= BASE_URL ?>/layanan/<?= htmlspecialchars($cat['slug']) ?>" target="_blank"
               class="btn btn-ghost btn-sm" style="font-size:0.76rem;padding:6px 12px">
              <i class="fas fa-eye"></i> Preview
            </a>
          </div>
        </div>
      </div>

      <?php endforeach; endif; ?>

      <div style="padding:10px 8px 4px;display:flex;gap:8px">
        <a href="<?= BASE_URL ?>/admin/services" class="btn btn-ghost btn-sm" style="font-size:0.76rem;padding:7px 14px">
          <i class="fas fa-plus"></i> Tambah Layanan
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php endif; ?>

<style>
.relation-category.open .relation-services { display: block; animation: fadeSlide 0.25s ease; }
@keyframes fadeSlide { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:none} }
</style>
