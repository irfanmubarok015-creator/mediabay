<?php $pageTitle = 'Carousel / Hero'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Carousel <em>/ Hero</em></h1>
    <p>Kelola slide pada halaman utama. Foto tampil sebagai background, video autoplay tanpa kontrol download.</p>
  </div>
  <div class="admin-page-header-actions">
    <button class="btn btn-primary" data-modal-open="modal-add-car">
      <i class="fas fa-plus"></i> Tambah Slide
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
      <i class="fas fa-images"></i>
      <h3>Belum Ada Slide</h3>
      <p>Tambah slide pertama untuk hero section halaman utama</p>
      <button class="btn btn-primary" style="margin-top:16px" data-modal-open="modal-add-car">
        <i class="fas fa-plus"></i> Tambah Slide
      </button>
    </div>
  </div>
</div>
<?php else: ?>

<div class="admin-card" style="margin-bottom:16px;padding:14px 18px">
  <div style="display:flex;align-items:center;gap:10px;font-size:0.83rem;color:var(--text-muted)">
    <i class="fas fa-info-circle" style="color:var(--info)"></i>
    <span><?= count($items) ?> slide aktif. Urutan ditentukan oleh nilai <strong>Sort Order</strong>. Video diputar otomatis tanpa tombol download.</span>
  </div>
</div>

<div class="carousel-admin-grid">
  <?php foreach($items as $item):
    $hasFile = !empty($item['media_file']) && file_exists(UPLOAD_PATH . 'carousel/' . $item['media_file']);
    $fileUrl  = $hasFile ? BASE_URL.'/uploads/carousel/'.htmlspecialchars($item['media_file']) : '';
    $isVideo  = $hasFile && $item['media_type']==='video';
  ?>
  <div class="carousel-item-card">
    <div class="carousel-thumb">
      <?php if ($isVideo): ?>
        <video src="<?= $fileUrl ?>" muted loop preload="metadata"
               style="width:100%;height:100%;object-fit:cover"
               onmouseenter="this.play()" onmouseleave="this.pause()">
        </video>
      <?php elseif ($hasFile): ?>
        <img src="<?= $fileUrl ?>" alt="<?= htmlspecialchars($item['title']??'') ?>" loading="lazy">
      <?php else: ?>
        <div class="carousel-thumb-placeholder">
          <i class="fas fa-<?= $item['media_type']==='video'?'film':'image' ?>"></i>
          <span>No media</span>
        </div>
      <?php endif; ?>

      <div class="carousel-thumb-badges">
        <span class="badge <?= $item['is_active']?'badge-approved':'badge-rejected' ?>">
          <?= $item['is_active']?'Aktif':'Nonaktif' ?>
        </span>
        <span class="badge badge-requested" style="text-transform:none;letter-spacing:0">
          <i class="fas fa-<?= $item['media_type']==='video'?'film':'image' ?>"></i>
          <?= ucfirst($item['media_type']) ?>
        </span>
      </div>
    </div>

    <div class="carousel-item-body">
      <div class="carousel-item-title"><?= htmlspecialchars($item['title'] ?: '(Tanpa judul)') ?></div>
      <div class="carousel-item-sub"><?= htmlspecialchars(mb_substr($item['subtitle']??'',0,55)) ?><?= strlen($item['subtitle']??'')>55?'...':'' ?></div>
      <div class="carousel-item-meta">
        <span><i class="fas fa-sort-numeric-up"></i> Urutan: <?= (int)$item['sort_order'] ?></span>
        <?php if ($item['cta_text']): ?>
        <span><i class="fas fa-link"></i> CTA: <?= htmlspecialchars($item['cta_text']) ?></span>
        <?php endif; ?>
      </div>
      <div class="carousel-item-actions">
        <a href="<?= BASE_URL ?>/" target="_blank" class="btn btn-ghost btn-sm">
          <i class="fas fa-eye"></i> Preview
        </a>
        <form method="POST" action="<?= BASE_URL ?>/admin/carousel/<?= $item['id'] ?>/delete" style="flex:1">
          <button type="submit" class="btn btn-danger btn-sm" style="width:100%"
                  data-confirm="Hapus slide '<?= htmlspecialchars($item['title']??'ini') ?>'?">
            <i class="fas fa-trash"></i> Hapus
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── ADD MODAL ─────────────────────────────────────────── -->
<div class="modal-overlay" id="modal-add-car">
  <div class="modal">
    <div class="modal-header">
      <h3><i class="fas fa-plus"></i> Tambah Slide Carousel</h3>
      <button class="modal-close"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <form method="POST" action="<?= BASE_URL ?>/admin/carousel" enctype="multipart/form-data" id="carouselForm">

        <div class="form-group">
          <label class="form-label">Tipe Media <span class="required">*</span></label>
          <div style="display:flex;gap:10px">
            <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--bg-4);border:1.5px solid var(--border-soft);border-radius:var(--radius);cursor:pointer;transition:var(--transition)" id="type-img-label">
              <input type="radio" name="media_type" value="image" checked onchange="switchMediaType('image')"> 
              <i class="fas fa-image" style="color:var(--gold)"></i> <span>Foto / Gambar</span>
            </label>
            <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--bg-4);border:1.5px solid var(--border-soft);border-radius:var(--radius);cursor:pointer;transition:var(--transition)" id="type-vid-label">
              <input type="radio" name="media_type" value="video" onchange="switchMediaType('video')">
              <i class="fas fa-film" style="color:var(--gold)"></i> <span>Video</span>
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">File Media <span class="required">*</span></label>
          <label class="upload-area" for="car_media_input">
            <i class="fas fa-cloud-upload-alt" style="font-size:2rem;margin-bottom:8px"></i>
            <p><strong>Klik atau drag & drop</strong></p>
            <p id="car_accept_hint" style="font-size:0.78rem;margin-top:4px;color:var(--text-muted)">JPG, PNG, WEBP — Maks 5MB</p>
            <p class="upload-filename" style="color:var(--gold);font-size:0.82rem;margin-top:8px;font-weight:600"></p>
            <input type="file" id="car_media_input" name="media" accept="image/*" style="display:none" required>
          </label>
          <!-- Preview -->
          <div id="car_preview_wrap" style="display:none;margin-top:12px;border-radius:var(--radius);overflow:hidden;aspect-ratio:16/9;background:var(--bg-4)">
            <img id="car_img_preview" style="width:100%;height:100%;object-fit:cover;display:none">
            <video id="car_vid_preview" style="width:100%;height:100%;object-fit:cover;display:none" muted controls></video>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Judul</label>
          <input type="text" name="title" class="form-control" placeholder="Abadikan Momen Terbaik Anda">
        </div>

        <div class="form-group">
          <label class="form-label">Subjudul</label>
          <textarea name="subtitle" class="form-control" rows="2" placeholder="Deskripsi singkat..."></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Teks Tombol CTA</label>
            <input type="text" name="cta_text" class="form-control" placeholder="Booking Sekarang">
          </div>
          <div class="form-group">
            <label class="form-label">Link Tombol CTA</label>
            <input type="text" name="cta_link" class="form-control" placeholder="/booking">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Urutan Tampil</label>
          <input type="number" name="sort_order" class="form-control" value="<?= count($items) + 1 ?>" min="0">
          <div class="form-hint">Angka lebih kecil tampil lebih dulu. Saat ini ada <?= count($items) ?> slide.</div>
        </div>

      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="carouselForm" class="btn btn-primary">
        <i class="fas fa-upload"></i> Upload & Simpan
      </button>
    </div>
  </div>
