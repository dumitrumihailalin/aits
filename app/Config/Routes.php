<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */

// ── Public ──────────────────────────────────────────────
$routes->get('/', 'Home::index');
$routes->get('about',                       'About::index');
$routes->get('contact',                     'Contact::index');
$routes->post('contact',                    'Contact::send');
$routes->get('products/(:segment)',         'Products::show/$1');

// ── Customer Auth ───────────────────────────────────────
$routes->get('register',                    'Auth::register');
$routes->post('register',                   'Auth::registerPost');
$routes->get('register/success',            'Auth::registerSuccess');
$routes->get('verify-email/(:segment)',     'Auth::verify/$1');
$routes->get('resend-verification',         'Auth::resendVerification');
$routes->post('resend-verification',        'Auth::resendVerificationPost');
$routes->get('login',                       'Auth::login');
$routes->post('login',                      'Auth::loginPost');
$routes->get('logout',                      'Auth::logout');
$routes->get('forgot-password',             'Auth::forgotPassword');
$routes->post('forgot-password',            'Auth::forgotPasswordPost');
$routes->get('reset-password/(:segment)',   'Auth::resetPassword/$1');
$routes->post('reset-password/(:segment)',  'Auth::resetPasswordPost/$1');

// ── Customer Protected ──────────────────────────────────
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard',               'Customer\Dashboard::index');

    // Products selection
    $routes->get('my-products',             'Customer\Products::index');
    $routes->post('cart/save-all',          'Customer\Cart::saveAll');

    // Invoices
    $routes->get('invoices',                    'Customer\Invoices::index');
    $routes->get('invoices/(:segment)',         'Customer\Invoices::show/$1');
    $routes->get('invoices/(:segment)/pay',     'Customer\Invoices::pay/$1');
    $routes->get('invoices/(:segment)/pdf',     'Customer\Invoices::pdf/$1');

    // Support
    $routes->get('support',                 'Customer\Support::index');
    $routes->get('support/create',          'Customer\Support::create');
    $routes->post('support/store',          'Customer\Support::store');
    $routes->get('support/(:num)',          'Customer\Support::show/$1');
    $routes->post('support/reply/(:segment)', 'Customer\Support::reply/$1');

    // in customer protected group
    $routes->post('profile/notifications', 'Customer\Profile::updateNotifications');
    
    // Cart
    $routes->get('cart',                    'Customer\Cart::index');
    $routes->post('cart/save',               'Customer\Cart::save');
    $routes->post('cart/add/(:segment)',      'Customer\Cart::add/$1');
    $routes->post('cart/remove/(:segment)',   'Customer\Cart::remove/$1');
    $routes->post('cart/checkout',           'Customer\Cart::checkout');

    // Profile
    $routes->get('profile',                 'Customer\Profile::index');
    $routes->post('profile',                'Customer\Profile::update');
});

// ── Admin Auth ──────────────────────────────────────────
$routes->get('admin/login',                     'Admin\Auth::login');
$routes->post('admin/login',                    'Admin\Auth::loginPost');
$routes->get('admin/logout',                    'Admin\Auth::logout');
$routes->get('admin/forgot-password',           'Admin\Auth::forgotPassword');
$routes->post('admin/forgot-password',          'Admin\Auth::forgotPasswordPost');
$routes->get('admin/reset-password/(:segment)', 'Admin\Auth::resetPassword/$1');
$routes->post('admin/reset-password/(:segment)','Admin\Auth::resetPasswordPost/$1');

// ── Admin Protected ─────────────────────────────────────
$routes->group('admin', ['filter' => 'adminauth'], function ($routes) {
    $routes->get('/',                           'Admin\Dashboard::index');
    $routes->get('dashboard',                   'Admin\Dashboard::index');

    // Profile
    $routes->get('profile',                     'Admin\Profile::index');
    $routes->post('profile',                    'Admin\Profile::update');

    // Products
    $routes->get('products',                    'Admin\Products::index');
    $routes->get('products/create',             'Admin\Products::create');
    $routes->post('products/store',             'Admin\Products::store');
    $routes->get('products/edit/(:segment)',        'Admin\Products::edit/$1');
    $routes->post('products/update/(:segment)',     'Admin\Products::update/$1');
    $routes->post('products/delete/(:segment)',     'Admin\Products::delete/$1');

    // Features
    $routes->get('features',                        'Admin\Features::index');
    $routes->get('features/create',                 'Admin\Features::create');
    $routes->post('features/store',                 'Admin\Features::store');
    $routes->get('features/edit/(:segment)',         'Admin\Features::edit/$1');
    $routes->post('features/update/(:segment)',      'Admin\Features::update/$1');
    $routes->post('features/delete/(:segment)',      'Admin\Features::delete/$1');

    // Customers
    $routes->get('customers',                   'Admin\Customers::index');
    $routes->get('customers/(:num)',            'Admin\Customers::show/$1');
    
    // Invoices
    $routes->get('invoices',                        'Admin\Invoices::index');
    $routes->get('invoices/create',                 'Admin\Invoices::create');
    $routes->post('invoices/store',                 'Admin\Invoices::store');
    $routes->get('invoices/(:segment)',             'Admin\Invoices::show/$1');
    $routes->post('invoices/paid/(:segment)',       'Admin\Invoices::markPaid/$1');
    $routes->post('invoices/unpaid/(:segment)',     'Admin\Invoices::markUnpaid/$1');
    $routes->post('invoices/delete/(:segment)',     'Admin\Invoices::delete/$1');
    // Cart
    $routes->get('cart',                        'Admin\Cart::index');
    $routes->post('cart/add/(:num)',            'Admin\Cart::add/$1');
    $routes->post('cart/remove/(:num)',         'Admin\Cart::remove/$1');
    $routes->post('cart/update',                'Admin\Cart::updateQty');
    $routes->post('cart/clear',                 'Admin\Cart::clear');
    $routes->get('cart/checkout',               'Admin\Cart::checkout');
    $routes->post('cart/place-order',           'Admin\Cart::placeOrder');

    // Support
    $routes->get('support',                     'Admin\Support::index');
    $routes->get('support/(:num)',              'Admin\Support::show/$1');
    $routes->post('support/reply/(:num)',       'Admin\Support::reply/$1');
    $routes->post('support/status/(:num)',      'Admin\Support::updateStatus/$1');
    $routes->post('support/close/(:num)',       'Admin\Support::close/$1');

    // Settings
    $routes->get('settings',                    'Admin\Settings::index');
    $routes->post('settings',                   'Admin\Settings::update');
});