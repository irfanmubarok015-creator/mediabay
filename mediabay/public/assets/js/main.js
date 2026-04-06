/* ============================================================
   MEDIABAY — main.js v2.0
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* ── THEME TOGGLE ──────────────────────────────────────── */
  const THEME_KEY = 'mb_theme';
  const root      = document.documentElement;

  const applyTheme = (t) => {
    root.setAttribute('data-theme', t);
    localStorage.setItem(THEME_KEY, t);
  };

  // Load saved or prefer-color-scheme
  const saved = localStorage.getItem(THEME_KEY);
  if (saved) {
    applyTheme(saved);
  } else if (window.matchMedia('(prefers-color-scheme: light)').matches) {
    applyTheme('light');
  }

  document.querySelectorAll('.theme-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const current = root.getAttribute('data-theme');
      applyTheme(current === 'light' ? 'dark' : 'light');
    });
  });


  /* ── NAVBAR SCROLL ─────────────────────────────────────── */
  const navbar  = document.getElementById('mainNavbar');
  const overlay = document.getElementById('navOverlay');

  const onScroll = () => {
    navbar?.classList.toggle('scrolled', window.scrollY > 30);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();


  /* ── HAMBURGER ─────────────────────────────────────────── */
  const ham     = document.getElementById('hamburger');
  const navMenu = document.getElementById('navMenu');

  ham?.addEventListener('click', () => {
    const open = ham.classList.toggle('open');
    navMenu?.classList.toggle('open', open);
    overlay?.classList.toggle('active', open);
  });

  overlay?.addEventListener('click', () => {
    ham?.classList.remove('open');
    navMenu?.classList.remove('open');
    overlay?.classList.remove('active');
    document.getElementById('userDropdown')?.classList.remove('open');
  });


  /* ── DROPDOWN OVERLAY ──────────────────────────────────── */
  document.querySelectorAll('.nav-item-dropdown').forEach(el => {
    el.addEventListener('mouseenter', () => overlay?.classList.add('active'));
    el.addEventListener('mouseleave', () => {
      if (!ham?.classList.contains('open')) overlay?.classList.remove('active');
    });
  });


  /* ── USER AVATAR DROPDOWN ──────────────────────────────── */
  const userBtn  = document.getElementById('userAvatarBtn');
  const userDrop = document.getElementById('userDropdown');

  userBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    const open = userDrop?.classList.toggle('open');
    overlay?.classList.toggle('active', !!open);
  });

  document.addEventListener('click', () => {
    if (userDrop?.classList.contains('open')) {
      userDrop.classList.remove('open');
      if (!ham?.classList.contains('open')) overlay?.classList.remove('active');
    }
  });


  /* ══════════════════════════════════════════════════════════
     CAROUSEL — Rebuilt
     Supports: image slides, video slides (autoplay muted)
     Controls: prev/next arrows, dot progress, auto-advance
  ══════════════════════════════════════════════════════════ */
  const CAROUSEL_DELAY = 6000;

  const slides    = Array.from(document.querySelectorAll('.carousel-slide'));
  const dots      = Array.from(document.querySelectorAll('.c-dot'));
  const counter   = document.querySelector('.c-counter');
  const titleWrap = document.getElementById('hero-title-wrap');
  const subWrap   = document.getElementById('hero-sub-wrap');

  if (slides.length) {
    let current = 0;
    let timer   = null;
    let paused  = false;

    // Build slide data from data attributes
    const slideData = slides.map(s => ({
      title : s.dataset.title    || '',
      sub   : s.dataset.subtitle || '',
      cta   : s.dataset.cta      || '',
      ctaUrl: s.dataset.ctaUrl   || '',
    }));

    const updateText = (idx) => {
      if (!titleWrap && !subWrap) return;
      const d = slideData[idx];
      if (titleWrap && d.title) {
        titleWrap.innerHTML = `<h2 class="hero-slide-title">${d.title.replace(/\n/g,'<br>')}</h2>`;
      }
      if (subWrap && d.sub) {
        subWrap.innerHTML = `<p class="hero-slide-sub">${d.sub}</p>`;
      }
    };

    const goTo = (n) => {
      const prev = slides[current];
      const prevVideo = prev?.querySelector('video');
      if (prevVideo) { prevVideo.pause(); }

      slides[current]?.classList.remove('active');
      dots[current]?.classList.remove('active');

      current = ((n % slides.length) + slides.length) % slides.length;

      slides[current]?.classList.add('active');
      dots[current]?.classList.add('active');

      if (counter) counter.textContent = `${String(current+1).padStart(2,'0')} / ${String(slides.length).padStart(2,'0')}`;

      // Play video if present
      const vid = slides[current]?.querySelector('video');
      if (vid) {
        vid.currentTime = 0;
        vid.play().catch(() => {}); // may fail due to autoplay policy — silent
      }

      updateText(current);
    };

    const next = () => goTo(current + 1);
    const prev = () => goTo(current - 1);

    const startTimer = () => {
      clearInterval(timer);
      if (!paused) timer = setInterval(next, CAROUSEL_DELAY);
    };

    // Dot clicks
    dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); startTimer(); }));

    // Arrow clicks
    document.querySelector('.c-arrow-prev')?.addEventListener('click', () => { prev(); startTimer(); });
    document.querySelector('.c-arrow-next')?.addEventListener('click', () => { next(); startTimer(); });

    // Pause on hover
    const hero = document.querySelector('.hero');
    hero?.addEventListener('mouseenter', () => { paused = true;  clearInterval(timer); });
    hero?.addEventListener('mouseleave', () => { paused = false; startTimer(); });

    // Touch swipe
    let touchX = 0;
    hero?.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; }, { passive: true });
    hero?.addEventListener('touchend',   e => {
      const dx = e.changedTouches[0].clientX - touchX;
      if (Math.abs(dx) > 50) { dx < 0 ? next() : prev(); startTimer(); }
    }, { passive: true });

    // Init
    goTo(0);
    startTimer();
  }


  /* ── SCROLL REVEAL ─────────────────────────────────────── */
  const revEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
  if ('IntersectionObserver' in window && revEls.length) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(({ target, isIntersecting }) => {
        if (isIntersecting) { target.classList.add('visible'); io.unobserve(target); }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    revEls.forEach(el => io.observe(el));
  } else {
    revEls.forEach(el => el.classList.add('visible'));
  }


  /* ── HERO SCROLL BUTTON ────────────────────────────────── */
  document.querySelector('.hero-scroll')?.addEventListener('click', () => {
    document.querySelector('.marquee-section, section:not(.hero)')
      ?.scrollIntoView({ behavior: 'smooth' });
  });


  /* ── FLASH AUTO-DISMISS ────────────────────────────────── */
  document.querySelectorAll('.flash').forEach(f => {
    setTimeout(() => {
      f.style.transition = 'opacity 0.4s, transform 0.4s';
      f.style.opacity = '0'; f.style.transform = 'translateY(-8px)';
      setTimeout(() => f.remove(), 400);
    }, 5000);
  });


  /* ── MODALS ────────────────────────────────────────────── */
  document.querySelectorAll('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById(btn.dataset.modalOpen)?.classList.add('open');
    });
  });

  document.querySelectorAll('.modal-overlay').forEach(ov => {
    ov.addEventListener('click', e => { if (e.target === ov) ov.classList.remove('open'); });
  });

  document.querySelectorAll('.modal-close').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.modal-overlay')?.classList.remove('open'));
  });


  /* ── UPLOAD DRAG & DROP ────────────────────────────────── */
  document.querySelectorAll('.upload-area').forEach(area => {
    const inputId = area.dataset.input || area.getAttribute('for');
    const input   = (inputId && document.getElementById(inputId)) || area.querySelector('input[type=file]');
    const nameEl  = area.querySelector('.upload-filename');

    const setName = (name) => { if (nameEl) nameEl.textContent = name; };

    area.addEventListener('click', e => { if (e.target === area || !e.target.closest('input')) input?.click(); });
    area.addEventListener('dragover',  e => { e.preventDefault(); area.classList.add('dragover'); });
    area.addEventListener('dragleave', () => area.classList.remove('dragover'));
    area.addEventListener('drop', e => {
      e.preventDefault(); area.classList.remove('dragover');
      if (input && e.dataTransfer.files.length) {
        // Transfer files
        try {
          const dt = new DataTransfer();
          Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
          input.files = dt.files;
          setName(e.dataTransfer.files[0].name);
          input.dispatchEvent(new Event('change'));
        } catch (_) { setName(e.dataTransfer.files[0].name); }
      }
    });
    input?.addEventListener('change', () => setName(input.files[0]?.name || ''));
  });


  /* ── IMAGE PREVIEW on upload ───────────────────────────── */
  document.querySelectorAll('input[type=file][data-preview]').forEach(inp => {
    const prevId = inp.dataset.preview;
    const prev   = prevId && document.getElementById(prevId);
    if (!prev) return;
    inp.addEventListener('change', () => {
      const file = inp.files[0];
      if (!file || !file.type.startsWith('image/')) return;
      const reader = new FileReader();
      reader.onload = e => {
        prev.src = e.target.result;
        prev.style.display = 'block';
      };
      reader.readAsDataURL(file);
    });
  });


  /* ── CONFIRM DIALOGS ───────────────────────────────────── */
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
  });


  /* ── BUTTON RIPPLE ─────────────────────────────────────── */
  if (!document.getElementById('ripple-kf')) {
    const s = document.createElement('style');
    s.id = 'ripple-kf';
    s.textContent = '@keyframes mb-ripple{to{transform:scale(2.8);opacity:0}}';
    document.head.appendChild(s);
  }
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', e => {
      const r    = btn.getBoundingClientRect();
      const size = Math.max(r.width, r.height);
      const rip  = document.createElement('span');
      Object.assign(rip.style, {
        position:'absolute', width:size+'px', height:size+'px', borderRadius:'50%',
        background:'rgba(255,255,255,0.12)', transform:'scale(0)', pointerEvents:'none',
        left:(e.clientX-r.left-size/2)+'px', top:(e.clientY-r.top-size/2)+'px',
        animation:'mb-ripple 0.55s ease-out forwards'
      });
      if (getComputedStyle(btn).position==='static') btn.style.position='relative';
      btn.style.overflow='hidden';
      btn.appendChild(rip);
      setTimeout(() => rip.remove(), 600);
    });
  });


  /* ── MINI CALENDAR ─────────────────────────────────────── */
  const calEl    = document.getElementById('miniCalendar');
  const calTitle = document.getElementById('calTitle');
  if (calEl) {
    const events = JSON.parse(calEl.dataset.events || '[]');
    const evMap  = {};
    events.forEach(e => { evMap[e.event_date] = e; });

    let now = new Date();
    const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'];

    const render = (y, m) => {
      if (calTitle) calTitle.textContent = `${MONTHS[m]} ${y}`;
      const first   = new Date(y, m, 1).getDay();
      const days    = new Date(y, m+1, 0).getDate();
      const today   = new Date();
      let html = '';
      for (let i = 0; i < first; i++) html += '<div class="cal-day empty"></div>';
      for (let d = 1; d <= days; d++) {
        const ds  = `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const ev  = evMap[ds];
        const isTd= d===today.getDate()&&m===today.getMonth()&&y===today.getFullYear();
        html += `<div class="cal-day${isTd?' today':''}${ev?' has-event':''}" title="${ev?ev.total+' booking':''}">
          <span class="cal-day-num">${d}</span>${ev?'<div class="cal-event-dot"></div>':''}
        </div>`;
      }
      calEl.innerHTML = html;
    };

    render(now.getFullYear(), now.getMonth());
    document.getElementById('calPrev')?.addEventListener('click', () => { now = new Date(now.getFullYear(), now.getMonth()-1, 1); render(now.getFullYear(), now.getMonth()); });
    document.getElementById('calNext')?.addEventListener('click', () => { now = new Date(now.getFullYear(), now.getMonth()+1, 1); render(now.getFullYear(), now.getMonth()); });
  }


  /* ── ADMIN: RELATION TREE ACCORDION ───────────────────── */
  document.querySelectorAll('.relation-category-header').forEach(h => {
    h.addEventListener('click', () => h.closest('.relation-category')?.classList.toggle('open'));
  });
  document.querySelectorAll('.relation-service-header').forEach(h => {
    h.addEventListener('click', () => h.closest('.relation-service')?.classList.toggle('open'));
  });


  /* ── LAZY LOAD IMAGES ──────────────────────────────────── */
  if ('IntersectionObserver' in window) {
    const lo = new IntersectionObserver(entries => {
      entries.forEach(({ target, isIntersecting }) => {
        if (isIntersecting) { target.src = target.dataset.src; delete target.dataset.src; lo.unobserve(target); }
      });
    });
    document.querySelectorAll('img[data-src]').forEach(img => lo.observe(img));
  }

});
