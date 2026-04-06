<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath    = rtrim(str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME'])), '/');
$relPath     = str_replace($basePath . '/public', '', $currentPath);
?>
<aside class="sidebar">
  <div style="padding:0 8px 24px;margin-bottom:16px;border-bottom:1px solid var(--border-soft)">
    <?php if (!empty($_SESSION['avatar'])): ?>
    <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($_SESSION['avatar']) ?>" style="width:56px;height:56px;border-radius:50%;object-fit:cover;margin-bottom:12px">
    <?php else: ?>
    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:grid;place-items:center;font-size:1.4rem;font-weight:700;color:var(--dark);margin-bottom:12px">
      <?= strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)) ?>
    </div>
    <?php endif; ?>
    <div style="font-weight:600;font-size:0.95rem"><?= htmlspecialchars($_SESSION['name'] ?? '') ?></div>
    <div style="font-size:0.78rem;color:var(--text-muted)"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></div>
  </div>

  <nav class="sidebar-menu">
    <a href="<?= BASE_URL ?>/dashboard" class="sidebar-link <?= $relPath === '/dashboard' ? 'active' : '' ?>">
      <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="<?= BASE_URL ?>/dashboard/booking" class="sidebar-link <?= strpos($relPath, '/dashboard/booking') === 0 ? 'active' : '' ?>">
      <i class="fas fa-calendar-check"></i> Riwayat Booking
    </a>
    <a href="<?= BASE_URL ?>/dashboard/profil" class="sidebar-link <?= strpos($relPath, '/dashboard/profil') === 0 ? 'active' : '' ?>">
      <i class="fas fa-user"></i> Profil Saya
    </a>
    <div class="divider" style="margin:12px 0"></div>
    <a href="<?= BASE_URL ?>/booking" class="sidebar-link">
      <i class="fas fa-plus-circle"></i> Buat Booking Baru
    </a>
    <a href="<?= BASE_URL ?>/auth/logout" class="sidebar-link" style="color:var(--danger)">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </nav>
</aside>