</div>

<script>
function switchMediaType(type) {
  const input    = document.getElementById('car_media_input');
  const hint     = document.getElementById('car_accept_hint');
  const imgPrev  = document.getElementById('car_img_preview');
  const vidPrev  = document.getElementById('car_vid_preview');
  const prevWrap = document.getElementById('car_preview_wrap');

  if (type === 'video') {
    input.accept = 'video/mp4,video/webm,video/*';
    hint.textContent = 'MP4, WEBM — Maks 50MB. Video autoplay tanpa kontrol download.';
  } else {
    input.accept = 'image/*';
    hint.textContent = 'JPG, PNG, WEBP — Maks 5MB';
  }
  input.value = '';
  prevWrap.style.display = 'none';
  imgPrev.style.display = 'none';
  vidPrev.style.display = 'none';
}

document.getElementById('car_media_input').addEventListener('change', function() {
  const file     = this.files[0];
  const wrap     = document.getElementById('car_preview_wrap');
  const imgPrev  = document.getElementById('car_img_preview');
  const vidPrev  = document.getElementById('car_vid_preview');
  if (!file) return;

  wrap.style.display = 'block';
  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = e => { imgPrev.src = e.target.result; imgPrev.style.display='block'; vidPrev.style.display='none'; };
    reader.readAsDataURL(file);
  } else if (file.type.startsWith('video/')) {
    vidPrev.src = URL.createObjectURL(file);
    vidPrev.style.display = 'block';
    imgPrev.style.display = 'none';
  }
});
</script>
