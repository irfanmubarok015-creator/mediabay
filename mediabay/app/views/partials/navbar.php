<?php
$navCategories = (new CategoryModel())->getActive();
$iconMap = ['photography'=>'camera','videography'=>'film','live-streaming'=>'tower-broadcast'];
// Hitung relPath secara andal dari BASE_URL
$fullUri  = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$baseDir  = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
$relPath  = $baseDir ? substr($fullUri, strlen($baseDir)) : $fullUri;
$relPath  = '/' . ltrim($relPath, '/');
if ($relPath !== '/') $relPath = rtrim($relPath, '/');
?>
<nav class="navbar" id="mainNavbar">
  <div class="nav-container">

    <a href="<?= BASE_URL ?>/" class="nav-logo">
      <span class="logo-icon"><i class="fas fa-camera"></i></span>
      <span class="logo-text">Media<strong>bay</strong></span>
    </a>

    <ul class="nav-menu" id="navMenu">
      <li><a href="<?= BASE_URL ?>/" class="nav-link <?= $relPath==='/'?'active':'' ?>">Home</a></li>

      <li class="nav-item-dropdown">
        <a href="<?= BASE_URL ?>/layanan" class="nav-link <?= strpos($relPath,'/layanan')===0?'active':'' ?>">
          Layanan <i class="fas fa-chevron-down nav-chevron"></i>
        </a>
        <div class="nav-dropdown">
          <div class="dropdown-inner">
            <?php foreach($navCategories as $cat):
              $ic = $iconMap[$cat['slug']] ?? ($cat['icon'] ?? 'circle');
            ?>
            <a href="<?= BASE_URL ?>/layanan/<?= htmlspecialchars($cat['slug']) ?>" class="dropdown-item">
              <span class="dropdown-icon"><i class="fas fa-<?= $ic ?>"></i></span>
              <span class="dropdown-text">
                <strong><?= htmlspecialchars($cat['name']) ?></strong>
                <small><?= htmlspecialchars(mb_substr($cat['description']??'',0,52)) ?>...</small>
              </span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
      </li>

      <li><a href="<?= BASE_URL ?>/informasi" class="nav-link <?= strpos($relPath,'/informasi')===0?'active':'' ?>">Informasi</a></li>
      <li><a href="<?= BASE_URL ?>/contact"   class="nav-link <?= $relPath==='/contact'?'active':'' ?>">Contact</a></li>
    </ul>

    <div class="nav-right">
      <!-- Theme toggle -->
      <button class="theme-toggle" title="Toggle tema" aria-label="Toggle dark/light mode">
        <i class="fas fa-sun icon-sun"></i>
        <i class="fas fa-moon icon-moon"></i>
      </button>

      <?php if (!empty($_SESSION['user_id'])): ?>
      <div class="user-dropdown-wrapper">
        <button class="user-avatar-btn" id="userAvatarBtn" aria-label="Menu pengguna">
          <?php if (!empty($_SESSION['avatar'])): ?>
            <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="" class="avatar-img">
          <?php else: ?>
            <div class="avatar-initials"><?= strtoupper(mb_substr($_SESSION['name']??'U',0,1)) ?></div>
          <?php endif; ?>
          <span class="user-name-nav"><?= htmlspecialchars(explode(' ',$_SESSION['name']??'')[0]) ?></span>
        </button>
        <div class="user-dropdown" id="userDropdown">
          <div class="user-dropdown-header">
            <div class="ud-name"><?= htmlspecialchars($_SESSION['name']??'') ?></div>
            <div class="ud-email"><?= htmlspecialchars($_SESSION['email']??'') ?></div>
          </div>
          <div class="user-dropdown-menu">
            <a href="<?= BASE_URL ?>/dashboard/profil"><i class="fas fa-user"></i> Profil Saya</a>
            <a href="<?= BASE_URL ?>/dashboard/booking"><i class="fas fa-calendar-check"></i> Riwayat Booking</a>
            <?php if (($_SESSION['role']??'') === 'admin'): ?>
            <a href="<?= BASE_URL ?>/admin"><i class="fas fa-tachometer-alt"></i> Admin Panel</a>
            <?php endif; ?>
            <div class="ud-divider"></div>
            <a href="<?= BASE_URL ?>/auth/logout" class="ud-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
      </div>
      <?php else: ?>
      <a href="<?= BASE_URL ?>/auth/login" class="btn-nav-login">Masuk</a>
      <a href="<?= BASE_URL ?>/booking" class="btn-nav-book"><i class="fas fa-calendar-plus"></i> Booking</a>
      <?php endif; ?>

      <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
    </div>

  </div>
</nav>
