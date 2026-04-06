<?php $pageTitle = 'Daftar'; ?>
<div class="auth-page">
  <div class="auth-bg"></div>
  <div class="auth-card" style="max-width:520px">
    <div class="auth-logo">
      <a href="<?= BASE_URL ?>/">Media<strong>bay</strong></a>
    </div>

    <?php if (isset($flash) && $flash): ?>
    <div class="flash flash-<?= $flash['type'] ?>">
      <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
      <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>

    <h2 class="auth-title">Buat Akun</h2>
    <p class="auth-subtitle">Daftar untuk mulai booking layanan kami</p>

    <form method="POST" action="<?= BASE_URL ?>/auth/register">
      <div class="form-group">
        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda" required autofocus value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Email <span class="required">*</span></label>
          <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">No. WhatsApp <span class="required">*</span></label>
          <input type="tel" name="phone" class="form-control" placeholder="628xxxxxxxxxx" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Password <span class="required">*</span></label>
          <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required minlength="8">
        </div>
        <div class="form-group">
          <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
          <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi password" required>
        </div>
      </div>
      <div class="form-hint" style="margin-bottom:16px">
        <i class="fas fa-info-circle" style="color:var(--info)"></i>
        Nomor WhatsApp digunakan untuk notifikasi booking
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%">
        <i class="fas fa-user-plus"></i> Daftar Sekarang
      </button>
    </form>

    <div class="auth-footer">
      Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login">Masuk di sini</a>
    </div>
  </div>
</div>
