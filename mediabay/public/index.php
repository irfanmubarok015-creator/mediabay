<?php
// ============================================================
// MEDIABAY — Entry Point
// ============================================================

// ── ERROR REPORTING ───────────────────────────────────────────
// DEVELOPMENT: set 1 untuk lihat error. PRODUCTION: set 0
define('MEDIABAY_DEBUG', true);

if (MEDIABAY_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ── SESSION ───────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── LOAD CONFIG ───────────────────────────────────────────────
require_once __DIR__ . '/../config/database.php';

// ── LOAD CORE ─────────────────────────────────────────────────
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

// ── LOAD MODELS ───────────────────────────────────────────────
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/Models.php';

// ── LOAD SERVICES ─────────────────────────────────────────────
require_once __DIR__ . '/../app/services/WhatsAppService.php';
require_once __DIR__ . '/../app/services/BookingService.php';

// ── LOAD CONTROLLERS ──────────────────────────────────────────
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/Controllers.php';

// ── DETECT BASE PATH ──────────────────────────────────────────
// SCRIPT_NAME = /mediabay/public/index.php → basePath = /mediabay/public
// SCRIPT_NAME = /index.php                 → basePath = (kosong)
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath  = ($scriptDir === '/' || $scriptDir === '\\') ? '' : rtrim($scriptDir, '/');

// ── BOOT ROUTER ───────────────────────────────────────────────
$router = new Router($basePath);
require_once __DIR__ . '/../routes/web.php';
$router->dispatch();
