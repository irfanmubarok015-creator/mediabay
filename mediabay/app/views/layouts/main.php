<!DOCTYPE html>
<html lang="id" data-theme="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Mediabay' : 'Mediabay — Jasa Kreatif Profesional' ?></title>
    <meta name="description" content="Mediabay — Photography, Videography & Live Streaming profesional untuk wedding, event, dan corporate.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <?= $extraCss ?? '' ?>
    <!-- Theme init: prevent flash -->
    <script>
      (function(){
        var t = localStorage.getItem('mb_theme');
        if (!t && window.matchMedia('(prefers-color-scheme: light)').matches) t = 'light';
        if (t) document.documentElement.setAttribute('data-theme', t);
      })();
    </script>
</head>
<body>

<div class="nav-overlay" id="navOverlay"></div>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main><?= $content ?></main>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<?= $extraJs ?? '' ?>
</body>
</html>
