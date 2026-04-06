<?php $pageTitle = 'Verifikasi Pembayaran'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Verifikasi <em>Pembayaran</em></h1>
    <p>Periksa dan verifikasi bukti DP yang dikirim klien</p>
  </div>
</div>

<?php if (!empty($flash)): ?>
<div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
  <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?>"></i>
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<?php if (empty($payments)): ?>
<div class="admin-card">
  <div class="admin-card-body">
    <div class="empty-state">
      <i class="fas fa-check-double" style="color:var(--success)"></i>
      <h3>Semua Terverifikasi</h3>
      <p>Tidak ada pembayaran yang menunggu verifikasi saat ini</p>
    </div>
  </div>
</div>
<?php else: ?>

<div class="admin-card" style="margin-bottom:16px;padding:12px 18px">
  <div style="display:flex;align-items:center;gap:10px;font-size:0.83rem;color:var(--text-muted)">
    <i class="fas fa-info-circle" style="color:var(--warning)"></i>
    <?= count($payments) ?> pembayaran menunggu verifikasi. Periksa bukti transfer sebelum verifikasi.
  </div>
</div>

<div class="admin-card">
  <div class="table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Kode Booking</th><th>Klien</th><th>Paket</th><th>Jumlah DP</th><th>Bukti Transfer</th><th>Tanggal Upload</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php foreach($payments as $p): ?>
        <tr>
          <td>
            <span class="td-code"><?= htmlspecialchars($p['booking_code']) ?></span>
            <div class="td-sub"><?= date('d M Y', strtotime($p['event_date'])) ?></div>
          </td>
          <td>
            <div class="td-title"><?= htmlspecialchars($p['user_name']) ?></div>
            <div class="td-sub"><?= htmlspecialchars($p['user_phone']) ?></div>
          </td>
          <td class="td-sub"><?= htmlspecialchars($p['package_name']) ?></td>
          <td>
            <span style="color:var(--gold);font-weight:700;font-size:0.92rem">
              Rp <?= number_format($p['amount'],0,',','.') ?>
            </span>
          </td>
          <td>
            <?php if ($p['proof_file']): ?>
            <a href="<?= BASE_URL ?>/uploads/payments/<?= htmlspecialchars($p['proof_file']) ?>"
               target="_blank" class="btn btn-outline btn-sm">
              <i class="fas fa-eye"></i> Lihat Bukti
            </a>
            <?php else: ?>
            <span style="color:var(--text-muted);font-size:0.8rem">Tidak ada file</span>
            <?php endif; ?>
          </td>
          <td style="white-space:nowrap;color:var(--text-muted);font-size:0.78rem">
            <?= date('d M Y H:i', strtotime($p['created_at'])) ?>
          </td>
          <td>
            <form method="POST" action="<?= BASE_URL ?>/admin/payments/<?= $p['id'] ?>/verify">
              <button type="submit" class="btn btn-success btn-sm"
                      data-confirm="Verifikasi pembayaran Rp <?= number_format($p['amount'],0,',','.') ?> dari <?= htmlspecialchars($p['user_name']) ?>?">
                <i class="fas fa-check"></i> Verifikasi
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>
