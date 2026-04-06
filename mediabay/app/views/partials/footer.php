<?php
// Footer menggunakan variable berbeda agar tidak conflict dengan $categories di controller
$footerCategories = (new CategoryModel())->getActive();
$iconMap = [
    'photography'    => 'camera',
    'videography'    => 'film',
    'live-streaming' => 'tower-broadcast',
];
?>
<footer class="footer">
    <div class="footer-wave">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="currentColor"/>
        </svg>
    </div>
    <div class="footer-body">
        <div class="footer-container">

            <!-- Brand -->
            <div class="footer-col footer-brand">
                <div class="footer-logo">
                    <span class="logo-icon" style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:inline-grid;place-items:center;color:var(--dark);font-size:0.85rem">
                        <i class="fas fa-camera"></i>
                    </span>
                    <span>Media<strong>bay</strong></span>
                </div>
                <p>Jasa kreatif profesional untuk momen berharga Anda. Photography, Videography, dan Live Streaming berkualitas tinggi.</p>
                <div class="footer-social">
                    <a href="https://www.instagram.com/mediabayhd?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://youtube.com/@mediabayhd?si=j1iJ98egsUOuwnk2" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- Layanan -->
            <div class="footer-col">
                <h4>Layanan Kami</h4>
                <ul>
                    <?php foreach($footerCategories as $cat):
                        $icon = $iconMap[$cat['slug']] ?? ($cat['icon'] ?? 'circle');
                    ?>
                    <li>
                        <a href="<?= BASE_URL ?>/layanan/<?= htmlspecialchars($cat['slug']) ?>">
                            <i class="fas fa-<?= $icon ?>"></i>
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Info -->
            <div class="footer-col">
                <h4>Informasi</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>/informasi"><i class="fas fa-images"></i> Portfolio</a></li>
                    <li><a href="<?= BASE_URL ?>/informasi"><i class="fas fa-newspaper"></i> Blog</a></li>
                    <li><a href="<?= BASE_URL ?>/contact"><i class="fas fa-envelope"></i> Hubungi Kami</a></li>
                    <li><a href="<?= BASE_URL ?>/booking"><i class="fas fa-calendar-plus"></i> Booking Sekarang</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="footer-col">
                <h4>Kontak</h4>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> Jln. Raya Gumulung Lebak Desa Gumulung Lebak Kec. Greged Kab. Cirebon</li>
                    <li><i class="fas fa-phone"></i> +62 838 0433 9441</li>
                    <li><i class="fas fa-envelope"></i> mediabay@gmail.com</li>
                    <li><i class="fas fa-clock"></i> Setiap Hari, 08.00–17.00</li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Mediabay. All rights reserved.</p>
            <p>Made with <i class="fas fa-heart" style="color:#e74c3c"></i> for beautiful moments</p>
        </div>
    </div>
</footer>
