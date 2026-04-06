<?php $pageTitle = 'Detail Booking'; ?>
<div class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/">Home</a><span>/</span>
      <a href="<?= BASE_URL ?>/dashboard/booking">Booking</a><span>/</span>
      <span><?= htmlspecialchars($booking['booking_code']) ?></span>
    </div>
    <h1>Detail <em>Booking</em></h1>
  </div>
</div>

<section class="section-sm">
  <div class="container">
    <?php if (isset($flash) && $flash): ?>
    <div class="flash flash-<?= $flash['type'] ?>">
      <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
      <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>

    <div class="booking-detail-grid">
      <!-- LEFT: Detail -->
      <div>
        <!-- Status Card -->
        <div class="card" style="margin-bottom:24px">
          <div class="card-body">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
              <div>
                <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:4px">Kode Booking</div>
                <div style="font-family:var(--font-display);font-size:1.5rem;color:var(--gold);letter-spacing:1px"><?= htmlspecialchars($booking['booking_code']) ?></div>
              </div>
              <?php
                $statusMap = [
                  'REQUESTED'           => 'requested',
                  'WAITING_VERIFICATION'=> 'waiting',
                  'APPROVED'            => 'approved',
                  'REJECTED'            => 'rejected',
                  'EXPIRED'             => 'expired',
                ];
                $statusLabel = [
                  'REQUESTED'           => 'Menunggu Konfirmasi',
                  'WAITING_VERIFICATION'=> 'Menunggu Verifikasi Pembayaran',
                  'APPROVED'            => 'Disetujui',
                  'REJECTED'            => 'Ditolak',
                  'EXPIRED'             => 'Kadaluarsa',
                ];
              ?>
              <span class="badge badge-<?= $statusMap[$booking['status']] ?>">
                <?= $statusLabel[$booking['status']] ?? $booking['status'] ?>
              </span>
            </div>
          </div>
        </div>

        <!-- Booking Info -->
        <div class="card" style="margin-bottom:24px">
          <div class="card-header"><h3>Informasi Booking</h3></div>
          <div class="card-body">
            <table style="width:100%;border-collapse:collapse">
              <?php $rows = [
                ['Layanan', $booking['category_name'] . ' — ' . $booking['service_name']],
                ['Paket', $booking['package_name']],
                ['Nama Acara', $booking['event_name'] ?: '-'],
                ['Tanggal Event', date('l, d F Y', strtotime($booking['event_date']))],
                ['Jam Mulai', $booking['event_time'] ? date('H:i', strtotime($booking['event_time'])) : '-'],
                ['Lokasi', $booking['event_location']],
                ['Harga Total', 'Rp ' . number_format($booking['total_price'], 0, ',', '.')],
                ['Uang Muka (DP)', 'Rp ' . number_format($booking['dp_amount'], 0, ',', '.')],
                ['Catatan', $booking['notes'] ?: '-'],
                ['Dibuat', date('d M Y H:i', strtotime($booking['created_at']))],
              ]; foreach($rows as [$k,$v]): ?>
              <tr>
                <td style="padding:10px 0;font-size:0.85rem;color:var(--text-muted);width:180px;vertical-align:top"><?= $k ?></td>
                <td style="padding:10px 0;font-size:0.88rem;font-weight:500"><?= htmlspecialchars($v) ?></td>
              </tr>
              <?php endforeach; ?>
            </table>

            <?php if ($booking['features']): ?>
            <div class="divider"></div>
            <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:12px">Fitur Paket:</div>
            <div class="feature-list">
              <?php foreach($booking['features'] as $f): ?>
              <div class="feature-item"><i class="fas fa-check-circle"></i><?= htmlspecialchars($f) ?></div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($booking['admin_notes']): ?>
            <div class="divider"></div>
            <div style="background:rgba(243,156,18,0.06);border:1px solid rgba(243,156,18,0.2);border-radius:var(--radius);padding:14px">
              <div style="font-size:0.78rem;color:var(--warning);font-weight:700;margin-bottom:4px">Catatan Admin</div>
              <div style="font-size:0.88rem"><?= htmlspecialchars($booking['admin_notes']) ?></div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Timeline -->
        <div class="card">
          <div class="card-header"><h3>Status Timeline</h3></div>
          <div class="card-body">
            <div class="booking-timeline">
              <?php
              $steps = [
                ['REQUESTED','Booking Dikirim','Booking Anda telah diterima sistem','paper-plane'],
                ['APPROVED','Dikonfirmasi Admin','Admin menyetujui booking Anda','check-circle'],
                ['WAITING_VERIFICATION','Pembayaran Dikirim','Bukti DP telah diupload','upload'],
                ['APPROVED','Selesai & Terkonfirmasi','Booking resmi terkonfirmasi','star'],
              ];
              $statuses = ['REQUESTED','APPROVED','WAITING_VERIFICATION'];
              $currentIndex = array_search($booking['status'], ['REQUESTED','WAITING_VERIFICATION','APPROVED','REJECTED','EXPIRED']);
              foreach($steps as $si => [$st,$title,$desc,$icon]):
                $done   = in_array($booking['status'], ['APPROVED']) && $si <= 1;
                $active = ($si === 0 && $booking['status'] === 'REQUESTED') ||
                          ($si === 1 && in_array($booking['status'], ['APPROVED','WAITING_VERIFICATION'])) ||
                          ($si === 2 && $booking['status'] === 'WAITING_VERIFICATION');
              ?>
              <div class="timeline-step">
                <div class="timeline-icon <?= $done ? 'done' : ($active ? 'active' : '') ?>">
                  <i class="fas fa-<?= $icon ?>"></i>
                </div>
                <div class="timeline-content">
                  <h4><?= $title ?></h4>
                  <p><?= $desc ?></p>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Actions -->
      <div>
        <!-- Upload Payment -->
        <?php if (in_array($booking['status'], ['APPROVED', 'WAITING_VERIFICATION'])): ?>
        <div class="card" style="margin-bottom:24px">
          <div class="card-header">
            <h3><i class="fas fa-credit-card" style="color:var(--gold);margin-right:8px"></i>Upload Bukti DP</h3>
          </div>
          <div class="card-body">
            <div style="font-size:0.88rem;color:var(--text-soft);margin-bottom:16px;line-height:1.6">
              Silakan transfer DP sebesar <strong style="color:var(--gold)">Rp <?= number_format($booking['dp_amount'], 0, ',', '.') ?></strong> dan upload bukti transfer di sini.
            </div>

            <?php if ($booking['status'] === 'WAITING_VERIFICATION'): ?>
            <div class="flash flash-warning" style="margin-bottom:0">
              <i class="fas fa-clock"></i> Bukti pembayaran sedang diverifikasi admin
            </div>
            <?php else: ?>
            <form method="POST" action="<?= BASE_URL ?>/booking/<?= $booking['booking_code'] ?>/payment" enctype="multipart/form-data">
              <div class="form-group">
                <label class="upload-area" for="proof_input">
                  <i class="fas fa-cloud-upload-alt"></i>
                  <p><strong>Klik untuk upload</strong> atau drag & drop</p>
                  <p style="font-size:0.78rem;margin-top:4px">JPG, PNG, PDF — Maks 5MB</p>
                  <p class="upload-filename" style="margin-top:8px;color:var(--gold);font-size:0.85rem"></p>
                  <input type="file" id="proof_input" name="proof" accept="image/*,.pdf" style="display:none" required>
                </label>
              </div>
              <button type="submit" class="btn btn-primary" style="width:100%">
                <i class="fas fa-upload"></i> Upload Bukti DP
              </button>
            </form>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Payment History -->
        <?php if ($payments): ?>
        <div class="card" style="margin-bottom:24px">
          <div class="card-header"><h3>Riwayat Pembayaran</h3></div>
          <div class="card-body">
            <?php foreach($payments as $pay): ?>
            <div style="background:var(--dark-4);border-radius:var(--radius);padding:14px;margin-bottom:12px">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                <span style="font-size:0.85rem;font-weight:600">Rp <?= number_format($pay['amount'], 0, ',', '.') ?></span>
                <span class="badge badge-<?= strtolower($pay['status']) ?>"><?= $pay['status'] ?></span>
              </div>
              <div style="font-size:0.78rem;color:var(--text-muted)"><?= date('d M Y H:i', strtotime($pay['created_at'])) ?></div>
              <?php if ($pay['proof_file']): ?>
              <a href="<?= BASE_URL ?>/uploads/payments/<?= htmlspecialchars($pay['proof_file']) ?>" target="_blank" style="font-size:0.78rem;color:var(--gold);margin-top:4px;display:inline-flex;align-items:center;gap:4px">
                <i class="fas fa-eye"></i> Lihat Bukti
              </a>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Quick Info -->
        <div class="card">
          <div class="card-body" style="font-size:0.85rem;color:var(--text-muted);line-height:1.8">
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:12px">
              <i class="fas fa-info-circle" style="color:var(--info);margin-top:3px"></i>
              <span>Butuh bantuan? Hubungi kami via WhatsApp</span>
            </div>
            <a href="https://wa.me/<?= WA_ADMIN_NUMBER ?>" target="_blank" class="btn btn-outline" style="width:100%">
              <i class="fab fa-whatsapp"></i> Chat WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
