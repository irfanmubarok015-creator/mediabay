<?php

// Public Routes
$router->get('/', 'HomeController', 'index');
$router->get('/layanan', 'ServiceController', 'index');
$router->get('/layanan/:slug', 'ServiceController', 'show');
$router->get('/paket/:id', 'ServiceController', 'package');
$router->get('/informasi', 'NewsController', 'index');
$router->get('/informasi/:slug', 'NewsController', 'show');
$router->get('/contact', 'HomeController', 'contact');
$router->post('/contact', 'HomeController', 'sendContact');

// Auth Routes
$router->get('/auth/login', 'AuthController', 'loginForm');
$router->post('/auth/login', 'AuthController', 'login');
$router->get('/auth/register', 'AuthController', 'registerForm');
$router->post('/auth/register', 'AuthController', 'register');
$router->get('/auth/logout', 'AuthController', 'logout');

// Booking Routes (Auth required)
$router->get('/booking', 'BookingController', 'index');
$router->post('/booking', 'BookingController', 'store');
$router->get('/booking/:code', 'BookingController', 'show');
$router->post('/booking/:code/payment', 'BookingController', 'uploadPayment');

// User Dashboard Routes
$router->get('/dashboard', 'UserController', 'dashboard');
$router->get('/dashboard/profil', 'UserController', 'profile');
$router->post('/dashboard/profil', 'UserController', 'updateProfile');
$router->get('/dashboard/booking', 'UserController', 'bookings');

// Admin Routes
$router->get('/admin', 'AdminController', 'dashboard');
$router->get('/admin/bookings', 'AdminController', 'bookings');
$router->post('/admin/bookings/:id/approve', 'AdminController', 'approveBooking');
$router->post('/admin/bookings/:id/reject', 'AdminController', 'rejectBooking');
$router->get('/admin/payments', 'AdminController', 'payments');
$router->post('/admin/payments/:id/verify', 'AdminController', 'verifyPayment');


// Admin Relations
$router->get('/admin/relations', 'AdminController', 'relations');

// Admin CRUD - Categories
$router->get('/admin/categories', 'AdminController', 'categories');
$router->post('/admin/categories', 'AdminController', 'storeCategory');
$router->post('/admin/categories/:id/update', 'AdminController', 'updateCategory');
$router->post('/admin/categories/:id/delete', 'AdminController', 'deleteCategory');

// Admin CRUD - Services
$router->get('/admin/services', 'AdminController', 'services');
$router->post('/admin/services', 'AdminController', 'storeService');
$router->post('/admin/services/:id/update', 'AdminController', 'updateService');
$router->post('/admin/services/:id/delete', 'AdminController', 'deleteService');

// Admin CRUD - Packages
$router->get('/admin/packages', 'AdminController', 'packages');
$router->post('/admin/packages', 'AdminController', 'storePackage');
$router->post('/admin/packages/:id/update', 'AdminController', 'updatePackage');
$router->post('/admin/packages/:id/delete', 'AdminController', 'deletePackage');

// Admin CRUD - Carousel
$router->get('/admin/carousel', 'AdminController', 'carousel');
$router->post('/admin/carousel', 'AdminController', 'storeCarousel');
$router->post('/admin/carousel/:id/delete', 'AdminController', 'deleteCarousel');

// Admin CRUD - News
$router->get('/admin/news', 'AdminController', 'news');
$router->post('/admin/news', 'AdminController', 'storeNews');
$router->post('/admin/news/:id/update', 'AdminController', 'updateNews');
$router->post('/admin/news/:id/delete', 'AdminController', 'deleteNews');

// API Routes (AJAX)
$router->get('/api/calendar', 'ApiController', 'calendar');
$router->get('/api/packages/:service_id', 'ApiController', 'packages');
