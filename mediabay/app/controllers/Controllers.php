<?php

class ServiceController extends Controller {
    public function index(): void {
        $categories = (new CategoryModel())->getWithServices();
        $services   = (new ServiceModel())->getAllWithCategory();
        $this->view('home/services', compact('categories', 'services'));
    }

    public function show(string $slug): void {
        $category = (new CategoryModel())->findBySlug($slug);
        if (!$category) { http_response_code(404); return; }

        $services = (new ServiceModel())->getByCategory($category['id']);
        $this->view('home/service_detail', compact('category', 'services'));
    }

    public function package(string $id): void {
        $package = (new PackageModel())->getWithService((int)$id);
        if (!$package) { http_response_code(404); return; }
        $package['features'] = json_decode($package['features'] ?? '[]', true);
        $this->view('home/package', compact('package'));
    }
}

class BookingController extends Controller {
    private BookingService $bookingService;

    public function __construct() {
        $this->bookingService = new BookingService();
    }

    public function index(): void {
        $this->requireAuth();
        $categories = (new CategoryModel())->getWithServices();
        $services   = (new ServiceModel())->getAllWithCategory();
        $flash      = $this->getFlash();
        $this->view('home/booking', compact('categories', 'services', 'flash'));
    }

    public function store(): void {
        $this->requireAuth();

        $data = [
            'package_id'     => (int)$this->input('package_id'),
            'event_date'     => $this->sanitize($this->input('event_date', '')),
            'event_time'     => $this->sanitize($this->input('event_time', '')),
            'event_location' => $this->sanitize($this->input('event_location', '')),
            'event_name'     => $this->sanitize($this->input('event_name', '')),
            'notes'          => $this->sanitize($this->input('notes', '')),
        ];

        if (!$data['package_id'] || !$data['event_date'] || !$data['event_location']) {
            $this->flash('error', 'Paket, tanggal, dan lokasi event wajib diisi');
            $this->redirect('/booking');
            return;
        }

        $result = $this->bookingService->createBooking($data, $_SESSION['user_id']);

        if ($result['success']) {
            $this->flash('success', 'Booking berhasil dibuat! Kode booking: ' . $result['booking_code']);
            $this->redirect('/booking/' . $result['booking_code']);
        } else {
            $this->flash('error', $result['message']);
            $this->redirect('/booking');
        }
    }

    public function show(string $code): void {
        $this->requireAuth();
        $booking = (new BookingModel())->getDetail($code);

        if (!$booking) { http_response_code(404); return; }
        if ($booking['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
            $this->redirect('/dashboard');
            return;
        }

        $payments = (new PaymentModel())->getByBooking($booking['id']);
        $flash    = $this->getFlash();
        $booking['features'] = json_decode($booking['features'] ?? '[]', true);
        $this->view('home/booking_detail', compact('booking', 'payments', 'flash'));
    }

    public function uploadPayment(string $code): void {
        $this->requireAuth();

        if (!isset($_FILES['proof']) || $_FILES['proof']['error'] === UPLOAD_ERR_NO_FILE) {
            $this->flash('error', 'File bukti pembayaran harus diunggah');
            $this->redirect('/booking/' . $code);
            return;
        }

        $result = $this->bookingService->uploadPayment($code, $_FILES['proof'], $_SESSION['user_id']);

        if ($result['success']) {
            $this->flash('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
        } else {
            $this->flash('error', $result['message']);
        }
        $this->redirect('/booking/' . $code);
    }
}

class UserController extends Controller {
    public function dashboard(): void {
        $this->requireAuth();
        $bookings = (new BookingModel())->getByUser($_SESSION['user_id']);
        $user     = (new UserModel())->find($_SESSION['user_id']);
        $this->view('user/dashboard', compact('bookings', 'user'));
    }

    public function profile(): void {
        $this->requireAuth();
        $user  = (new UserModel())->find($_SESSION['user_id']);
        $flash = $this->getFlash();
        $this->view('user/profile', compact('user', 'flash'));
    }

    public function updateProfile(): void {
        $this->requireAuth();
        $name  = $this->sanitize($this->input('name', ''));
        $phone = $this->sanitize($this->input('phone', ''));

        if (!$name) {
            $this->flash('error', 'Nama tidak boleh kosong');
            $this->redirect('/dashboard/profil');
            return;
        }

        $data = ['name' => $name, 'phone' => $phone];

        // Handle avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $bs     = new BookingService();
            $result = $bs->uploadFile($_FILES['avatar'], 'avatars');
            if ($result['success']) {
                $data['avatar'] = $result['filename'];
                $_SESSION['avatar'] = $result['filename'];
            }
        }

        (new UserModel())->update($_SESSION['user_id'], $data);
        $_SESSION['name'] = $name;
        $this->flash('success', 'Profil berhasil diperbarui');
        $this->redirect('/dashboard/profil');
    }


    public function relations(): void {
        $this->requireAdmin();
        $db = Database::getInstance();

        // Build full tree: Category → Services → Packages
        $categories = (new CategoryModel())->all('sort_order');
        $tree = [];
        foreach ($categories as $cat) {
            $services = $db->prepare("SELECT * FROM services WHERE category_id=? ORDER BY name");
            $services->execute([$cat['id']]);
            $svcs = $services->fetchAll();

            foreach ($svcs as &$svc) {
                $pkgs = $db->prepare("SELECT * FROM packages WHERE service_id=? ORDER BY price ASC");
                $pkgs->execute([$svc['id']]);
                $svc['packages'] = $pkgs->fetchAll();
            }
            unset($svc);

            $cat['services'] = $svcs;
            $tree[] = $cat;
        }

        $this->view('admin/relations', compact('tree'), 'admin');
    }

    public function bookings(): void {
        $this->requireAuth();
        $bookings = (new BookingModel())->getByUser($_SESSION['user_id']);
        $this->view('user/bookings', compact('bookings'));
    }
}

class AdminController extends Controller {
    public function dashboard(): void {
        $this->requireAdmin();
        $db = Database::getInstance();

        $stats = [
            'total_bookings'  => (int)$db->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
            'pending'         => (int)$db->query("SELECT COUNT(*) FROM bookings WHERE status='REQUESTED'")->fetchColumn(),
            'approved'        => (int)$db->query("SELECT COUNT(*) FROM bookings WHERE status='APPROVED'")->fetchColumn(),
            'pending_payment' => (int)$db->query("SELECT COUNT(*) FROM payments WHERE status='PENDING'")->fetchColumn(),
            'total_users'     => (int)$db->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn(),
        ];

        $recentBookings = (new BookingModel())->getAllWithDetails();
        $recentBookings = array_slice($recentBookings, 0, 10);

        $this->view('admin/dashboard', compact('stats', 'recentBookings'), 'admin');
    }

