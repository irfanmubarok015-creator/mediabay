<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>404 — Mediabay</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:#0d0d0d;color:#f0ece4;min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:24px}
h1{font-family:'Playfair Display',serif;font-size:8rem;color:#c9a84c;line-height:1;margin-bottom:16px}
h2{font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:12px}
p{color:#8a8580;margin-bottom:32px}
a{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:100px;background:linear-gradient(135deg,#c9a84c,#9c7a2e);color:#0d0d0d;font-weight:600;text-decoration:none}
</style>
</head>
<body>
<div>
  <h1>404</h1>
  <h2>Halaman Tidak Ditemukan</h2>
  <p>Maaf, halaman yang Anda cari tidak tersedia.</p>
  <a href="<?= BASE_URL ?? '/' ?>/">↩ Kembali ke Beranda</a>
</div>
</body>
</html>
