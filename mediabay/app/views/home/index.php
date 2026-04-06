<?php $pageTitle = 'Jasa Kreatif Profesional'; ?>

<!-- ══════════════════════════════════════════════════════════
     HERO / CAROUSEL — Rebuilt
     Supports image + video. Video = autoplay muted loop.
     ══════════════════════════════════════════════════════════ -->
<section class="hero">

  <!-- Slides -->
  <div class="carousel-slides">
    <?php foreach($carousel as $i => $item):
      $hasFile = !empty($item['media_file']) && file_exists(UPLOAD_PATH . 'carousel/' . $item['media_file']);
      $fileUrl  = $hasFile ? BASE_URL . '/uploads/carousel/' . htmlspecialchars($item['media_file']) : '';
      $isVideo  = $hasFile && $item['media_type'] === 'video';
      $isImage  = $hasFile && $item['media_type'] === 'image';
    ?>
    <div class="carousel-slide <?= $i === 0 ? 'active' : '' ?>"
         data-title="<?= htmlspecialchars($item['title'] ?? '') ?>"
         data-subtitle="<?= htmlspecialchars($item['subtitle'] ?? '') ?>"
         data-cta="<?= htmlspecialchars($item['cta_text'] ?? '') ?>"
         data-cta-url="<?= htmlspecialchars($item['cta_link'] ?? '') ?>">

      <?php if ($isVideo): ?>
        <!-- VIDEO SLIDE — autoplay, muted, loop, NO download controls -->
        <video
          src="<?= $fileUrl ?>"
          autoplay muted loop playsinline
          preload="<?= $i === 0 ? 'auto' : 'none' ?>"
          disablePictureInPicture
          controlsList="nodownload"
          style="pointer-events:none"
          aria-hidden="true">
        </video>

      <?php elseif ($isImage): ?>
        <!-- IMAGE SLIDE -->
        <div class="carousel-slide-img"
             style="background-image:url('<?= $fileUrl ?>')">
        </div>

      <?php else: ?>
        <!-- PLACEHOLDER GRADIENT -->
        <div class="carousel-slide-img cs-<?= ($i % 3) + 1 ?>"></div>
      <?php endif; ?>

    </div>
    <?php endforeach; ?>
  </div>

  <!-- Grain overlay -->
  <div class="hero-noise"></div>

  <!-- Content -->
  <div class="hero-content">
    <div class="hero-badge">
      <span></span>
      Layanan Kreatif Profesional
    </div>

    <!-- Titles updated by JS per slide -->
    <div id="hero-title-wrap" class="hero-title-wrap">
      <?php $h = $carousel[0] ?? null; ?>
      <h2 class="hero-slide-title">
        <?= $h && $h['title']
          ? nl2br(htmlspecialchars($h['title']))
          : 'Abadikan Momen<br><em>Terbaik Anda</em>' ?>
      </h2>
    </div>

    <div id="hero-sub-wrap">
      <p class="hero-slide-sub">
        <?= htmlspecialchars($h['subtitle'] ?? 'Layanan photography, videography, dan live streaming profesional untuk wedding, event, dan corporate Anda.') ?>
      </p>
    </div>

    <div class="hero-cta">
      <a href="<?= BASE_URL ?>/booking" class="btn btn-primary btn-lg">
        <i class="fas fa-calendar-plus"></i> Booking Sekarang
      </a>
      <a href="<?= BASE_URL ?>/layanan" class="btn btn-outline btn-lg">
        <i class="fas fa-eye"></i> Lihat Layanan
      </a>
    </div>
  </div>

  <!-- Navigation -->
  <div class="carousel-nav">
    <button class="c-arrow c-arrow-prev" aria-label="Previous slide">
      <i class="fas fa-chevron-left"></i>
    </button>

    <div class="c-dots">
      <?php foreach($carousel as $i => $item): ?>
      <div class="c-dot <?= $i === 0 ? 'active' : '' ?>" aria-label="Slide <?= $i+1 ?>"></div>
      <?php endforeach; ?>
    </div>

    <span class="c-counter">01 / <?= str_pad(count($carousel), 2, '0', STR_PAD_LEFT) ?></span>

    <button class="c-arrow c-arrow-next" aria-label="Next slide">
      <i class="fas fa-chevron-right"></i>
    </button>
  </div>

  <!-- Scroll cue -->
  <div class="hero-scroll">
    <span>Scroll</span>
    <i class="fas fa-chevron-down scroll-arrow"></i>
  </div>

