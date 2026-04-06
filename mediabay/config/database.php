<?php

// ── DATABASE ──────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'mediabay');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// ── BASE URL (auto-detect, tidak perlu diubah manual) ─────────
// Mendeteksi otomatis apakah di root, subfolder, HTTP atau HTTPS
if (!defined('BASE_URL')) {
    $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // SCRIPT_NAME = /mediabay/public/index.php → dir = /mediabay/public
    $dir      = dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $dir      = ($dir === '/' || $dir === '\\') ? '' : rtrim($dir, '/');
    define('BASE_URL', $scheme . '://' . $host . $dir);
}

// ── APP ───────────────────────────────────────────────────────
define('APP_NAME',    'Mediabay');
define('APP_VERSION', '1.0.0');

// ── WHATSAPP (Fonnte API) ─────────────────────────────────────
define('WA_API_URL',      'https://api.fonnte.com/send');
define('WA_TOKEN',        'YOUR_FONNTE_TOKEN');    // Ganti dengan token Fonnte Anda
define('WA_ADMIN_NUMBER', '628xxxxxxxxxx');         // Ganti dengan nomor WA admin

// ── UPLOAD ────────────────────────────────────────────────────
define('MAX_FILE_SIZE',       5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm', 'video/quicktime']);
define('UPLOAD_PATH',         __DIR__ . '/../public/uploads/');
