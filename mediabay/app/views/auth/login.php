<?php $pageTitle = 'Masuk'; ?>
<div class="auth-page">
  <div class="auth-bg"></div>
  <div class="auth-card">
    <div class="auth-logo">
      <a href="<?= BASE_URL ?>/">Media<strong>bay</strong></a>
    </div>

    <?php if (isset($flash) && $flash): ?>
    <div class="flash flash-<?= $flash['type'] ?>">
      <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
      <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>

    <h2 class="auth-title">Selamat Datang</h2>
    <p class="auth-subtitle">Masuk ke akun Mediabay Anda</p>

    <form method="POST" action="<?= BASE_URL ?>/auth/login">
      <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required autofocus>
      </div>
      
      <div class="form-group">
        <label class="form-label">Password <span class="required">*</span></label>
        <div style="position:relative">
          <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" required style="padding-right:44px">
          <button type="button" onclick="togglePass()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted)" id="passToggle">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px">
        <i class="fas fa-sign-in-alt"></i> Masuk
      </button>
    </form>

    <div class="auth-footer">
      Belum punya akun? <a href="<?= BASE_URL ?>/auth/register">Daftar sekarang</a>
    </div>
  </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
function togglePass(){
  const inp = document.getElementById('passwordInput');
  const btn = document.getElementById('passToggle').querySelector('i');
  inp.type = inp.type === 'password' ? 'text' : 'password';
  btn.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>