</section>

<!-- ── MARQUEE STRIP ──────────────────────────────────────── -->
<section class="marquee-section">
  <div class="marquee-track">
    <?php $tags = ['Photography','Videography','Live Streaming','Wedding','Corporate','Event','Cinematic','Dokumentasi']; ?>
    <?php for($r=0;$r<4;$r++): foreach($tags as $t): ?>
    <div class="marquee-item"><i class="fas fa-diamond"></i><?= $t ?></div>
    <?php endforeach; endfor; ?>
  </div>
</section>

<!-- ── LAYANAN ────────────────────────────────────────────── -->
<section class="section services-section">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag">Layanan Kami</div>
      <h2>Kami Hadir untuk <em>Setiap Momen</em></h2>
      <p>Dari momen paling intim hingga event skala besar — kami hadir dengan keahlian dan dedikasi penuh.</p>
    </div>

    <div class="services-grid">
      <?php $bgCls = ['bg-photo','bg-video','bg-live'];
            $iconMap = ['photography'=>'camera','videography'=>'film','live-streaming'=>'tower-broadcast'];
      ?>
      <?php foreach($categories as $i => $cat): ?>
      <a href="<?= BASE_URL ?>/layanan/<?= htmlspecialchars($cat['slug']) ?>"
         class="service-card reveal delay-<?= $i+1 ?>">
        <div class="service-card-bg <?= $bgCls[$i % 3] ?>"
          <?= $cat['image'] ? 'style="background-image:url('.BASE_URL.'/uploads/categories/'.htmlspecialchars($cat['image']).')"' : '' ?>>
        </div>
        <div class="service-card-overlay"></div>
        <div class="service-card-body">
          <div class="service-card-icon">
            <i class="fas fa-<?= $iconMap[$cat['slug']] ?? ($cat['icon'] ?? 'camera') ?>"></i>
          </div>
          <h3><?= htmlspecialchars($cat['name']) ?></h3>
          <p><?= htmlspecialchars($cat['description'] ?? '') ?></p>
          <div class="service-card-cta">Lihat Paket <i class="fas fa-arrow-right"></i></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── PORTFOLIO / NEWS ───────────────────────────────────── -->
