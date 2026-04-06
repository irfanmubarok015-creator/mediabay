<?php $pageTitle = 'Manajemen Booking'; ?>

<div class="admin-page-header">
  <div class="admin-page-header-left">
    <h1>Manajemen <em>Booking</em></h1>
    <p>Approve atau reject request booking dari klien</p>
  </div>
</div>

<?php if (!empty($flash)): ?>
<div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
  <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?>"></i>
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- Filter -->
<div class="filter-bar">
  <span>Filter:</span>
  <?php $filters=[''=>'Semua','REQUESTED'=>'Pending','WAITING_VERIFICATION'=>'Verifikasi','APPROVED'=>'Approved','REJECTED'=>'Rejected']; ?>
  <?php foreach($filters as $val => $lbl): ?>
  <a href="<?= BASE_URL ?>/admin/bookings<?= $val?'?status='.$val:'' ?>"
     class="btn btn-sm <?= $status===$val?'btn-primary':'btn-ghost' ?>">
    <?= $lbl ?>
  </a>
  <?php endforeach; ?>
</div>

<div class="admin-card">
  <div class="table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Kode</th><th>Klien</th><th>Layanan / Paket</th><th>Tanggal Event</th><th>Total</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($bookings)): ?>
        <tr><td colspan="8" style="text-align:center;padding:48px;color:var(--text-muted)">
          <i class="fas fa-calendar-times" style="font-size:2rem;display:block;margin-bottom:10px"></i>
          Tidak ada booking<?= $status?' dengan status '.$status:'' ?>
        </td></tr>
        <?php endif; ?>
        <?php
        $sm = ['REQUESTED'=>'requested','WAITING_VERIFICATION'=>'waiting','APPROVED'=>'approved','REJECTED'=>'rejected','EXPIRED'=>'expired'];
        $sl = ['REQUESTED'=>'Pending','WAITING_VERIFICATION'=>'Verifikasi','APPROVED'=>'Approved','REJECTED'=>'Rejected','EXPIRED'=>'Expired'];
        foreach($bookings as $b):
        ?>
        <tr>
          <td><span class="td-code"><?= htmlspecialchars($b['booking_code']) ?></span></td>
          <td>
            <div class="td-title"><?= htmlspecialchars($b['user_name']) ?></div>
            <div class="td-sub"><?= htmlspecialchars($b['user_phone']) ?></div>
            <div class="td-sub"><?= htmlspecialchars($b['user_email']) ?></div>
          </td>
          <td>
            <div class="td-title"><?= htmlspecialchars($b['service_name']) ?></div>
            <div class="td-sub"><?= htmlspecialchars($b['package_name']) ?></div>
          </td>
          <td style="white-space:nowrap"><?= date('d M Y', strtotime($b['event_date'])) ?></td>
          <td style="white-space:nowrap">
            <span style="color:var(--gold);font-weight:700">Rp <?= number_format($b['total_price'],0,',','.') ?></span>
          </td>
          <td><span class="badge badge-<?= $sm[$b['status']] ?>"><?= $sl[$b['status']] ?></span></td>
          <td style="white-space:nowrap;color:var(--text-muted);font-size:0.78rem"><?= date('d M Y', strtotime($b['created_at'])) ?></td>
          <td>
            <div class="td-actions">
              <a href="<?= BASE_URL ?>/booking/<?= htmlspecialchars($b['booking_code']) ?>" target="_blank" class="btn btn-ghost btn-sm">Detail</a>
              <?php if ($b['status']==='REQUESTED'): ?>
              <button class="btn btn-success btn-sm" data-modal-open="modal-approve-<?= $b['id'] ?>">
                <i class="fas fa-check"></i>
              </button>
              <button class="btn btn-danger btn-sm"  data-modal-open="modal-reject-<?= $b['id'] ?>">
                <i class="fas fa-times"></i>
              </button>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Approve/Reject Modals -->
<?php foreach($bookings as $b): if ($b['status']!=='REQUESTED') continue; ?>

<div class="modal-overlay" id="modal-approve-<?= $b['id'] ?>">
  <div class="modal">
    <div class="modal-header">
      <h3><i class="fas fa-check-circle" style="color:var(--success)"></i> Approve Booking</h3>
      <button class="modal-close"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div style="background:var(--bg-4);border-radius:var(--radius);padding:14px;margin-bottom:18px;font-size:0.88rem">
        <div><strong><?= htmlspecialchars($b['booking_code']) ?></strong> — <?= htmlspecialchars($b['user_name']) ?></div>
        <div style="color:var(--text-muted);margin-top:4px"><?= htmlspecialchars($b['service_name']) ?> · <?= date('d M Y',strtotime($b['event_date'])) ?></div>
      </div>
      <form method="POST" action="<?= BASE_URL ?>/admin/bookings/<?= $b['id'] ?>/approve" id="approveForm<?= $b['id'] ?>">
        <div class="form-group">
          <label class="form-label">Catatan untuk Klien (opsional)</label>
          <textarea name="admin_notes" class="form-control" rows="3" placeholder="Contoh: Silakan lakukan pembayaran DP untuk konfirmasi slot..."></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="approveForm<?= $b['id'] ?>" class="btn btn-success">
        <i class="fas fa-check"></i> Ya, Setujui
      </button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal-reject-<?= $b['id'] ?>">
  <div class="modal">
    <div class="modal-header">
      <h3><i class="fas fa-times-circle" style="color:var(--danger)"></i> Tolak Booking</h3>
      <button class="modal-close"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div style="background:var(--bg-4);border-radius:var(--radius);padding:14px;margin-bottom:18px;font-size:0.88rem">
        <div><strong><?= htmlspecialchars($b['booking_code']) ?></strong> — <?= htmlspecialchars($b['user_name']) ?></div>
        <div style="color:var(--text-muted);margin-top:4px"><?= htmlspecialchars($b['service_name']) ?> · <?= date('d M Y',strtotime($b['event_date'])) ?></div>
      </div>
      <form method="POST" action="<?= BASE_URL ?>/admin/bookings/<?= $b['id'] ?>/reject" id="rejectForm<?= $b['id'] ?>">
        <div class="form-group">
          <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
          <textarea name="reason" class="form-control" rows="3" required
                    placeholder="Contoh: Tanggal tersebut sudah penuh, silakan pilih tanggal lain..."></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost modal-close">Batal</button>
      <button type="submit" form="rejectForm<?= $b['id'] ?>" class="btn btn-danger">
        <i class="fas fa-times"></i> Ya, Tolak
      </button>
    </div>
  </div>
</div>

<?php endforeach; ?>
