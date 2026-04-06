<?php

class HomeController extends Controller {
    public function index(): void {
        $carousel   = (new CarouselModel())->getActive();
        $categories = (new CategoryModel())->getWithServices();
        $news       = (new NewsModel())->getPublished(6);
        $calendar   = (new BookingModel())->getCalendarData();

        $this->view('home/index', compact('carousel', 'categories', 'news', 'calendar'));
    }

    public function contact(): void {
        $this->view('home/contact', ['flash' => $this->getFlash()]);
    }

    public function sendContact(): void {
        $name    = $this->sanitize($this->input('name', ''));
        $email   = $this->sanitize($this->input('email', ''));
        $message = $this->sanitize($this->input('message', ''));

        if (!$name || !$email || !$message) {
            $this->flash('error', 'Semua field harus diisi');
            $this->redirect('/contact');
            return;
        }

        // Send WA to admin
        $wa = new WhatsAppService();
        // $wa->sendContactMessage($name, $email, $message);

        $this->flash('success', 'Pesan Anda telah terkirim! Kami akan menghubungi Anda segera.');
        $this->redirect('/contact');
    }
}
