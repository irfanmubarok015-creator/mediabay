<?php $pageTitle = 'Dashboard'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Dashboard</h1>
    <p>Selamat datang, <?= htmlspecialchars(explode(' ', $_SESSION['name']??'Admin')[0]) ?>. Berikut ringkasan terkini.</p>
  </div>
  <div class="admin-page-header-actions">
    <a href="<?= BASE_URL ?>/admin/bookings?status=REQUESTED" class="btn btn-outline btn-sm">
      <i class="fas fa-clock"></i> Booking Pending
      <?php if ($stats['pending'] > 0): ?>
      <span style="background:var(--danger);color:#fff;border-radius:100px;padding:1px 7px;font-size:0.68rem;font-weight:800;margin-left:4px"><?= $stats['pending'] ?></span>
      <?php endif; ?>
    </a>
  </div>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon stat-icon-gold"><i class="fas fa-calendar-check"></i></div>
    <div class="stat-value"><?= $stats['total_bookings'] ?></div>
    <div class="stat-label">Total Booking</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-orange"><i class="fas fa-hourglass-half"></i></div>
    <div class="stat-value"><?= $stats['pending'] ?></div>
    <div class="stat-label">Menunggu Konfirmasi</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-green"><i class="fas fa-check-circle"></i></div>
    <div class="stat-value"><?= $stats['approved'] ?></div>
    <div class="stat-label">Disetujui</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-blue"><i class="fas fa-credit-card"></i></div>
    <div class="stat-value"><?= $stats['pending_payment'] ?></div>
    <div class="stat-label">Verifikasi Pembayaran</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-red"><i class="fas fa-users"></i></div>
    <div class="stat-value"><?= $stats['total_users'] ?></div>
    <div class="stat-label">Total User</div>
  </div>
</div>

<!-- Quick Actions -->
<div style="display:flex;gap:10px;margin-bottom:28px;flex-wrap:wrap">
  <a href="<?= BASE_URL ?>/admin/bookings?status=REQUESTED" class="btn btn-ghost btn-sm">
    <i class="fas fa-clock" style="color:var(--warning)"></i> Booking Pending (<?= $stats['pending'] ?>)
  </a>
  <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-ghost btn-sm">
    <i class="fas fa-credit-card" style="color:var(--info)"></i> Verifikasi Bayar (<?= $stats['pending_payment'] ?>)
  </a>
</div>

<!-- Recent Bookings -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3><i class="fas fa-calendar-alt"></i> Booking Terbaru</h3>
    <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-ghost btn-sm">Lihat Semua</a>
  </div>
  <div class="table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Klien</th>
          <th>Layanan</th>
          <th>Tanggal Event</th>
          <th>Total</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentBookings)): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">Belum ada booking</td></tr>
        <?php endif; ?>
        <?php
        $sm = ['REQUESTED'=>'requested','WAITING_VERIFICATION'=>'waiting','APPROVED'=>'approved','REJECTED'=>'rejected','EXPIRED'=>'expired'];
        $sl = ['REQUESTED'=>'Requested','WAITING_VERIFICATION'=>'Verifikasi','APPROVED'=>'Approved','REJECTED'=>'Rejected','EXPIRED'=>'Expired'];
        foreach($recentBookings as $b):
        ?>
        <tr>
          <td><span class="td-code"><?= htmlspecialchars($b['booking_code']) ?></span></td>
          <td>
            <div class="td-title"><?= htmlspecialchars($b['user_name']) ?></div>
            <div class="td-sub"><?= htmlspecialchars($b['user_phone']) ?></div>
          </td>
          <td>
            <div class="td-title"><?= htmlspecialchars($b['service_name']) ?></div>
            <div class="td-sub"><?= htmlspecialchars($b['package_name']) ?></div>
          </td>
          <td style="white-space:nowrap"><?= date('d M Y', strtotime($b['event_date'])) ?></td>
          <td style="white-space:nowrap;color:var(--gold);font-weight:600">
            Rp <?= number_format($b['total_price'],0,',','.') ?>
          </td>
          <td><span class="badge badge-<?= $sm[$b['status']] ?>"><?= $sl[$b['status']] ?></span></td>
          <td>
            <div class="td-actions">
              <a href="<?= BASE_URL ?>/booking/<?= htmlspecialchars($b['booking_code']) ?>" target="_blank" class="btn btn-ghost btn-sm">Detail</a>
              <?php if ($b['status'] === 'REQUESTED'): ?>
              <form method="POST" action="<?= BASE_URL ?>/admin/bookings/<?= $b['id'] ?>/approve">
                <button type="submit" class="btn btn-success btn-sm">✓ Approve</button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
