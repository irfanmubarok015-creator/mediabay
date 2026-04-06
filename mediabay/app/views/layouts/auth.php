<!DOCTYPE html>
<html lang="id" data-theme="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Mediabay' : 'Mediabay' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <script>(function(){var t=localStorage.getItem('mb_theme');if(!t&&window.matchMedia('(prefers-color-scheme: light)').matches)t='light';if(t)document.documentElement.setAttribute('data-theme',t);})();</script>
</head>
<body>
<!-- Theme toggle on auth pages -->
<div style="position:fixed;top:16px;right:16px;z-index:100">
  <button class="theme-toggle" title="Toggle tema" aria-label="Toggle dark/light mode">
    <i class="fas fa-sun icon-sun"></i>
    <i class="fas fa-moon icon-moon"></i>
  </button>
</div>
<main><?= $content ?></main>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
