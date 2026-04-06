<?php

class BookingService {
    private PDO $db;
    private WhatsAppService $wa;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->wa = new WhatsAppService();
    }

    public function createBooking(array $data, int $userId): array {
        // Get package info
        $stmt = $this->db->prepare("
            SELECT p.*, s.name as service_name, c.name as category_name
            FROM packages p
            JOIN services s ON p.service_id = s.id
            JOIN categories c ON s.category_id = c.id
            WHERE p.id = ? AND p.is_active = 1
        ");
        $stmt->execute([$data['package_id']]);
        $package = $stmt->fetch();

        if (!$package) {
            return ['success' => false, 'message' => 'Paket tidak ditemukan'];
        }

        $code = $this->generateCode();
        $dpAmount = $package['price'] * ($package['dp_percentage'] / 100);

        $bookingId = (new BookingModel())->create([
            'booking_code'   => $code,
            'user_id'        => $userId,
            'package_id'     => $data['package_id'],
            'event_date'     => $data['event_date'],
            'event_time'     => $data['event_time'] ?? null,
            'event_location' => $data['event_location'],
            'event_name'     => $data['event_name'] ?? '',
            'notes'          => $data['notes'] ?? '',
            'total_price'    => $package['price'],
            'dp_amount'      => $dpAmount,
            'status'         => 'REQUESTED',
        ]);

        // Get user for WA notification
        $user = (new UserModel())->find($userId);
        $bookingRow = (new BookingModel())->find($bookingId);
        $bookingRow['package_name'] = $package['name'];

        $this->wa->sendBookingCreated($bookingRow, $user);

        return ['success' => true, 'booking_code' => $code, 'booking_id' => $bookingId];
    }

    public function uploadPayment(string $code, array $file, int $userId): array {
        $booking = (new BookingModel())->findBy('booking_code', $code);
        
        if (!$booking || $booking['user_id'] != $userId) {
            return ['success' => false, 'message' => 'Booking tidak ditemukan'];
        }

        if (!in_array($booking['status'], ['APPROVED', 'WAITING_VERIFICATION'])) {
            return ['success' => false, 'message' => 'Status booking tidak memungkinkan upload pembayaran'];
        }

        $uploadResult = $this->uploadFile($file, 'payments');
        if (!$uploadResult['success']) {
            return $uploadResult;
        }

        $db = $this->db;
        $stmt = $db->prepare("INSERT INTO payments (booking_id, amount, payment_type, proof_file, status) VALUES (?,?,?,?,?)");
        $stmt->execute([$booking['id'], $booking['dp_amount'], 'dp', $uploadResult['filename'], 'PENDING']);

        (new BookingModel())->update($booking['id'], ['status' => 'WAITING_VERIFICATION']);

        $user = (new UserModel())->find($userId);
        $this->wa->sendPaymentUploaded($booking, $user);

        return ['success' => true];
    }

    public function approveBooking(int $bookingId, string $adminNotes = ''): array {
        $booking = (new BookingModel())->find($bookingId);
        if (!$booking) return ['success' => false, 'message' => 'Booking tidak ditemukan'];

        (new BookingModel())->update($bookingId, [
            'status'      => 'APPROVED',
            'admin_notes' => $adminNotes,
        ]);

        $user = (new UserModel())->find($booking['user_id']);
        $this->wa->sendBookingApproved($booking, $user);

        return ['success' => true];
    }

    public function rejectBooking(int $bookingId, string $reason = ''): array {
        $booking = (new BookingModel())->find($bookingId);
        if (!$booking) return ['success' => false, 'message' => 'Booking tidak ditemukan'];

        (new BookingModel())->update($bookingId, [
            'status'      => 'REJECTED',
            'admin_notes' => $reason,
        ]);

        $user = (new UserModel())->find($booking['user_id']);
        $this->wa->sendBookingRejected($booking, $user, $reason);

        return ['success' => true];
    }

    public function uploadFile(array $file, string $folder): array {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Upload gagal'];
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'File terlalu besar (max 5MB)'];
        }

        $allowedTypes = array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_VIDEO_TYPES);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $destDir  = UPLOAD_PATH . $folder . '/';

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $destDir . $filename)) {
            return ['success' => false, 'message' => 'Gagal menyimpan file'];
        }

        return ['success' => true, 'filename' => $filename, 'mime' => $mimeType];
    }

    private function generateCode(): string {
        do {
            $code = 'MB' . date('ymd') . strtoupper(substr(uniqid(), -4));
        } while ((new BookingModel())->findBy('booking_code', $code));
        return $code;
    }
}
