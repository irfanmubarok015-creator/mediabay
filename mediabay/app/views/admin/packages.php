<?php $pageTitle = 'Paket Harga'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Paket <em>Harga</em></h1>
    <p>Kelola semua paket layanan dan detail harganya</p>
  </div>
  <div class="admin-page-header-actions">
    <a href="<?= BASE_URL ?>/admin/relations" class="btn btn-ghost btn-sm"><i class="fas fa-sitemap"></i> Lihat Relasi</a>
    <button class="btn btn-primary" data-modal-open="modal-add-pkg"><i class="fas fa-plus"></i> Tambah Paket</button>
  </div>
</div>

<?php if (!empty($flash)): ?>
<div class="flash flash-<?= htmlspecialchars($flash['type']) ?>"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="admin-card">
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>Nama Paket</th><th>Layanan</th><th>Harga</th><th>DP</th><th>Durasi</th><th>Fitur</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach($packages as $p):
          $feats = json_decode($p['features']??'[]', true);
        ?>
        <tr>
          <td>
            <div class="td-title"><?= htmlspecialchars($p['name']) ?></div>
            <div class="td-sub"><?= htmlspecialchars($p['category_name']) ?></div>
          </td>
          <td class="td-sub"><?= htmlspecialchars($p['service_name']) ?></td>
          <td><span style="color:var(--gold);font-weight:700">Rp <?= number_format($p['price'],0,',','.') ?></span></td>
          <td><span class="badge badge-waiting"><?= $p['dp_percentage'] ?>%</span></td>
          <td class="td-sub"><?= $p['duration_hours'] ?> jam</td>
          <td class="td-sub"><?= count($feats) ?> fitur</td>
          <td><span class="badge <?= $p['is_active']?'badge-approved':'badge-rejected' ?>"><?= $p['is_active']?'Aktif':'Nonaktif' ?></span></td>
          <td>
            <div class="td-actions">
              <button class="btn btn-ghost btn-sm" data-modal-open="modal-edit-pkg-<?= $p['id'] ?>"><i class="fas fa-edit"></i> Edit</button>
              <form method="POST" action="<?= BASE_URL ?>/admin/packages/<?= $p['id'] ?>/delete">
                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus paket '<?= htmlspecialchars($p['name']) ?>'?"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="modal-add-pkg">
  <div class="modal" style="max-width:640px">
    <div class="modal-header"><h3><i class="fas fa-plus"></i> Tambah Paket</h3><button class="modal-close"><i class="fas fa-times"></i></button></div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/packages" id="addPkgForm">
        <div class="form-group"><label class="form-label">Layanan <span class="required">*</span></label>
          <select name="service_id" class="form-control" required>
            <option value="">— Pilih Layanan —</option>
            <?php foreach($services as $s): ?><option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['category_name'].' — '.$s['name']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Nama Paket <span class="required">*</span></label><input type="text" name="name" class="form-control" required placeholder="Silver Package"></div>
          <div class="form-group"><label class="form-label">Harga (Rp) <span class="required">*</span></label><input type="number" name="price" class="form-control" required min="0" placeholder="2500000"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">DP (%)</label><input type="number" name="dp_percentage" class="form-control" value="50" min="0" max="100"></div>
          <div class="form-group"><label class="form-label">Durasi (jam)</label><input type="number" name="duration_hours" class="form-control" value="8" min="1"></div>
        </div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat paket..."></textarea></div>
        <div class="form-group"><label class="form-label">Fitur <span style="color:var(--text-muted);font-weight:400">(1 fitur per baris)</span></label>
          <textarea name="features" class="form-control" rows="6" placeholder="1 fotografer&#10;8 jam liputan&#10;200 foto edit&#10;Album digital&#10;Hard copy 4R"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="addPkgForm" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Paket</button>
    </div>
  </div>
</div>

<!-- Edit Modals -->
<?php foreach($packages as $p):
  $feats = json_decode($p['features']??'[]', true);
  $featStr = implode("\n", $feats);
?>
<div class="modal-overlay" id="modal-edit-pkg-<?= $p['id'] ?>">
  <div class="modal" style="max-width:640px">
    <div class="modal-header"><h3><i class="fas fa-edit"></i> Edit: <?= htmlspecialchars($p['name']) ?></h3><button class="modal-close"><i class="fas fa-times"></i></button></div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/packages/<?= $p['id'] ?>/update" id="editPkgForm<?= $p['id'] ?>">
        <div class="form-row">
          <div class="form-group"><label class="form-label">Nama Paket</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($p['name']) ?>"></div>
          <div class="form-group"><label class="form-label">Harga (Rp)</label><input type="number" name="price" class="form-control" value="<?= $p['price'] ?>"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">DP (%)</label><input type="number" name="dp_percentage" class="form-control" value="<?= $p['dp_percentage'] ?>"></div>
          <div class="form-group"><label class="form-label">Durasi (jam)</label><input type="number" name="duration_hours" class="form-control" value="<?= $p['duration_hours'] ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($p['description']??'') ?></textarea></div>
        <div class="form-group"><label class="form-label">Fitur (1 per baris)</label><textarea name="features" class="form-control" rows="6"><?= htmlspecialchars($featStr) ?></textarea></div>
        <div class="form-group"><label class="form-label">Status</label>
          <select name="is_active" class="form-control">
            <option value="1" <?= $p['is_active']?'selected':'' ?>>✅ Aktif</option>
            <option value="0" <?= !$p['is_active']?'selected':'' ?>>❌ Nonaktif</option>
          </select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="editPkgForm<?= $p['id'] ?>" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
    </div>
  </div>
</div>
<?php endforeach; ?>
