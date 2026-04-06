<!DOCTYPE html>
<html lang="id" data-theme="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle).' — Admin Mediabay' : 'Admin Panel — Mediabay' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <!-- Prevent theme flash -->
    <script>(function(){var t=localStorage.getItem('mb_theme');if(!t&&window.matchMedia('(prefers-color-scheme:light)').matches)t='light';if(t)document.documentElement.setAttribute('data-theme',t);})();</script>
    <?= $extraCss ?? '' ?>
</head>
<body>
<div class="nav-overlay" id="navOverlay"></div>

<!-- Admin topbar -->
<nav class="navbar scrolled" id="mainNavbar">
  <div class="nav-container">
    
    <div style="flex:1"></div>

    <a href="<?= BASE_URL ?>/" target="_blank" class="btn btn-ghost btn-sm" style="margin-right:8px">
      <i class="fas fa-external-link-alt"></i> Website
    </a>

    <div class="user-dropdown-wrapper">
      <button class="user-avatar-btn" id="userAvatarBtn">
        <div class="avatar-initials"><?= strtoupper(mb_substr($_SESSION['name']??'A',0,1)) ?></div>
        <span class="user-name-nav"><?= htmlspecialchars(explode(' ',$_SESSION['name']??'Admin')[0]) ?></span>
      </button>
      <div class="user-dropdown" id="userDropdown">
        <div class="user-dropdown-header">
          <div class="ud-name"><?= htmlspecialchars($_SESSION['name']??'') ?></div>
          <div class="ud-email" style="color:var(--gold);font-size:0.7rem">Administrator</div>
        </div>
        <div class="user-dropdown-menu">
          <a href="<?= BASE_URL ?>/auth/logout" class="ud-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- Sidebar + main -->
<?php require __DIR__ . '/../partials/admin_sidebar.php'; ?>

<main class="admin-main">
  <?= $content ?>
</main>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<?= $extraJs ?? '' ?>
</body>
</html>
