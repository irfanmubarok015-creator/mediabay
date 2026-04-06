<?php

abstract class Controller {

    /**
     * Render view dengan layout
     * @param string $layout 'main' | 'admin' | 'auth' | '' (tanpa layout)
     */
    protected function view(string $view, array $data = [], string $layout = 'main'): void {
        extract($data);

        $viewPath = __DIR__ . "/../app/views/{$view}.php";
        if (!file_exists($viewPath)) {
            http_response_code(500);
            die("<b>View not found:</b> <code>{$view}</code>");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($layout) {
            $layoutPath = __DIR__ . "/../app/views/layouts/{$layout}.php";
            if (file_exists($layoutPath)) {
                require $layoutPath;
                return;
            }
        }

        // Tidak ada layout — output langsung
        echo $content;
    }

    protected function json(array $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $url): void {
        // Kalau $url sudah full URL (http...) langsung pakai
        if (str_starts_with($url, 'http')) {
            header("Location: {$url}");
        } else {
            header("Location: " . BASE_URL . $url);
        }
        exit;
    }

    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function input(string $key, mixed $default = null): mixed {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function sanitize(string $value): string {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }

    protected function requireAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->flash('error', 'Silakan login terlebih dahulu');
            $this->redirect('/auth/login');
        }
    }

    protected function requireAdmin(): void {
        $this->requireAuth();
        if (($_SESSION['role'] ?? '') !== 'admin') {
            $this->redirect('/');
        }
    }

    protected function flash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function getFlash(): ?array {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}