    public function bookings(): void {
        $this->requireAdmin();
        $status   = $this->input('status', '');
        $bookings = (new BookingModel())->getAllWithDetails($status);
        $flash    = $this->getFlash();
        $this->view('admin/bookings', compact('bookings', 'status', 'flash'), 'admin');
    }

    public function approveBooking(string $id): void {
        $this->requireAdmin();
        $notes  = $this->sanitize($this->input('admin_notes', ''));
        $result = (new BookingService())->approveBooking((int)$id, $notes);
        $this->flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Booking disetujui' : $result['message']);
        $this->redirect('/admin/bookings');
    }

    public function rejectBooking(string $id): void {
        $this->requireAdmin();
        $reason = $this->sanitize($this->input('reason', ''));
        $result = (new BookingService())->rejectBooking((int)$id, $reason);
        $this->flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Booking ditolak' : $result['message']);
        $this->redirect('/admin/bookings');
    }

    public function payments(): void {
        $this->requireAdmin();
        $payments = (new PaymentModel())->getPendingWithBooking();
        $flash    = $this->getFlash();
        $this->view('admin/payments', compact('payments', 'flash'), 'admin');
    }

    public function verifyPayment(string $id): void {
        $this->requireAdmin();
        $paymentId = (int)$id;
        $db        = Database::getInstance();

        $stmt = $db->prepare("UPDATE payments SET status='VERIFIED', verified_at=NOW(), verified_by=? WHERE id=?");
        $stmt->execute([$_SESSION['user_id'], $paymentId]);

        $this->flash('success', 'Pembayaran berhasil diverifikasi');
        $this->redirect('/admin/payments');
    }

    public function categories(): void {
        $this->requireAdmin();
        $categories = (new CategoryModel())->all();
        $flash      = $this->getFlash();
        $this->view('admin/categories', compact('categories', 'flash'), 'admin');
    }

    public function storeCategory(): void {
        $this->requireAdmin();
        $name = $this->sanitize($this->input('name', ''));
        $slug = $this->sanitize($this->input('slug', '')) ?: strtolower(str_replace(' ', '-', $name));
        $desc = $this->sanitize($this->input('description', ''));

        if (!$name) {
            $this->flash('error', 'Nama kategori harus diisi');
            $this->redirect('/admin/categories');
            return;
        }

        (new CategoryModel())->create(['name' => $name, 'slug' => $slug, 'description' => $desc]);
        $this->flash('success', 'Kategori berhasil ditambahkan');
        $this->redirect('/admin/categories');
    }

