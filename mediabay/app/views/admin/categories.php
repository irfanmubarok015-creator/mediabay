<?php $pageTitle = 'Kategori Layanan'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Kategori <em>Layanan</em></h1>
    <p>Kelola Photography, Videography, Live Streaming</p>
  </div>
  <div class="admin-page-header-actions">
    <button class="btn btn-primary" data-modal-open="modal-add-cat"><i class="fas fa-plus"></i> Tambah Kategori</button>
  </div>
</div>

<?php if (!empty($flash)): ?>
<div class="flash flash-<?= htmlspecialchars($flash['type']) ?>"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="admin-card">
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>#</th><th>Nama Kategori</th><th>Slug</th><th>Deskripsi</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach($categories as $cat): ?>
        <tr>
          <td style="color:var(--text-muted)"><?= $cat['id'] ?></td>
          <td>
            <div class="td-title"><?= htmlspecialchars($cat['name']) ?></div>
          </td>
          <td><code style="font-size:0.78rem;background:var(--bg-4);padding:2px 8px;border-radius:5px;color:var(--gold)"><?= htmlspecialchars($cat['slug']) ?></code></td>
          <td class="td-sub" style="max-width:220px"><?= htmlspecialchars(mb_substr($cat['description']??'',0,65)) ?>...</td>
          <td><span class="badge <?= $cat['is_active']?'badge-approved':'badge-rejected' ?>"><?= $cat['is_active']?'Aktif':'Nonaktif' ?></span></td>
          <td>
            <div class="td-actions">
              <button class="btn btn-ghost btn-sm" data-modal-open="modal-edit-cat-<?= $cat['id'] ?>"><i class="fas fa-edit"></i> Edit</button>
              <form method="POST" action="<?= BASE_URL ?>/admin/categories/<?= $cat['id'] ?>/delete">
                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus kategori '<?= htmlspecialchars($cat['name']) ?>'? Semua layanan terkait ikut terhapus."><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal-overlay" id="modal-add-cat">
  <div class="modal">
    <div class="modal-header"><h3><i class="fas fa-plus"></i> Tambah Kategori</h3><button class="modal-close"><i class="fas fa-times"></i></button></div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/categories" id="addCatForm">
        <div class="form-group"><label class="form-label">Nama <span class="required">*</span></label><input type="text" name="name" class="form-control" required placeholder="Contoh: Photography"></div>
        <div class="form-group"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" placeholder="auto-generate jika kosong"><div class="form-hint">Contoh: photography (huruf kecil, pakai tanda -)</div></div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat kategori..."></textarea></div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="addCatForm" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    </div>
  </div>
</div>

<?php foreach($categories as $cat): ?>
<div class="modal-overlay" id="modal-edit-cat-<?= $cat['id'] ?>">
  <div class="modal">
    <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit: <?= htmlspecialchars($cat['name']) ?></h3><button class="modal-close"><i class="fas fa-times"></i></button></div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/categories/<?= $cat['id'] ?>/update" id="editCatForm<?= $cat['id'] ?>">
        <div class="form-group"><label class="form-label">Nama</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($cat['name']) ?>"></div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($cat['description']??'') ?></textarea></div>
        <div class="form-group"><label class="form-label">Status</label>
          <select name="is_active" class="form-control">
            <option value="1" <?= $cat['is_active']?'selected':'' ?>>✅ Aktif</option>
            <option value="0" <?= !$cat['is_active']?'selected':'' ?>>❌ Nonaktif</option>
          </select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="editCatForm<?= $cat['id'] ?>" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
    </div>
  </div>
</div>
<?php endforeach; ?>