<?php if ($news): ?>
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag">Portfolio & Inspirasi</div>
      <h2>Karya <em>Terbaik</em> Kami</h2>
      <p>Setiap karya bercerita. Lihat bagaimana kami mengabadikan momen berharga klien kami.</p>
    </div>
    <div class="news-grid">
      <?php foreach($news as $i => $item): ?>
      <article class="news-card reveal delay-<?= ($i%3)+1 ?>">
        <div class="news-card-img">
          <?php if ($item['image']): ?>
          <img data-src="<?= BASE_URL ?>/uploads/portfolio/<?= htmlspecialchars($item['image']) ?>"
               src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="
               alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
          <?php else: ?>
          <div class="news-card-img-placeholder"><i class="fas fa-image"></i></div>
          <?php endif; ?>
        </div>
        <div class="news-card-body">
          <span class="news-card-tag"><?= ucfirst($item['category']) ?></span>
          <h3><a href="<?= BASE_URL ?>/informasi/<?= htmlspecialchars($item['slug']) ?>"><?= htmlspecialchars($item['title']) ?></a></h3>
          <p><?= htmlspecialchars(mb_substr($item['excerpt'] ?? '', 0, 110)) ?>...</p>
        </div>
        <div class="news-card-footer">
          <span class="news-card-date"><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($item['published_at'])) ?></span>
          <a href="<?= BASE_URL ?>/informasi/<?= htmlspecialchars($item['slug']) ?>" class="news-card-read">
            Selengkapnya <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:44px">
      <a href="<?= BASE_URL ?>/informasi" class="btn btn-outline">Lihat Semua Karya</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── CALENDAR & BOOKING CTA ─────────────────────────────── -->
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 420px;gap:64px;align-items:center" class="cal-layout">
      <div>
        <div class="section-tag reveal">Jadwal Event</div>
        <h2 class="reveal" style="margin:12px 0 18px">Cek <em>Ketersediaan</em> Tanggal</h2>
        <p class="reveal" style="color:var(--text-soft);line-height:1.8;margin-bottom:28px;font-size:1.02rem">
          Lihat tanggal yang sudah ada booking. Satu tanggal bisa memiliki beberapa booking. Konsultasikan jadwal Anda langsung dengan tim kami.
        </p>
        <div class="booking-warning reveal">
          <i class="fas fa-triangle-exclamation"></i>
          <p><strong>Perhatian:</strong> Booking akan dikonfirmasi admin. <strong>Slot belum dijamin hingga disetujui.</strong> Setelah disetujui, upload bukti DP untuk mengamankan tanggal Anda.</p>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:24px" class="reveal">
          <a href="<?= BASE_URL ?>/booking" class="btn btn-primary">
            <i class="fas fa-calendar-plus"></i> Buat Booking
          </a>
          <a href="<?= BASE_URL ?>/contact" class="btn btn-ghost">
            <i class="fab fa-whatsapp"></i> Konsultasi Gratis
          </a>
        </div>
      </div>

      <div class="calendar-preview reveal-right">
        <div class="calendar-header">
          <h4 id="calTitle" style="font-family:var(--font-display)"></h4>
          <div class="calendar-nav">
            <button class="cal-nav-btn" id="calPrev"><i class="fas fa-chevron-left"></i></button>
            <button class="cal-nav-btn" id="calNext"><i class="fas fa-chevron-right"></i></button>
          </div>
        </div>
        <div class="calendar-grid">
          <?php foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $d): ?>
          <div class="cal-day-name"><?= $d ?></div>
          <?php endforeach; ?>
          <div id="miniCalendar" style="display:contents"
               data-events="<?= htmlspecialchars(json_encode($calendar)) ?>"></div>
        </div>
        <div style="margin-top:14px;display:flex;gap:18px;font-size:0.76rem;color:var(--text-muted)">
          <span style="display:flex;align-items:center;gap:6px">
            <div style="width:8px;height:8px;border-radius:50%;background:var(--gold)"></div> Ada booking
          </span>
          <span style="display:flex;align-items:center;gap:6px">
            <div style="width:8px;height:8px;border-radius:50%;background:rgba(201,168,76,0.3);border:1px solid var(--gold)"></div> Hari ini
          </span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── CTA ────────────────────────────────────────────────── -->
<section class="cta-section section">
  <div class="cta-bg"></div>
  <div class="container">
    <div class="section-tag reveal">Mulai Sekarang</div>
    <h2 class="reveal" style="margin:16px auto">Book Your<br><em>Moment Now</em></h2>
    <p class="reveal">Jangan biarkan momen berharga berlalu. Kami siap mengabadikan setiap cerita Anda.</p>
    <div class="cta-buttons reveal">
      <a href="<?= BASE_URL ?>/booking" class="btn btn-primary btn-lg">
        <i class="fas fa-calendar-plus"></i> Booking Sekarang
      </a>
      <a href="<?= BASE_URL ?>/contact" class="btn btn-ghost btn-lg">
        <i class="fab fa-whatsapp"></i> Chat WhatsApp
      </a>
    </div>
  </div>
</section>

<style>
@media(max-width:900px){.cal-layout{grid-template-columns:1fr!important}}
</style>
