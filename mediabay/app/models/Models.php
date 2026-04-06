<?php
class CategoryModel extends Model {
    protected string $table = 'categories';

    public function getActive(): array {
        $stmt = $this->db->query("SELECT * FROM categories WHERE is_active=1 ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array {
        return $this->findBy('slug', $slug);
    }

    public function getWithServices(): array {
        $stmt = $this->db->query("
            SELECT c.*, COUNT(s.id) as service_count
            FROM categories c
            LEFT JOIN services s ON s.category_id = c.id AND s.is_active = 1
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY c.sort_order
        ");
        return $stmt->fetchAll();
    }
}

class ServiceModel extends Model {
    protected string $table = 'services';

    public function findBySlug(string $slug): ?array {
        return $this->findBy('slug', $slug);
    }

    public function getByCategory(int $categoryId): array {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE category_id=? AND is_active=1");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function getAllWithCategory(): array {
        $stmt = $this->db->query("
            SELECT s.*, c.name as category_name, c.slug as category_slug
            FROM services s
            JOIN categories c ON s.category_id = c.id
            WHERE s.is_active = 1
            ORDER BY c.sort_order, s.name
        ");
        return $stmt->fetchAll();
    }
}

class PackageModel extends Model {
    protected string $table = 'packages';

    public function getByService(int $serviceId): array {
        $stmt = $this->db->prepare("SELECT * FROM packages WHERE service_id=? AND is_active=1 ORDER BY price ASC");
        $stmt->execute([$serviceId]);
        return $stmt->fetchAll();
    }

    public function getWithService(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, s.name as service_name, c.name as category_name
            FROM packages p
            JOIN services s ON p.service_id = s.id
            JOIN categories c ON s.category_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}

class BookingModel extends Model {
    protected string $table = 'bookings';

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT b.*, p.name as package_name, s.name as service_name, c.name as category_name
            FROM bookings b
            JOIN packages p ON b.package_id = p.id
            JOIN services s ON p.service_id = s.id
            JOIN categories c ON s.category_id = c.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getDetail(string $code): ?array {
        $stmt = $this->db->prepare("
            SELECT b.*, p.name as package_name, p.dp_percentage, p.features,
                   s.name as service_name, c.name as category_name,
                   u.name as user_name, u.email as user_email, u.phone as user_phone
            FROM bookings b
            JOIN packages p ON b.package_id = p.id
            JOIN services s ON p.service_id = s.id
            JOIN categories c ON s.category_id = c.id
            JOIN users u ON b.user_id = u.id
            WHERE b.booking_code = ?
        ");
        $stmt->execute([$code]);
        return $stmt->fetch() ?: null;
    }

    public function getAllWithDetails(string $status = ''): array {
        $sql = "
            SELECT b.*, p.name as package_name, s.name as service_name,
                   u.name as user_name, u.email as user_email, u.phone as user_phone
            FROM bookings b
            JOIN packages p ON b.package_id = p.id
            JOIN services s ON p.service_id = s.id
            JOIN users u ON b.user_id = u.id
        ";
        if ($status) {
            $stmt = $this->db->prepare($sql . " WHERE b.status = ? ORDER BY b.created_at DESC");
            $stmt->execute([$status]);
        } else {
            $stmt = $this->db->query($sql . " ORDER BY b.created_at DESC");
        }
        return $stmt->fetchAll();
    }

    public function getCalendarData(): array {
        $stmt = $this->db->query("
            SELECT event_date, COUNT(*) as total,
                   SUM(CASE WHEN status='APPROVED' THEN 1 ELSE 0 END) as approved
            FROM bookings
            WHERE status NOT IN ('REJECTED','EXPIRED')
            GROUP BY event_date
        ");
        return $stmt->fetchAll();
    }
}

class PaymentModel extends Model {
    protected string $table = 'payments';

    public function getByBooking(int $bookingId): array {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE booking_id=? ORDER BY created_at DESC");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll();
    }

    public function getPendingWithBooking(): array {
        $stmt = $this->db->query("
            SELECT py.*, b.booking_code, b.event_date, b.dp_amount,
                   u.name as user_name, u.email as user_email, u.phone as user_phone,
                   p.name as package_name
            FROM payments py
            JOIN bookings b ON py.booking_id = b.id
            JOIN users u ON b.user_id = u.id
            JOIN packages p ON b.package_id = p.id
            WHERE py.status = 'PENDING'
            ORDER BY py.created_at DESC
        ");
        return $stmt->fetchAll();
    }
}

class CarouselModel extends Model {
    protected string $table = 'carousels';

    public function getActive(): array {
        $stmt = $this->db->query("SELECT * FROM carousels WHERE is_active=1 ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    }
}

class NewsModel extends Model {
    protected string $table = 'news';

    public function getPublished(int $limit = 6): array {
        $stmt = $this->db->prepare("SELECT * FROM news WHERE is_published=1 ORDER BY published_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array {
        return $this->findBy('slug', $slug);
    }
}