    public function updateCategory(string $id): void {
        $this->requireAdmin();
        $name   = $this->sanitize($this->input('name', ''));
        $desc   = $this->sanitize($this->input('description', ''));
        $active = (int)$this->input('is_active', 1);
        (new CategoryModel())->update((int)$id, ['name' => $name, 'description' => $desc, 'is_active' => $active]);
        $this->flash('success', 'Kategori diperbarui');
        $this->redirect('/admin/categories');
    }

    public function deleteCategory(string $id): void {
        $this->requireAdmin();
        (new CategoryModel())->delete((int)$id);
        $this->flash('success', 'Kategori dihapus');
        $this->redirect('/admin/categories');
    }

    public function services(): void {
        $this->requireAdmin();
        $services   = (new ServiceModel())->getAllWithCategory();
        $categories = (new CategoryModel())->getActive();
        $flash      = $this->getFlash();
        $this->view('admin/services', compact('services', 'categories', 'flash'), 'admin');
    }

    public function storeService(): void {
        $this->requireAdmin();
        $name    = $this->sanitize($this->input('name', ''));
        $catId   = (int)$this->input('category_id');
        $desc    = $this->sanitize($this->input('description', ''));
        $slug    = strtolower(str_replace(' ', '-', $name)) . '-' . time();

        (new ServiceModel())->create(['category_id' => $catId, 'name' => $name, 'slug' => $slug, 'description' => $desc]);
        $this->flash('success', 'Layanan berhasil ditambahkan');
        $this->redirect('/admin/services');
    }

    public function updateService(string $id): void {
        $this->requireAdmin();
        $data = [
            'name'        => $this->sanitize($this->input('name', '')),
            'description' => $this->sanitize($this->input('description', '')),
            'is_active'   => (int)$this->input('is_active', 1),
        ];
        (new ServiceModel())->update((int)$id, $data);
        $this->flash('success', 'Layanan diperbarui');
        $this->redirect('/admin/services');
    }

    public function deleteService(string $id): void {
        $this->requireAdmin();
        (new ServiceModel())->delete((int)$id);
        $this->flash('success', 'Layanan dihapus');
        $this->redirect('/admin/services');
    }

    public function packages(): void {
        $this->requireAdmin();
        $db       = Database::getInstance();
        $packages = $db->query("
            SELECT p.*, s.name as service_name, c.name as category_name
            FROM packages p
            JOIN services s ON p.service_id = s.id
            JOIN categories c ON s.category_id = c.id
            ORDER BY c.sort_order, s.name, p.price
        ")->fetchAll();
        $services = (new ServiceModel())->getAllWithCategory();
        $flash    = $this->getFlash();
        $this->view('admin/packages', compact('packages', 'services', 'flash'), 'admin');
    }

    public function storePackage(): void {
        $this->requireAdmin();
        $features = array_filter(explode("\n", $this->input('features', '')));
        $data = [
            'service_id'     => (int)$this->input('service_id'),
            'name'           => $this->sanitize($this->input('name', '')),
            'price'          => (float)$this->input('price'),
            'dp_percentage'  => (int)$this->input('dp_percentage', 50),
            'description'    => $this->sanitize($this->input('description', '')),
            'features'       => json_encode(array_values($features)),
            'duration_hours' => (int)$this->input('duration_hours', 8),
        ];
        (new PackageModel())->create($data);
        $this->flash('success', 'Paket berhasil ditambahkan');
        $this->redirect('/admin/packages');
    }

    public function updatePackage(string $id): void {
        $this->requireAdmin();
        $features = array_filter(explode("\n", $this->input('features', '')));
        $data = [
            'name'           => $this->sanitize($this->input('name', '')),
            'price'          => (float)$this->input('price'),
            'dp_percentage'  => (int)$this->input('dp_percentage', 50),
            'description'    => $this->sanitize($this->input('description', '')),
            'features'       => json_encode(array_values($features)),
            'duration_hours' => (int)$this->input('duration_hours', 8),
            'is_active'      => (int)$this->input('is_active', 1),
        ];
        (new PackageModel())->update((int)$id, $data);
        $this->flash('success', 'Paket diperbarui');
        $this->redirect('/admin/packages');
    }

    public function deletePackage(string $id): void {
        $this->requireAdmin();
        (new PackageModel())->delete((int)$id);
        $this->flash('success', 'Paket dihapus');
        $this->redirect('/admin/packages');
    }

    public function carousel(): void {
        $this->requireAdmin();
        $items = (new CarouselModel())->all('sort_order');
        $flash = $this->getFlash();
        $this->view('admin/carousel', compact('items', 'flash'), 'admin');
    }

    public function storeCarousel(): void {
        $this->requireAdmin();
        $data = [
            'title'      => $this->sanitize($this->input('title', '')),
            'subtitle'   => $this->sanitize($this->input('subtitle', '')),
            'media_type' => $this->input('media_type', 'image'),
            'cta_text'   => $this->sanitize($this->input('cta_text', '')),
            'cta_link'   => $this->sanitize($this->input('cta_link', '')),
            'sort_order' => (int)$this->input('sort_order', 0),
            'media_file' => '',
        ];

        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $bs     = new BookingService();
            $result = $bs->uploadFile($_FILES['media'], 'carousel');
            if (!$result['success']) {
                $this->flash('error', $result['message']);
                $this->redirect('/admin/carousel');
                return;
            }
            $data['media_file'] = $result['filename'];
        }

        (new CarouselModel())->create($data);
        $this->flash('success', 'Carousel berhasil ditambahkan');
        $this->redirect('/admin/carousel');
    }

