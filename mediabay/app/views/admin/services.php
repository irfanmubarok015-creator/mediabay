<?php $pageTitle = 'Manajemen Layanan'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Layanan</h1>
    <p>Kelola semua jenis layanan dalam setiap kategori</p>
  </div>
  <div class="admin-page-header-actions">
    <button class="btn btn-primary" data-modal-open="modal-add-svc"><i class="fas fa-plus"></i> Tambah Layanan</button>
  </div>
</div>

<?php if (!empty($flash)): ?>
<div class="flash flash-<?= htmlspecialchars($flash['type']) ?>"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="admin-card">
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>Nama Layanan</th><th>Kategori</th><th>Deskripsi</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach($services as $s): ?>
        <tr>
          <td><div class="td-title"><?= htmlspecialchars($s['name']) ?></div></td>
          <td><span class="badge badge-requested"><?= htmlspecialchars($s['category_name']) ?></span></td>
          <td class="td-sub" style="max-width:220px"><?= htmlspecialchars(mb_substr($s['description']??'',0,70)) ?>...</td>
          <td><span class="badge <?= $s['is_active']?'badge-approved':'badge-rejected' ?>"><?= $s['is_active']?'Aktif':'Nonaktif' ?></span></td>
          <td>
            <div class="td-actions">
              <button class="btn btn-ghost btn-sm" data-modal-open="modal-edit-svc-<?= $s['id'] ?>"><i class="fas fa-edit"></i> Edit</button>
              <form method="POST" action="<?= BASE_URL ?>/admin/services/<?= $s['id'] ?>/delete">
                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus layanan '<?= htmlspecialchars($s['name']) ?>'?"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal-overlay" id="modal-add-svc">
  <div class="modal">
    <div class="modal-header"><h3><i class="fas fa-plus"></i> Tambah Layanan</h3><button class="modal-close"><i class="fas fa-times"></i></button></div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/services" id="addSvcForm">
        <div class="form-group"><label class="form-label">Kategori <span class="required">*</span></label>
          <select name="category_id" class="form-control" required>
            <option value="">— Pilih Kategori —</option>
            <?php foreach($categories as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group"><label class="form-label">Nama Layanan <span class="required">*</span></label><input type="text" name="name" class="form-control" required placeholder="Contoh: Wedding Photography"></div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat layanan..."></textarea></div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="addSvcForm" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    </div>
  </div>
</div>

<?php foreach($services as $s): ?>
<div class="modal-overlay" id="modal-edit-svc-<?= $s['id'] ?>">
  <div class="modal">
    <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit Layanan</h3><button class="modal-close"><i class="fas fa-times"></i></button></div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/services/<?= $s['id'] ?>/update" id="editSvcForm<?= $s['id'] ?>">
        <div class="form-group"><label class="form-label">Nama</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($s['name']) ?>"></div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($s['description']??'') ?></textarea></div>
        <div class="form-group"><label class="form-label">Status</label>
          <select name="is_active" class="form-control">
            <option value="1" <?= $s['is_active']?'selected':'' ?>>✅ Aktif</option>
            <option value="0" <?= !$s['is_active']?'selected':'' ?>>❌ Nonaktif</option>
          </select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="editSvcForm<?= $s['id'] ?>" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
    </div>
  </div>
</div>
<?php endforeach; ?>
