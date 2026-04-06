<?php $pageTitle = 'Profil Saya'; ?>
<div class="dashboard-layout">
  <?php require __DIR__ . '/../partials/user_sidebar.php'; ?>
  <div class="dashboard-main">
    <div class="page-header">
      <h1>Profil <em>Saya</em></h1>
      <p>Kelola informasi akun Anda</p>
    </div>

    <?php if (isset($flash) && $flash): ?>
    <div class="flash flash-<?= $flash['type'] ?>">
      <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
      <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:280px 1fr;gap:32px;align-items:start" class="profile-layout">
      <!-- Avatar Card -->
      <div class="card">
        <div class="card-body" style="text-align:center">
          <div style="position:relative;display:inline-block;margin-bottom:16px">
            <?php if (!empty($user['avatar'])): ?>
            <img src="<?= BASE_URL ?>/uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>"
                 style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--gold)">
            <?php else: ?>
            <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:grid;place-items:center;font-size:2.5rem;font-weight:700;color:var(--dark);border:3px solid var(--gold)">
              <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
            <?php endif; ?>
          </div>
          <h3 style="margin-bottom:4px"><?= htmlspecialchars($user['name']) ?></h3>
          <p style="font-size:0.82rem;color:var(--text-muted)"><?= htmlspecialchars($user['email']) ?></p>
          <div style="margin-top:12px">
            <span class="badge badge-approved">User Aktif</span>
          </div>
          <div style="margin-top:16px;font-size:0.8rem;color:var(--text-muted)">
            Bergabung <?= date('M Y', strtotime($user['created_at'])) ?>
          </div>
        </div>
      </div>

      <!-- Edit Form -->
      <div class="card">
        <div class="card-header"><h3>Edit Informasi</h3></div>
        <div class="card-body">
          <form method="POST" action="<?= BASE_URL ?>/dashboard/profil" enctype="multipart/form-data">
            <div class="form-group">
              <label class="form-label">Nama Lengkap <span class="required">*</span></label>
              <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled style="opacity:0.6;cursor:not-allowed">
              <div class="form-hint">Email tidak dapat diubah</div>
            </div>
            <div class="form-group">
              <label class="form-label">No. WhatsApp</label>
              <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="628xxxxxxxxxx">
            </div>
            <div class="form-group">
              <label class="form-label">Foto Profil</label>
              <label class="upload-area" for="avatar_input" style="padding:20px">
                <i class="fas fa-camera" style="font-size:1.5rem"></i>
                <p style="margin-top:8px"><strong>Ganti foto profil</strong></p>
                <p style="font-size:0.75rem">JPG, PNG — Maks 2MB</p>
                <p class="upload-filename" style="color:var(--gold);font-size:0.82rem;margin-top:6px"></p>
                <input type="file" id="avatar_input" name="avatar" accept="image/*" style="display:none">
              </label>
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan Perubahan
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<style>@media(max-width:768px){.profile-layout{grid-template-columns:1fr!important}}</style>