    public function deleteCarousel(string $id): void {
        $this->requireAdmin();
        (new CarouselModel())->delete((int)$id);
        $this->flash('success', 'Item carousel dihapus');
        $this->redirect('/admin/carousel');
    }

    public function news(): void {
        $this->requireAdmin();
        $db    = Database::getInstance();
        $items = $db->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll();
        $flash = $this->getFlash();
        $this->view('admin/news', compact('items', 'flash'), 'admin');
    }

    public function storeNews(): void {
        $this->requireAdmin();
        $title = $this->sanitize($this->input('title', ''));
        $slug  = strtolower(str_replace(' ', '-', preg_replace('/[^a-zA-Z0-9 ]/', '', $title))) . '-' . time();
        $data  = [
            'title'    => $title,
            'slug'     => $slug,
            'content'  => $this->input('content', ''),
            'excerpt'  => $this->sanitize($this->input('excerpt', '')),
            'category' => $this->input('category', 'portfolio'),
            'image'    => '',
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $bs     = new BookingService();
            $result = $bs->uploadFile($_FILES['image'], 'portfolio');
            if ($result['success']) $data['image'] = $result['filename'];
        }

        (new NewsModel())->create($data);
        $this->flash('success', 'Artikel berhasil ditambahkan');
        $this->redirect('/admin/news');
    }

    public function updateNews(string $id): void {
        $this->requireAdmin();
        $data = [
            'title'        => $this->sanitize($this->input('title', '')),
            'content'      => $this->input('content', ''),
            'excerpt'      => $this->sanitize($this->input('excerpt', '')),
            'category'     => $this->input('category', 'portfolio'),
            'is_published' => (int)$this->input('is_published', 1),
        ];
        // Handle image upload if new file provided
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $bs     = new BookingService();
            $result = $bs->uploadFile($_FILES['image'], 'portfolio');
            if ($result['success']) {
                $data['image'] = $result['filename'];
            }
        }
        (new NewsModel())->update((int)$id, $data);
        $this->flash('success', 'Artikel berhasil diperbarui');
        $this->redirect('/admin/news');
    }

    public function deleteNews(string $id): void {
        $this->requireAdmin();
        (new NewsModel())->delete((int)$id);
        $this->flash('success', 'Artikel dihapus');
        $this->redirect('/admin/news');
    }
}

class ApiController extends Controller {
    // Pastikan tidak ada output buffer yang bocor
    private function cleanBuffer(): void {
        while (ob_get_level() > 0) ob_end_clean();
    }
    public function calendar(): void {
        $data = (new BookingModel())->getCalendarData();
        $this->json($data);
    }

    public function packages(string $service_id): void {
        $this->cleanBuffer();
        $packages = (new PackageModel())->getByService((int)$service_id);
        foreach ($packages as &$p) {
            $p['features']       = json_decode($p['features'] ?? '[]', true) ?: [];
            $p['price']          = (float)$p['price'];
            $p['dp_percentage']  = (int)$p['dp_percentage'];
            $p['duration_hours'] = (int)$p['duration_hours'];
            $p['id']             = (int)$p['id'];
            $p['service_id']     = (int)$p['service_id'];
        }
        unset($p);
        $this->json($packages);
    }
}

class NewsController extends Controller {
    public function index(): void {
        $items = (new NewsModel())->getPublished(12);
        $this->view('home/news', compact('items'));
    }

    public function show(string $slug): void {
        $item = (new NewsModel())->findBySlug($slug);
        if (!$item) { http_response_code(404); return; }
        $this->view('home/news_detail', compact('item'));
    }
}
