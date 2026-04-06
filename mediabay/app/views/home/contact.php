<?php $pageTitle = 'Hubungi Kami'; ?>

<div class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/">Home</a><span>/</span><span>Contact</span>
    </div>
    <h1>Hubungi <em>Kami</em></h1>
    <p>Kami siap membantu mewujudkan momen tak terlupakan Anda</p>
  </div>
</div>

<section class="section-sm">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start" class="contact-layout">

      <!-- Contact Info -->
      <div>
        <div class="section-tag reveal" style="margin-bottom:16px">Kontak & Lokasi</div>
        <h2 class="reveal" style="margin-bottom:24px">Siap <em>Membantu</em> Anda</h2>
        <p class="reveal" style="color:var(--text-soft);line-height:1.8;margin-bottom:36px">
          Konsultasikan kebutuhan foto/video Anda dengan tim profesional kami. Kami akan membantu memilihkan paket terbaik sesuai kebutuhan dan anggaran.
        </p>

        <div style="display:flex;flex-direction:column;gap:20px">
          <?php $contacts = [
            ['fas fa-map-marker-alt','Lokasi','Jln. Raya Gumulung Lebak Desa Gumulung Lebak Kec. Greged Kab. Cirebon'],
            ['fab fa-whatsapp','WhatsApp','+62 838 0433 9441'],
            ['fas fa-envelope','Email','mediabay@gmail.com'],
            ['fas fa-clock','Jam Operasional','Setiap Hari, 08.00 – 17.00 WIB'],
          ]; ?>
          <?php foreach($contacts as $i => [$icon, $label, $val]): ?>
          <div class="reveal delay-<?= $i+1 ?>" style="display:flex;gap:16px;align-items:flex-start">
            <div style="width:44px;height:44px;border-radius:10px;background:rgba(201,168,76,0.1);border:1px solid var(--border);display:grid;place-items:center;flex-shrink:0;color:var(--gold)">
              <i class="<?= $icon ?>"></i>
            </div>
            <div>
              <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px"><?= $label ?></div>
              <div style="font-size:0.95rem"><?= htmlspecialchars($val) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Social -->
        <div style="margin-top:36px" class="reveal">
          <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:16px">Ikuti Kami</div>
          <div style="display:flex;gap:12px">
            <?php $socials = [['fab fa-instagram','Instagram'],['fab fa-youtube','YouTube'],['fab fa-tiktok','TikTok']]; ?>
            <?php foreach($socials as [$ic, $name]): ?>
            <a href="#" style="width:44px;height:44px;border-radius:10px;border:1px solid var(--border-soft);background:var(--dark-3);display:grid;place-items:center;color:var(--text-muted);transition:var(--transition)" class="social-icon">
              <i class="<?= $ic ?>"></i>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="reveal-right">
        <?php if (isset($flash) && $flash): ?>
        <div class="flash flash-<?= $flash['type'] ?>">
          <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
          <?= htmlspecialchars($flash['message']) ?>
        </div>
        <?php endif; ?>

        <div class="card">
          <div class="card-header">
            <h3><i class="fas fa-paper-plane" style="color:var(--gold);margin-right:8px"></i>Kirim Pesan</h3>
          </div>
          <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/contact">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Nama <span class="required">*</span></label>
                  <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Email <span class="required">*</span></label>
                  <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">No. WhatsApp</label>
                <input type="tel" name="phone" class="form-control" placeholder="628xxxxxxxxxx">
              </div>
              <div class="form-group">
                <label class="form-label">Subjek</label>
                <select name="subject" class="form-control">
                  <option>Konsultasi Paket Layanan</option>
                  <option>Pertanyaan Harga</option>
                  <option>Booking Custom</option>
                  <option>Kerjasama</option>
                  <option>Lainnya</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Pesan <span class="required">*</span></label>
                <textarea name="message" class="form-control" rows="5" placeholder="Tuliskan kebutuhan atau pertanyaan Anda..." required></textarea>
              </div>
              <button type="submit" class="btn btn-primary" style="width:100%">
                <i class="fas fa-paper-plane"></i> Kirim Pesan
              </button>
            </form>
          </div>
        </div>

        <!-- WhatsApp CTA -->
        <div style="text-align:center;margin-top:20px;padding:20px;background:rgba(37,211,102,0.06);border:1px solid rgba(37,211,102,0.2);border-radius:var(--radius);font-size:0.88rem;color:var(--text-muted)">
          Atau langsung chat kami via
          <a href="https://wa.me/<?= WA_ADMIN_NUMBER ?>" target="_blank" style="color:#25d366;font-weight:700;margin-left:4px">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>
          untuk respon lebih cepat
        </div>
      </div>

    </div>
  </div>
</section>

<style>
.social-icon:hover{border-color:var(--gold)!important;color:var(--gold)!important;background:rgba(201,168,76,0.08)!important}
@media(max-width:768px){.contact-layout{grid-template-columns:1fr!important}}
</style>
