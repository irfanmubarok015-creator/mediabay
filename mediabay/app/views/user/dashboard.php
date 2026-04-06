<?php $pageTitle = 'Dashboard'; ?>
<div class="dashboard-layout">
  <!-- Sidebar -->
  <?php require __DIR__ . '/../partials/user_sidebar.php'; ?>

  <!-- Main -->
  <div class="dashboard-main">
    <div class="page-header">
      <h1>Halo, <em><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></em> 👋</h1>
      <p>Selamat datang di dashboard Anda</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <?php
      $total    = count($bookings);
      $approved = count(array_filter($bookings, fn($b) => $b['status'] === 'APPROVED'));
      $pending  = count(array_filter($bookings, fn($b) => in_array($b['status'], ['REQUESTED','WAITING_VERIFICATION'])));
      ?>
      <div class="stat-card">
        <div class="stat-icon stat-icon-gold"><i class="fas fa-calendar"></i></div>
        <div class="stat-value"><?= $total ?></div>
        <div class="stat-label">Total Booking</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon stat-icon-green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value"><?= $approved ?></div>
        <div class="stat-label">Disetujui</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon stat-icon-orange"><i class="fas fa-clock"></i></div>
        <div class="stat-value"><?= $pending ?></div>
        <div class="stat-label">Menunggu</div>
      </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card">
      <div class="card-header">
        <h3>Booking Terbaru</h3>
        <a href="<?= BASE_URL ?>/dashboard/booking" class="btn btn-ghost btn-sm">Lihat Semua</a>
      </div>
      <?php if ($bookings): ?>
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Kode</th><th>Layanan</th><th>Tanggal Event</th><th>Total</th><th>Status</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $statusMap = ['REQUESTED'=>'requested','WAITING_VERIFICATION'=>'waiting','APPROVED'=>'approved','REJECTED'=>'rejected','EXPIRED'=>'expired']; ?>
            <?php $statusLabel = ['REQUESTED'=>'Menunggu','WAITING_VERIFICATION'=>'Verifikasi','APPROVED'=>'Disetujui','REJECTED'=>'Ditolak','EXPIRED'=>'Expired']; ?>
            <?php foreach(array_slice($bookings, 0, 5) as $b): ?>
            <tr>
              <td><strong style="color:var(--gold)"><?= htmlspecialchars($b['booking_code']) ?></strong></td>
              <td>
                <div><?= htmlspecialchars($b['service_name']) ?></div>
                <div style="font-size:0.78rem;color:var(--text-muted)"><?= htmlspecialchars($b['package_name']) ?></div>
              </td>
              <td><?= date('d M Y', strtotime($b['event_date'])) ?></td>
              <td>Rp <?= number_format($b['total_price'], 0, ',', '.') ?></td>
              <td><span class="badge badge-<?= $statusMap[$b['status']] ?>"><?= $statusLabel[$b['status']] ?></span></td>
              <td>
                <a href="<?= BASE_URL ?>/booking/<?= $b['booking_code'] ?>" class="btn btn-ghost btn-sm">Detail</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-calendar-plus"></i>
        <h3>Belum Ada Booking</h3>
        <p>Yuk buat booking pertama Anda!</p>
        <a href="<?= BASE_URL ?>/booking" class="btn btn-primary" style="margin-top:16px">Buat Booking</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
