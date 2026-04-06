<?php $pageTitle = 'Berita & Portfolio'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Berita & <em>Portfolio</em></h1>
    <p>Kelola artikel, berita, dan dokumentasi portfolio dengan foto</p>
  </div>
  <div class="admin-page-header-actions">
    <button class="btn btn-primary" data-modal-open="modal-add-news">
      <i class="fas fa-plus"></i> Tambah Artikel
    </button>
  </div>
</div>

<?php if (!empty($flash)): ?>
<div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
  <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?>"></i>
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<?php if (empty($items)): ?>
<div class="admin-card">
  <div class="admin-card-body">
    <div class="empty-state">
      <i class="fas fa-newspaper"></i>
      <h3>Belum Ada Artikel</h3>
      <p>Tambah artikel portfolio atau berita pertama Anda</p>
      <button class="btn btn-primary" style="margin-top:16px" data-modal-open="modal-add-news">
        <i class="fas fa-plus"></i> Tambah Artikel
      </button>
    </div>
  </div>
</div>
<?php else: ?>

<!-- Summary bar -->
<div class="filter-bar" style="margin-bottom:20px">
  <span>Total: <?= count($items) ?> artikel</span>
  <?php
  $counts = array_count_values(array_column($items, 'category'));
  foreach(['portfolio','news','blog'] as $c):
    $n = $counts[$c] ?? 0; if (!$n) continue;
  ?>
  <span class="badge badge-requested"><?= ucfirst($c) ?>: <?= $n ?></span>
  <?php endforeach; ?>
  <?php $pub = count(array_filter($items, fn($i)=>$i['is_published'])); ?>
  <span class="badge badge-approved">Published: <?= $pub ?></span>
  <span class="badge badge-rejected">Draft: <?= count($items)-$pub ?></span>
</div>

<div class="news-admin-grid">
  <?php foreach($items as $item):
    $hasImg = !empty($item['image']) && file_exists(UPLOAD_PATH.'portfolio/'.$item['image']);
    $imgUrl  = $hasImg ? BASE_URL.'/uploads/portfolio/'.htmlspecialchars($item['image']) : '';
  ?>
  <div class="news-admin-card">
    <div class="news-admin-thumb">
      <?php if ($imgUrl): ?>
        <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
      <?php else: ?>
        <div class="news-admin-thumb-placeholder">
          <i class="fas fa-image"></i>
          <span>Belum ada foto</span>
        </div>
      <?php endif; ?>
      <!-- Category badge overlay -->
      <div style="position:absolute;top:8px;left:8px">
        <span class="badge badge-requested"><?= ucfirst($item['category']) ?></span>
      </div>
      <div style="position:absolute;top:8px;right:8px">
        <span class="badge <?= $item['is_published']?'badge-approved':'badge-rejected' ?>">
          <?= $item['is_published']?'Published':'Draft' ?>
        </span>
      </div>
    </div>

    <div class="news-admin-body">
      <div class="news-admin-title"><?= htmlspecialchars($item['title']) ?></div>
      <div class="news-admin-excerpt">
        <?= htmlspecialchars(mb_substr($item['excerpt']??$item['content']??'',0,85)) ?>...
      </div>
      <div class="news-admin-meta">
        <span style="display:flex;align-items:center;gap:4px;font-size:0.72rem;color:var(--text-muted)">
          <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($item['created_at'])) ?>
        </span>
        <?php if (!$imgUrl): ?>
        <span style="color:var(--warning);font-size:0.72rem;display:flex;align-items:center;gap:3px">
          <i class="fas fa-image"></i> Tidak ada foto
        </span>
        <?php endif; ?>
      </div>
      <div class="news-admin-actions">
        <a href="<?= BASE_URL ?>/informasi/<?= htmlspecialchars($item['slug']) ?>" target="_blank" class="btn btn-ghost btn-sm">
          <i class="fas fa-eye"></i>
        </a>
        <button class="btn btn-ghost btn-sm" data-modal-open="modal-edit-news-<?= $item['id'] ?>">
          <i class="fas fa-edit"></i> Edit
        </button>
        <form method="POST" action="<?= BASE_URL ?>/admin/news/<?= $item['id'] ?>/delete">
          <button type="submit" class="btn btn-danger btn-sm"
                  data-confirm="Hapus artikel '<?= htmlspecialchars($item['title']) ?>'?">
            <i class="fas fa-trash"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── ADD MODAL ─────────────────────────────────────────── -->
<div class="modal-overlay" id="modal-add-news">
  <div class="modal" style="max-width:680px">
    <div class="modal-header">
      <h3><i class="fas fa-plus"></i> Tambah Artikel / Portfolio</h3>
      <button class="modal-close"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/news" enctype="multipart/form-data" id="newsAddForm">

        <div class="form-row">
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Judul <span class="required">*</span></label>
            <input type="text" name="title" class="form-control" required placeholder="Judul artikel atau nama event/proyek">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-control">
              <option value="portfolio">📸 Portfolio</option>
              <option value="news">📰 Berita</option>
              <option value="blog">✍️ Blog</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="is_published" class="form-control">
              <option value="1">✅ Published (tampil di website)</option>
              <option value="0">📝 Draft (tidak tampil)</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Foto Utama / Dokumentasi</label>
          <label class="upload-area" for="news_img_input" style="padding:20px">
            <i class="fas fa-camera" style="font-size:1.8rem;margin-bottom:8px"></i>
            <p><strong>Upload foto dokumentasi</strong></p>
            <p style="font-size:0.78rem;margin-top:4px;color:var(--text-muted)">JPG, PNG, WEBP — Maks 5MB. Foto ini tampil di kartu artikel.</p>
            <p class="upload-filename" style="color:var(--gold);font-size:0.82rem;margin-top:8px;font-weight:600"></p>
            <input type="file" id="news_img_input" name="image" accept="image/*" style="display:none" data-preview="news_add_preview">
          </label>
          <div id="news_add_preview_wrap" style="display:none;margin-top:10px;border-radius:var(--radius);overflow:hidden;aspect-ratio:16/9;background:var(--bg-4)">
            <img id="news_add_preview" style="width:100%;height:100%;object-fit:cover">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Ringkasan / Deskripsi Singkat</label>
          <textarea name="excerpt" class="form-control" rows="2" placeholder="Deskripsi singkat yang tampil di kartu artikel (maks ~150 karakter)..."></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Konten Lengkap</label>
          <textarea name="content" class="form-control" rows="6" placeholder="Detail cerita, informasi lengkap, atau deskripsi proyek..."></textarea>
        </div>

      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="newsAddForm" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan Artikel
      </button>
    </div>
  </div>
