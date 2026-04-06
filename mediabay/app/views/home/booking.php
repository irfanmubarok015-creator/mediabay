<?php $pageTitle = 'Buat Booking'; ?>

<div class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a><span>/</span><span>Booking</span>
        </div>
        <h1>Buat <em>Booking</em></h1>
        <p>Isi form berikut untuk mengajukan booking layanan kami</p>
    </div>
</div>

<section class="section-sm">
    <div class="container-sm">

        <?php if (!empty($flash)): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
            <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= htmlspecialchars($flash['message']) ?>
        </div>
        <?php endif; ?>

        <!-- Warning -->
        <div class="booking-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <p>
                <strong>Penting:</strong> Booking akan dikonfirmasi oleh admin.
                <strong>Slot belum dijamin hingga disetujui.</strong>
                Setelah disetujui, Anda perlu mengupload bukti DP untuk mengamankan slot.
            </p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-calendar-plus" style="color:var(--gold);margin-right:8px"></i>
                    Form Pengajuan Booking
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/booking" id="bookingForm">

                    <!-- Step 1: Pilih Layanan -->
                    <div style="margin-bottom:8px;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--gold)">
                        Langkah 1 — Pilih Layanan
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select class="form-control" id="cat_select">
                            <option value="">— Pilih kategori —</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Layanan <span class="required">*</span></label>
                        <select class="form-control" id="svc_select" disabled>
                            <option value="">— Pilih kategori dulu —</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Paket <span class="required">*</span></label>
                        <select class="form-control" id="pkg_select" name="package_id" required disabled>
                            <option value="">— Pilih layanan dulu —</option>
                        </select>
                    </div>

                    <!-- Package info box -->
                    <div id="pkg_info" style="display:none;background:var(--dark-4);border:1px solid var(--border);border-radius:var(--radius);padding:20px;margin-bottom:20px"></div>

                    <div class="divider"></div>

                    <!-- Step 2: Detail Event -->
                    <div style="margin-bottom:16px;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--gold)">
                        Langkah 2 — Detail Event
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Acara</label>
                        <input type="text" name="event_name" class="form-control"
                               placeholder="Contoh: Pernikahan Budi & Ayu"
                               value="<?= htmlspecialchars($_POST['event_name'] ?? '') ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tanggal Event <span class="required">*</span></label>
                            <input type="date" name="event_date" class="form-control" required
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   value="<?= htmlspecialchars($_POST['event_date'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="event_time" class="form-control"
                                   value="<?= htmlspecialchars($_POST['event_time'] ?? '08:00') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lokasi Event <span class="required">*</span></label>
                        <input type="text" name="event_location" class="form-control" required
                               placeholder="Nama gedung / alamat lengkap"
                               value="<?= htmlspecialchars($_POST['event_location'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="form-control"
                                  placeholder="Informasi tambahan yang perlu diketahui tim kami..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                    </div>

                    <div style="display:flex;gap:12px;margin-top:8px;flex-wrap:wrap">
                        <button type="submit" class="btn btn-primary btn-lg" style="flex:1;min-width:200px">
                            <i class="fas fa-paper-plane"></i> Kirim Booking
                        </button>
                        <a href="<?= BASE_URL ?>/layanan" class="btn btn-ghost btn-lg">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</section>

<script>
(function(){
    // Data services dari PHP
    const allServices = <?= json_encode($services, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) ?>;
    const baseUrl     = <?= json_encode(BASE_URL) ?>;

    const catEl  = document.getElementById('cat_select');
    const svcEl  = document.getElementById('svc_select');
    const pkgEl  = document.getElementById('pkg_select');
    const infoEl = document.getElementById('pkg_info');

    // Saat kategori berubah → populate services
    catEl.addEventListener('change', function() {
        const catId = parseInt(this.value);
        svcEl.innerHTML = '<option value="">— Pilih layanan —</option>';
        pkgEl.innerHTML = '<option value="">— Pilih layanan dulu —</option>';
        infoEl.style.display = 'none';
        svcEl.disabled = true;
        pkgEl.disabled = true;

        if (!catId) return;

        const filtered = allServices.filter(s => parseInt(s.category_id) === catId);
        filtered.forEach(s => {
            const opt = new Option(s.name, s.id);
            svcEl.add(opt);
        });
        svcEl.disabled = false;
    });

    // Saat service berubah → fetch packages via API
    svcEl.addEventListener('change', async function() {
        const svcId = this.value;
        pkgEl.innerHTML = '<option value="">Memuat paket...</option>';
        pkgEl.disabled  = true;
        infoEl.style.display = 'none';

        if (!svcId) {
            pkgEl.innerHTML = '<option value="">— Pilih layanan dulu —</option>';
            return;
        }

        try {
            const res  = await fetch(baseUrl + '/api/packages/' + svcId);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();

            pkgEl.innerHTML = '<option value="">— Pilih paket —</option>';
            if (!data.length) {
                pkgEl.innerHTML = '<option value="">Tidak ada paket tersedia</option>';
                return;
            }
            data.forEach(p => {
                const label = p.name + ' — Rp ' + Number(p.price).toLocaleString('id-ID');
                const opt   = new Option(label, p.id);
                opt.dataset.pkg = JSON.stringify(p);
                pkgEl.add(opt);
            });
            pkgEl.disabled = false;

        } catch(e) {
            pkgEl.innerHTML = '<option value="">Gagal memuat paket</option>';
            console.error('Fetch packages error:', e);
        }
    });

    // Saat paket berubah → tampilkan info
    pkgEl.addEventListener('change', function() {
        const opt = this.selectedOptions[0];
        if (!opt || !opt.dataset.pkg) {
            infoEl.style.display = 'none';
            return;
        }
        const p  = JSON.parse(opt.dataset.pkg);
        const dp = Math.round(p.price * (p.dp_percentage / 100));

        let featHtml = '';
        if (p.features && p.features.length) {
            featHtml = '<div class="feature-list" style="margin-top:14px">'
                + p.features.map(f => `<div class="feature-item"><i class="fas fa-check-circle"></i>${f}</div>`).join('')
                + '</div>';
        }

        infoEl.style.display = 'block';
        infoEl.innerHTML = `
            <div style="display:flex;gap:24px;flex-wrap:wrap">
                <div><div style="font-size:0.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Total Harga</div>
                     <div style="font-size:1.2rem;font-weight:700">Rp ${Number(p.price).toLocaleString('id-ID')}</div></div>
                <div><div style="font-size:0.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">DP (${p.dp_percentage}%)</div>
                     <div style="font-size:1.2rem;font-weight:700;color:var(--gold)">Rp ${dp.toLocaleString('id-ID')}</div></div>
                <div><div style="font-size:0.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Durasi</div>
                     <div style="font-size:1rem;font-weight:600">${p.duration_hours} jam</div></div>
            </div>
            ${featHtml}
        `;
    });
})();
</script>
