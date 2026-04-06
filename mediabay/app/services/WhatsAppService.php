<?php

class WhatsAppService {
    private string $apiUrl;
    private string $token;
    private string $adminPhone;

    public function __construct() {
        $this->apiUrl   = WA_API_URL;
        $this->token    = WA_TOKEN;
        $this->adminPhone = 628123456789;
    }

    public function sendBookingCreated(array $booking, array $user): void {
        // Notify user
        $msgUser = "✅ *Booking Berhasil Dibuat - Mediabay*\n\n"
            . "Halo {$user['name']}, booking Anda telah diterima!\n\n"
            . "📋 *Detail Booking:*\n"
            . "Kode: *{$booking['booking_code']}*\n"
            . "Layanan: {$booking['package_name']}\n"
            . "Tanggal: " . date('d F Y', strtotime($booking['event_date'])) . "\n"
            . "Lokasi: {$booking['event_location']}\n"
            . "Total: Rp " . number_format($booking['total_price'], 0, ',', '.') . "\n"
            . "DP: Rp " . number_format($booking['dp_amount'], 0, ',', '.') . "\n\n"
            . "⏳ Status: *MENUNGGU KONFIRMASI ADMIN*\n\n"
            . "⚠️ _Booking akan dikonfirmasi oleh admin. Slot belum dijamin hingga disetujui._\n\n"
            . "Silakan upload bukti DP setelah booking disetujui.\n"
            . "Info: " . BASE_URL . "/booking/{$booking['booking_code']}";

        $this->send($user['phone'], $msgUser, 'booking_created_user');

        // Notify admin
        $msgAdmin = "🔔 *Booking Baru Masuk - Mediabay*\n\n"
            . "Dari: {$user['name']} ({$user['phone']})\n"
            . "Kode: *{$booking['booking_code']}*\n"
            . "Layanan: {$booking['package_name']}\n"
            . "Tanggal: " . date('d F Y', strtotime($booking['event_date'])) . "\n"
            . "Lokasi: {$booking['event_location']}\n"
            . "Total: Rp " . number_format($booking['total_price'], 0, ',', '.') . "\n\n"
            . "Silakan cek admin panel untuk approve/reject.\n"
            . BASE_URL . "/admin/bookings";

        $this->send($this->adminPhone, $msgAdmin, 'booking_created_admin');
    }

    public function sendPaymentUploaded(array $booking, array $user): void {
        $msgUser = "📤 *Bukti Pembayaran Diterima - Mediabay*\n\n"
            . "Halo {$user['name']}, bukti pembayaran Anda telah kami terima.\n\n"
            . "Kode Booking: *{$booking['booking_code']}*\n"
            . "Status: *MENUNGGU VERIFIKASI*\n\n"
            . "Admin akan memverifikasi dalam 1x24 jam. Terima kasih! 🙏";

        $this->send($user['phone'], $msgUser, 'payment_uploaded');

        $msgAdmin = "💰 *Bukti Pembayaran Baru - Mediabay*\n\n"
            . "Dari: {$user['name']}\n"
            . "Kode: *{$booking['booking_code']}*\n\n"
            . "Silakan verifikasi di admin panel.\n"
            . BASE_URL . "/admin/payments";

        $this->send($this->adminPhone, $msgAdmin, 'payment_uploaded_admin');
    }

    public function sendBookingApproved(array $booking, array $user): void {
        $msg = "🎉 *Booking Disetujui! - Mediabay*\n\n"
            . "Selamat {$user['name']}! Booking Anda telah *DISETUJUI*.\n\n"
            . "📋 *Detail:*\n"
            . "Kode: *{$booking['booking_code']}*\n"
            . "Tanggal: " . date('d F Y', strtotime($booking['event_date'])) . "\n"
            . "Lokasi: {$booking['event_location']}\n\n"
            . "💳 Silakan upload bukti DP untuk mengkonfirmasi booking Anda.\n"
            . "DP: Rp " . number_format($booking['dp_amount'], 0, ',', '.') . "\n\n"
            . "Upload di: " . BASE_URL . "/booking/{$booking['booking_code']}";

        $this->send($user['phone'], $msg, 'booking_approved');
    }

    public function sendBookingRejected(array $booking, array $user, string $reason = ''): void {
        $msg = "❌ *Booking Ditolak - Mediabay*\n\n"
            . "Halo {$user['name']}, mohon maaf booking Anda tidak dapat kami terima.\n\n"
            . "Kode: *{$booking['booking_code']}*\n"
            . ($reason ? "Alasan: {$reason}\n\n" : "\n")
            . "Silakan hubungi kami untuk informasi lebih lanjut atau booking ulang.\n"
            . "WhatsApp: " . $this->adminPhone;

        $this->send($user['phone'], $msg, 'booking_rejected');
    }

    public function sendReminderH1(array $booking, array $user): void {
        $msg = "⏰ *Reminder - Mediabay*\n\n"
            . "Halo {$user['name']},\n\n"
            . "Mengingatkan bahwa event Anda *besok*! 🎊\n\n"
            . "Kode: *{$booking['booking_code']}*\n"
            . "Tanggal: " . date('d F Y', strtotime($booking['event_date'])) . "\n"
            . "Lokasi: {$booking['event_location']}\n\n"
            . "Tim kami akan hadir tepat waktu. Sampai jumpa! 🙏";

        $this->send($user['phone'], $msg, 'reminder_h1');
    }

    private function send(string $phone, string $message, string $type): void {
        if (empty($phone) || $this->token === 'YOUR_FONNTE_TOKEN') {
            $this->log($phone, $message, $type, 'failed');
            return;
        }

        try {
            $ch = curl_init($this->apiUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query([
                    'target'  => $phone,
                    'message' => $message,
                ]),
                CURLOPT_HTTPHEADER => ["Authorization: {$this->token}"],
                CURLOPT_TIMEOUT    => 10,
            ]);

            $response = curl_exec($ch);
            $error    = curl_error($ch);
            curl_close($ch);

            $status = ($response && !$error) ? 'sent' : 'failed';
            $this->log($phone, $message, $type, $status);

        } catch (Exception $e) {
            $this->log($phone, $message, $type, 'failed');
        }
    }

    private function log(string $phone, string $message, string $type, string $status): void {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO wa_logs (phone, message, type, status) VALUES (?,?,?,?)");
            $stmt->execute([$phone, $message, $type, $status]);
        } catch (Exception $e) {
            // Silent fail
        }
    }
}
