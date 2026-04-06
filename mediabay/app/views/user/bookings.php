<?php $pageTitle = 'Riwayat Booking'; ?>
<div class="dashboard-layout">
  <?php require __DIR__ . '/../partials/user_sidebar.php'; ?>
  <div class="dashboard-main">
    <div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
      <div>
        <h1>Riwayat <em>Booking</em></h1>
        <p>Semua booking yang pernah Anda buat</p>
      </div>
      <a href="<?= BASE_URL ?>/booking" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Booking Baru
      </a>
    </div>

    <?php if ($bookings): ?>
    <div style="display:flex;flex-direction:column;gap:16px">
      <?php
      $sm = ['REQUESTED'=>'requested','WAITING_VERIFICATION'=>'waiting','APPROVED'=>'approved','REJECTED'=>'rejected','EXPIRED'=>'expired'];
      $sl = ['REQUESTED'=>'Menunggu Konfirmasi','WAITING_VERIFICATION'=>'Menunggu Verifikasi','APPROVED'=>'Disetujui','REJECTED'=>'Ditolak','EXPIRED'=>'Kadaluarsa'];
      foreach($bookings as $b):
      ?>
      <div class="card">
        <div style="padding:20px 24px;display:grid;grid-template-columns:1fr auto;gap:16px;align-items:start">
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px">
            <div>
              <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Kode Booking</div>
              <div style="color:var(--gold);font-family:var(--font-display);font-weight:700;font-size:1.05rem"><?= htmlspecialchars($b['booking_code']) ?></div>
            </div>
            <div>
              <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Layanan</div>
              <div style="font-size:0.9rem;font-weight:600"><?= htmlspecialchars($b['service_name']) ?></div>
              <div style="font-size:0.78rem;color:var(--text-muted)"><?= htmlspecialchars($b['package_name']) ?></div>
            </div>
            <div>
              <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Tanggal Event</div>
              <div style="font-size:0.9rem"><?= date('d M Y', strtotime($b['event_date'])) ?></div>
            </div>
            <div>
              <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Total Harga</div>
              <div style="font-size:0.9rem;font-weight:600">Rp <?= number_format($b['total_price'],0,',','.') ?></div>
              <div style="font-size:0.75rem;color:var(--text-muted)">DP: Rp <?= number_format($b['dp_amount'],0,',','.') ?></div>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px">
            <span class="badge badge-<?= $sm[$b['status']] ?>"><?= $sl[$b['status']] ?></span>
            <a href="<?= BASE_URL ?>/booking/<?= $b['booking_code'] ?>" class="btn btn-ghost btn-sm">
              <i class="fas fa-eye"></i> Detail
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
      <i class="fas fa-calendar-plus"></i>
      <h3>Belum Ada Booking</h3>
      <p>Anda belum pernah membuat booking. Mulai booking sekarang!</p>
      <a href="<?= BASE_URL ?>/booking" class="btn btn-primary" style="margin-top:20px">
        <i class="fas fa-plus"></i> Buat Booking Pertama
      </a>
    </div>
    <?php endif; ?>
  </div>
</div>