</div>

<!-- ── EDIT MODALS ────────────────────────────────────────── -->
<?php foreach($items as $item):
  $hasImg = !empty($item['image']) && file_exists(UPLOAD_PATH.'portfolio/'.$item['image']);
  $imgUrl  = $hasImg ? BASE_URL.'/uploads/portfolio/'.htmlspecialchars($item['image']) : '';
?>
<div class="modal-overlay" id="modal-edit-news-<?= $item['id'] ?>">
  <div class="modal" style="max-width:680px">
    <div class="modal-header">
      <h3><i class="fas fa-edit"></i> Edit Artikel</h3>
      <button class="modal-close"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/news/<?= $item['id'] ?>/update"
            enctype="multipart/form-data" id="newsEditForm<?= $item['id'] ?>">

        <div class="form-group">
          <label class="form-label">Judul <span class="required">*</span></label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($item['title']) ?>" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-control">
              <?php foreach(['portfolio'=>'📸 Portfolio','news'=>'📰 Berita','blog'=>'✍️ Blog'] as $v=>$l): ?>
              <option value="<?= $v ?>" <?= $item['category']===$v?'selected':'' ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="is_published" class="form-control">
              <option value="1" <?= $item['is_published']?'selected':'' ?>>✅ Published</option>
              <option value="0" <?= !$item['is_published']?'selected':'' ?>>📝 Draft</option>
            </select>
          </div>
        </div>

        <!-- Foto saat ini -->
        <div class="form-group">
          <label class="form-label">Foto Dokumentasi</label>
          <?php if ($imgUrl): ?>
          <div style="display:flex;gap:12px;align-items:center;padding:12px;background:var(--bg-4);border:1px solid var(--border-soft);border-radius:var(--radius);margin-bottom:10px">
            <img src="<?= $imgUrl ?>" style="width:64px;height:48px;object-fit:cover;border-radius:6px">
            <div>
              <div style="font-size:0.82rem;font-weight:600">Foto saat ini</div>
              <div style="font-size:0.74rem;color:var(--text-muted)">Upload foto baru untuk mengganti</div>
            </div>
          </div>
          <?php else: ?>
          <div style="padding:10px 14px;background:rgba(243,156,18,0.07);border:1px solid rgba(243,156,18,0.2);border-radius:var(--radius);font-size:0.82rem;color:var(--warning);margin-bottom:10px">
            <i class="fas fa-exclamation-triangle"></i> Belum ada foto. Upload foto dokumentasi agar artikel lebih menarik.
          </div>
          <?php endif; ?>
          <label class="upload-area" for="edit_img_<?= $item['id'] ?>" style="padding:16px">
            <i class="fas fa-camera"></i>
            <p style="font-size:0.84rem"><strong><?= $imgUrl?'Ganti foto':'Upload foto'?></strong></p>
            <p class="upload-filename" style="color:var(--gold);font-size:0.8rem;margin-top:6px;font-weight:600"></p>
            <input type="file" id="edit_img_<?= $item['id'] ?>" name="image" accept="image/*" style="display:none"
                   data-preview="edit_preview_<?= $item['id'] ?>">
          </label>
          <div id="edit_preview_wrap_<?= $item['id'] ?>" style="display:none;margin-top:10px;border-radius:var(--radius);overflow:hidden;aspect-ratio:16/9">
            <img id="edit_preview_<?= $item['id'] ?>" style="width:100%;height:100%;object-fit:cover">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Ringkasan</label>
          <textarea name="excerpt" class="form-control" rows="2"><?= htmlspecialchars($item['excerpt']??'') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Konten</label>
          <textarea name="content" class="form-control" rows="5"><?= htmlspecialchars($item['content']??'') ?></textarea>
        </div>

      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="newsEditForm<?= $item['id'] ?>" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan Perubahan
      </button>
    </div>
  </div>
</div>
<?php endforeach; ?>

<script>
// Show preview when file selected
document.querySelectorAll('input[type=file][data-preview]').forEach(inp => {
  inp.addEventListener('change', function() {
    const prev  = document.getElementById(this.dataset.preview);
    const wrap  = document.getElementById(this.dataset.preview.replace('news_add_preview','news_add_preview_wrap')
                                                               .replace('edit_preview_','edit_preview_wrap_'));
    if (!prev || !this.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
      prev.src = e.target.result;
      prev.style.display = 'block';
      if (wrap) wrap.style.display = 'block';
    };
    reader.readAsDataURL(this.files[0]);
  });
});

// Fix for add form preview
document.getElementById('news_img_input')?.addEventListener('change', function() {
  const prev = document.getElementById('news_add_preview');
  const wrap = document.getElementById('news_add_preview_wrap');
  if (!this.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => { prev.src = e.target.result; wrap.style.display='block'; };
  reader.readAsDataURL(this.files[0]);
});
</script>
