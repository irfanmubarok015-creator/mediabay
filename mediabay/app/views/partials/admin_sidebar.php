<?php
$r = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$b = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
$p = $b ? substr($r, strlen($b)) : $r;
$p = '/' . ltrim($p, '/');
function isActive(string $path, string $current, bool $exact=false): string {
    if ($exact) return $current === $path ? 'active' : '';
    return strpos($current, $path) === 0 ? 'active' : '';
}
?>
<aside class="admin-sidebar">
  <div class="admin-sidebar-inner">
    <div class="admin-sidebar-brand">
      <span style="width:30px;height:30px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));border-radius:7px;display:grid;place-items:center;color:#000;font-size:0.8rem;flex-shrink:0"><i class="fas fa-camera"></i></span>
      <div>
        <div class="brand-name">Media<strong>bay</strong></div>
        <span class="brand-badge">Admin</span>
      </div>
    </div>

    <div class="sidebar-section-label">Utama</div>
    <a href="<?= BASE_URL ?>/admin"          class="admin-nav-link <?= isActive('/admin',$p,true) ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <div class="sidebar-section-label">Booking</div>
    <a href="<?= BASE_URL ?>/admin/bookings" class="admin-nav-link <?= isActive('/admin/bookings',$p) ?>"><i class="fas fa-calendar-check"></i> Semua Booking</a>
    <a href="<?= BASE_URL ?>/admin/payments" class="admin-nav-link <?= isActive('/admin/payments',$p) ?>"><i class="fas fa-credit-card"></i> Verifikasi Bayar</a>

    <div class="sidebar-section-label">CRUD</div>
    <a href="<?= BASE_URL ?>/admin/categories"  class="admin-nav-link <?= isActive('/admin/categories',$p) ?>"><i class="fas fa-th-large"></i> Kategori</a>
    <a href="<?= BASE_URL ?>/admin/services"    class="admin-nav-link <?= isActive('/admin/services',$p) ?>"><i class="fas fa-concierge-bell"></i> Layanan</a>
    <a href="<?= BASE_URL ?>/admin/packages"    class="admin-nav-link <?= isActive('/admin/packages',$p) ?>"><i class="fas fa-box"></i> Paket Harga</a>
    <a href="<?= BASE_URL ?>/admin/carousel"    class="admin-nav-link <?= isActive('/admin/carousel',$p) ?>"><i class="fas fa-images"></i> Carousel / Hero</a>
    <a href="<?= BASE_URL ?>/admin/news"        class="admin-nav-link <?= isActive('/admin/news',$p) ?>"><i class="fas fa-newspaper"></i> Berita & Portfolio</a>

    <div class="sidebar-section-label" style="margin-top:8px"></div>
    <a href="<?= BASE_URL ?>/" target="_blank" class="admin-nav-link"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
    <a href="<?= BASE_URL ?>/auth/logout" class="admin-nav-link" style="color:var(--danger)"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
</aside>
