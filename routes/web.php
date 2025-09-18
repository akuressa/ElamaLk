<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;


require __DIR__ . '/auth.php';

// Installer Middleware
Route::group(['middleware' => 'Installer'], function () {
  // Google OAuth routes
  Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
  Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

  Route::get('/', [App\Http\Controllers\Website\WebController::class, "webIndex"])->name("web.index")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/features', [App\Http\Controllers\Website\WebController::class, "webFeatures"])->name("web.features")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/contact', [App\Http\Controllers\Website\WebController::class, "webContact"])->name("web.contact")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/about', [App\Http\Controllers\Website\WebController::class, "webAbout"])->name("web.about")->middleware('scriptsanitizer', 'setLocale');
  Route::post('/send-email', [App\Http\Controllers\Website\MailerController::class, "composeEmail"])->name("send-email")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/privacy-policy', [App\Http\Controllers\Website\WebController::class, "webPrivacy"])->name("web.privacy")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/refund-policy', [App\Http\Controllers\Website\WebController::class, "webRefund"])->name("web.refund")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/terms-and-conditions', [App\Http\Controllers\Website\WebController::class, "webTerms"])->name("web.terms")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/filter-businesses', [App\Http\Controllers\AjaxController::class, 'filterBusinesses'])->name('filter.businesses');
  Route::get('/blogs', [App\Http\Controllers\Website\WebController::class, "blogs"])->name("web.blogs")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/blog/{blog_slug}', [App\Http\Controllers\Website\WebController::class, "viewBlog"])->name("web.view.blog")->middleware('scriptsanitizer', 'setLocale');
  Route::get('/businesses/{business_category_slug}', [App\Http\Controllers\Website\WebController::class, "businesses"])->name("web.businesses");
  Route::get('/b/{business_id}', [App\Http\Controllers\Website\WebController::class, "business"])->name("web.business");
  
// Business Plan Subscription Routes
Route::post('/subscribe-business-plan', [App\Http\Controllers\BusinessPlanSubscriptionController::class, 'subscribe'])->name('subscribe.business.plan');
Route::get('/user-subscriptions/{business_id}', [App\Http\Controllers\BusinessPlanSubscriptionController::class, 'getUserSubscriptions'])->name('user.subscriptions');

// Business Plan Checkout Routes
Route::get('/business-plan-checkout/{business_id}/{business_plan_id}', [App\Http\Controllers\BusinessPlanSubscriptionController::class, 'checkout'])->name('business.plan.checkout');
Route::post('/business-plan-payment/{business_id}/{business_plan_id}', [App\Http\Controllers\BusinessPlanSubscriptionController::class, 'processPayment'])->name('business.plan.payment');

// Business Plan Payment Gateway Routes
Route::get('/business-plan-payment-paypal/{business_plan_id}', [App\Http\Controllers\Payment\PaypalController::class, 'businessPlanPaypal'])->name('business.plan.payment.paypal');
Route::get('/business-plan-payment-stripe/{business_plan_id}', [App\Http\Controllers\Payment\StripeController::class, 'businessPlanStripe'])->name('business.plan.payment.stripe');
Route::get('/business-plan-payment-onepay/{business_plan_id}', [App\Http\Controllers\Payment\OnepayController::class, 'businessPlanOnepay'])->name('business.plan.payment.onepay');
Route::get('/business-plan-payment-mollie/{business_plan_id}', [App\Http\Controllers\Payment\MollieController::class, 'businessPlanMollie'])->name('business.plan.payment.mollie');
Route::get('/business-plan-payment-razorpay/{business_plan_id}', [App\Http\Controllers\Payment\RazorPayController::class, 'businessPlanRazorPay'])->name('business.plan.payment.razorpay');
Route::get('/business-plan-payment-paystack/{business_plan_id}', [App\Http\Controllers\Payment\PaystackController::class, 'businessPlanPaystack'])->name('business.plan.payment.paystack');
Route::get('/business-plan-payment-mercadopago/{business_plan_id}', [App\Http\Controllers\Payment\MercadoPagoController::class, 'businessPlanMercadoPago'])->name('business.plan.payment.mercadopago');

  // Blog post share
  Route::get('/blog/{blog_slug}/share/facebook', [App\Http\Controllers\Website\ShareController::class, "shareToFacebook"])->name("sharetofacebook");
  Route::get('/blog/{blog_slug}/share/twitter', [App\Http\Controllers\Website\ShareController::class, "shareToTwitter"])->name("sharetotwitter");
  Route::get('/blog/{blog_slug}/share/linkedin', [App\Http\Controllers\Website\ShareController::class, "shareToLinkedIn"])->name("sharetolinkedin");
  Route::get('/blog/{blog_slug}/share/instagram', [App\Http\Controllers\Website\ShareController::class, "shareToInstagram"])->name("sharetoinstagram");
  Route::get('/blog/{blog_slug}/share/whatsapp', [App\Http\Controllers\Website\ShareController::class, "shareToWhatsApp"])->name("sharetowhatsapp");

  // Custom pages
  Route::get('/p/{id}', [App\Http\Controllers\Website\WebController::class, "customPage"])->name("web.custom.page")->middleware('scriptsanitizer', 'setLocale');

  // Admin Routes
  Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin', 'setLocale'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {

    // Dashboard
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');

    // Switch Account
    Route::get('switch/account', [App\Http\Controllers\Admin\BusinessController::class, "switchAccount"])->name('switch.account');

    // Account Setting
    Route::get('account', [App\Http\Controllers\Admin\AccountController::class, "index"])->name('index.account');
    Route::get('account/edit', [App\Http\Controllers\Admin\AccountController::class, "editAccount"])->name('edit.account');
    Route::post('account/update', [App\Http\Controllers\Admin\AccountController::class, "updateAccount"])->name('update.account');
    Route::get('account/change-password', [App\Http\Controllers\Admin\AccountController::class, "changePassword"])->name('change.password');
    Route::post('account/update-password', [App\Http\Controllers\Admin\AccountController::class, "UpdatePassword"])->name('update.password');
    Route::get('theme/{id}', [App\Http\Controllers\Admin\AccountController::class, "changeTheme"])->name('change.theme');
    Route::get('view-user/{id}', [App\Http\Controllers\Admin\UserController::class, "viewUser"])->name('view.user');

    //Plans
    Route::get('plans', [App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
    Route::get('plans/add', [App\Http\Controllers\Admin\PlanController::class, 'add'])->name('plans.add');
    Route::post('plans/save', [App\Http\Controllers\Admin\PlanController::class, "savePlan"])->name('save.plan');
    Route::get('plans/activation', [App\Http\Controllers\Admin\PlanController::class, "activationPlan"])->name('activation.plan');
    Route::get('plans/delete', [App\Http\Controllers\Admin\PlanController::class, "deletePlan"])->name('delete.plan');
    Route::get('plans/edit/{plan_id}', [App\Http\Controllers\Admin\PlanController::class, "editPlan"])->name('edit.plan');
    Route::post('plans/update/{plan_id}', [App\Http\Controllers\Admin\PlanController::class, 'updatePlan'])->name('update.plan');

    // License
    Route::get('license', [App\Http\Controllers\Admin\LicenseController::class, "license"])->name('license');
    Route::post('verify-license', [App\Http\Controllers\Admin\LicenseController::class, "verifyLicense"])->name('verify.license');

    //Settings
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::get('test-email', [App\Http\Controllers\Admin\SettingController::class, "testEmail"])->name('test.email');
    Route::post('change-general-settings', [App\Http\Controllers\Admin\SettingController::class, "changeGeneralSettings"])->name('change.general.settings');
    Route::post('change-payments-settings', [App\Http\Controllers\Admin\SettingController::class, "changePaymentsSettings"])->name('change.payments.settings');
    Route::post('change-google-settings', [App\Http\Controllers\Admin\SettingController::class, "changeGoogleSettings"])->name('change.google.settings');
    Route::post('change-email-settings', [App\Http\Controllers\Admin\SettingController::class, "changeEmailSettings"])->name('change.email.settings');
    Route::post('change-website-settings', [App\Http\Controllers\Admin\SettingController::class, "changeWebsiteSettings"])->name('change.website.settings');

    // Check update
    Route::get('check', [App\Http\Controllers\Admin\UpdateController::class, 'check'])->name('check');
    Route::post('check-update', [App\Http\Controllers\Admin\UpdateController::class, 'checkUpdate'])->name('check.update');
    Route::post('update-code', [App\Http\Controllers\Admin\UpdateController::class, 'updateCode'])->name('update.code');

    // Invoice and Tax
    Route::get('tax-setting', [App\Http\Controllers\Admin\SettingController::class, "taxSetting"])->name('tax.setting');
    Route::post('update-email-setting', [App\Http\Controllers\Admin\SettingController::class, "updateEmailSetting"])->name('update.email.setting');
    Route::post('update-tex-setting', [App\Http\Controllers\Admin\SettingController::class, "updateTaxSetting"])->name('update.tax.setting');

    // Business
    Route::get('businesses', [App\Http\Controllers\Admin\BusinessController::class, 'index'])->name('businesses.index');
    Route::get('businesses/delete', [App\Http\Controllers\Admin\BusinessController::class, "deleteBusiness"])->name('delete.business');
    Route::get('businesses/activation', [App\Http\Controllers\Admin\BusinessController::class, "activationBusiness"])->name('activation.business');
    Route::get('businesses/{business_id}', [App\Http\Controllers\Admin\BusinessController::class, 'businessIndex'])->name('business.index');

    // Services and Users
    Route::get('businesses/service/delete', [App\Http\Controllers\Admin\BusinessController::class, "deleteBusinessService"])->name('delete.business-service');
    Route::get('businesses/service/activation', [App\Http\Controllers\Admin\BusinessController::class, "activationBusinessService"])->name('activation.business-service');
    Route::get('businesses/user/delete', [App\Http\Controllers\Admin\BusinessController::class, "deleteBusinessUser"])->name('delete.user');
    Route::get('businesses/user/activation', [App\Http\Controllers\Admin\BusinessController::class, "activationBusinessUser"])->name('activation.user');

    // Business Category
    Route::get('business-categories', [App\Http\Controllers\Admin\BusinessCategoryController::class, 'index'])->name('business-categories.index');
    Route::get('business-categories/add', [App\Http\Controllers\Admin\BusinessCategoryController::class, 'add'])->name('business-categories.add');
    Route::post('business-category/save', [App\Http\Controllers\Admin\BusinessCategoryController::class, "saveBusinessCategory"])->name('save.business-category');
    Route::get('business-category/delete', [App\Http\Controllers\Admin\BusinessCategoryController::class, "deleteBusinessCategory"])->name('delete.business-category');
    Route::get('business-category/activation', [App\Http\Controllers\Admin\BusinessCategoryController::class, "activationBusinessCategory"])->name('activation.business-category');
    Route::get('business-category/edit/{business_category_id}', [App\Http\Controllers\Admin\BusinessCategoryController::class, "editBusinessCategory"])->name('edit.business-category');
    Route::post('business-category/update/{business_category_id}', [App\Http\Controllers\Admin\BusinessCategoryController::class, 'updateBusinessCategory'])->name('update.business-category');

    // Customers
    Route::get('customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/activation', [App\Http\Controllers\Admin\CustomerController::class, "activationCustomer"])->name('activation.customer');
    Route::get('customers/delete', [App\Http\Controllers\Admin\CustomerController::class, "deleteCustomer"])->name('delete.customer');

    // Users
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');

    // Withdrawal Requests
    Route::get('withdrawal-requests', [App\Http\Controllers\Admin\WithdrawalRequestController::class, 'index'])->name('withdrawal-requests.index');
    Route::get('withdrawal-status/{id}/{status}', [App\Http\Controllers\Admin\WithdrawalRequestController::class, 'withdrawalStatus'])->name('withdrawal.status');

    // Refund Requests
    Route::get('refund-requests', [App\Http\Controllers\Admin\RefundController::class, 'index'])->name('refund-requests.index');
    Route::get('refund-status/{id}/{status}', [App\Http\Controllers\Admin\RefundController::class, 'refundStatus'])->name('refund.status');

    // Payment Methods
    Route::get('payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('payment-method/delete', [App\Http\Controllers\Admin\PaymentMethodController::class, "deletePaymentMethod"])->name('delete.payment.method');

    //Onepay
    Route::get('/paywithonepay/{planId}', [App\Http\Controllers\Payment\OnepayController::class, 'redirectToOnepay'])->name('paywithonepay');
    Route::post('/onepay/callback', [App\Http\Controllers\Payment\OnepayController::class, 'handleCallback'])->name('onepay.callback');

    // Route for redirecting to OnePay payment gateway
    Route::get('/payonepay/{bookingId}', [App\Http\Controllers\Payment\OnepayController::class, 'bookingOnepayNewtwo'])->name('user.appointment.book');

    Route::get('/booking-payment-onepay/{bookingId}', [App\Http\Controllers\Payment\OnepayController::class, "bookingOnepay"])->name('bookingOnepay');


    // Business Transactions
    Route::get('business-transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('business-transaction-status/{id}/{status}', [App\Http\Controllers\Admin\TransactionController::class, "transactionStatus"])->name('trans.status');

    // Offline Transactions
    Route::get('business-offline-transactions', [App\Http\Controllers\Admin\TransactionController::class, "offlineTransactions"])->name('business.offline.transactions');
    Route::get('offline-transaction-status/{id}/{status}', [App\Http\Controllers\Admin\TransactionController::class, "offlineTransactionStatus"])->name('business.offline.trans.status');
    Route::get('view-invoice/{id}', [App\Http\Controllers\Admin\TransactionController::class, "viewInvoice"])->name('view.invoice');

    // Booking Transactions
    Route::get('booking-transactions', [App\Http\Controllers\Admin\BookingTransactionController::class, 'index'])->name('booking-transactions.index');
    Route::get('booking-offline-transactions', [App\Http\Controllers\Admin\BookingTransactionController::class, "offlineBookingTransactions"])->name('booking.offline.transactions');
    Route::get('booking-transaction-status/{id}/{status}', [App\Http\Controllers\Admin\BookingTransactionController::class, "bookingTransactionStatus"])->name('booking.trans.status');
    Route::get('booking-offline-transaction-status/{id}/{status}', [App\Http\Controllers\Admin\BookingTransactionController::class, "offlineBookingTransactionStatus"])->name('booking.offline.trans.status');
    Route::get('view-invoice-booking/{id}', [App\Http\Controllers\Admin\BookingTransactionController::class, "viewInvoiceBooking"])->name('view.invoice.booking');

    // Pages
    Route::get('pages', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
    Route::get('pages/edit/{page_name}', [App\Http\Controllers\Admin\PageController::class, 'edit'])->name('edit.page');
    Route::post('pages/update/{page_id}', [App\Http\Controllers\Admin\PageController::class, 'update'])->name('update.page');
    Route::post('pages/update-seo/{page_name}', [App\Http\Controllers\Admin\PageController::class, 'updateSeo'])->name('update.seo.page');
    Route::get('pages/{page_name}/{status}', [App\Http\Controllers\Admin\PageController::class, 'pageStatus'])->name('page.status');

    // Blogs Categories
    Route::get('blog-categories', [App\Http\Controllers\Admin\BlogCategoryController::class, "index"])->name('blog-categories.index');
    Route::get('blog-category/create', [App\Http\Controllers\Admin\BlogCategoryController::class, "createBlogCategory"])->name('create.blog-category');
    Route::post('blog-category/publish', [App\Http\Controllers\Admin\BlogCategoryController::class, "publishBlogCategory"])->name('publish.blog-category');
    Route::get('blog-category/action', [App\Http\Controllers\Admin\BlogCategoryController::class, "actionBlogCategory"])->name('action.blog-category');
    Route::get('blog-category/edit/{blog_category_id}', [App\Http\Controllers\Admin\BlogCategoryController::class, "editBlogCategory"])->name('edit.blog-category');
    Route::post('blog-category/update/{blog_category_id}', [App\Http\Controllers\Admin\BlogCategoryController::class, "updateBlogCategory"])->name('update.blog-category');

    // Blogs
    Route::get('blogs', [App\Http\Controllers\Admin\BlogController::class, "index"])->name('blogs.index');
    Route::get('blog/create', [App\Http\Controllers\Admin\BlogController::class, "createBlog"])->name('create.blog');
    Route::post('blog/publish', [App\Http\Controllers\Admin\BlogController::class, "publishBlog"])->name('publish.blog');
    Route::get('blog/action', [App\Http\Controllers\Admin\BlogController::class, "actionBlog"])->name('action.blog');
    Route::get('blog/edit/{blog_id}', [App\Http\Controllers\Admin\BlogController::class, "editBlog"])->name('edit.blog');
    Route::post('blog/update/{blog_id}', [App\Http\Controllers\Admin\BlogController::class, "updateBlog"])->name('update.blog');
  });

  // Business Routes
  Route::group(['as' => 'business.', 'prefix' => 'business', 'namespace' => 'Business', 'middleware' => ['auth', 'business', 'setLocale'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
    // Dashboard
    Route::get('dashboard', [App\Http\Controllers\Business\DashboardController::class, 'index'])->name('dashboard.index');

    // Resend Email Verfication
    Route::get('verify-email-verification', [App\Http\Controllers\Business\VerificationController::class, "verifyEmailVerification"])->name('verify.email.verification');
    Route::get('resend-email-verification', [App\Http\Controllers\Business\VerificationController::class, "resendEmailVerification"])->name('resend.email.verification');

    // Account
    Route::get('account', [App\Http\Controllers\Business\AccountController::class, "index"])->name('index.account');
    Route::get('account/edit', [App\Http\Controllers\Business\AccountController::class, "editAccount"])->name('edit.account');
    Route::post('account/update', [App\Http\Controllers\Business\AccountController::class, "updateAccount"])->name('update.account');
    Route::get('account/change-password', [App\Http\Controllers\Business\AccountController::class, "changePassword"])->name('change.password');
    Route::post('account/update-password', [App\Http\Controllers\Business\AccountController::class, "UpdatePassword"])->name('update.password');
    Route::get('theme/{id}', [App\Http\Controllers\Business\AccountController::class, "changeTheme"])->name('change.theme');

    // Businesses
    Route::get('businesses', [App\Http\Controllers\Business\BusinessController::class, 'index'])->name('businesses.index');
    Route::get('businesses/add', [App\Http\Controllers\Business\BusinessController::class, 'add'])->name('businesses.add');
    Route::post('businesses/save-business', [App\Http\Controllers\Business\BusinessController::class, "saveBusiness"])->name('save.business');
    Route::get('businesses/delete', [App\Http\Controllers\Business\BusinessController::class, "deleteBusiness"])->name('delete.business');
    Route::get('businesses/activation', [App\Http\Controllers\Business\BusinessController::class, "activationBusiness"])->name('activation.business');
    Route::get('businesses/edit/{business_id}', [App\Http\Controllers\Business\BusinessController::class, "editBusiness"])->name('edit.business');
    Route::post('businesses/update/{business_id}', [App\Http\Controllers\Business\BusinessController::class, 'updateBusiness'])->name('update.business');

    // Ajax Routes For Businesses
    Route::get('businesses/states', [App\Http\Controllers\AjaxController::class, 'states'])->name('states.index');
    Route::get('businesses/cities', [App\Http\Controllers\AjaxController::class, 'cities'])->name('cities.index');

    // Plans
    Route::get('plans', [App\Http\Controllers\Business\PlanController::class, 'index'])->name('plans.index');

    // Checkout
    Route::get('checkout/{plan_id}', [App\Http\Controllers\Business\CheckoutController::class, 'checkout'])->name('checkout');

    // Transactions
    Route::get('transactions', [App\Http\Controllers\Business\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('view-invoice/{id}', [App\Http\Controllers\Business\TransactionController::class, "viewInvoice"])->name('view.invoice');
  });


  // Business Admin Routes
  Route::group(['as' => 'business-admin.', 'prefix' => 'business-admin', 'namespace' => 'Business-Admin', 'middleware' => ['auth', 'business-admin', 'setLocale'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
    // Dashboard
    Route::get('{business_id}/dashboard', [App\Http\Controllers\BusinessAdmin\DashboardController::class, 'index'])->name('dashboard.index');

    // Resend Email Verfication
    Route::get('verify-email-verification', [App\Http\Controllers\BusinessAdmin\VerificationController::class, "verifyEmailVerification"])->name('verify.email.verification');
    Route::get('resend-email-verification', [App\Http\Controllers\BusinessAdmin\VerificationController::class, "resendEmailVerification"])->name('resend.email.verification');

    // Account
    Route::get('{business_id}/account', [App\Http\Controllers\BusinessAdmin\AccountController::class, "index"])->name('index.account');
    Route::get('{business_id}/account/edit', [App\Http\Controllers\BusinessAdmin\AccountController::class, "editAccount"])->name('edit.account');
    Route::post('{business_id}/account/update', [App\Http\Controllers\BusinessAdmin\AccountController::class, "updateAccount"])->name('update.account');
    Route::get('{business_id}/account/change-password', [App\Http\Controllers\BusinessAdmin\AccountController::class, "changePassword"])->name('change.password');
    Route::post('{business_id}/account/update-password', [App\Http\Controllers\BusinessAdmin\AccountController::class, "UpdatePassword"])->name('update.password');
    Route::get('theme/{id}', [App\Http\Controllers\BusinessAdmin\AccountController::class, "changeTheme"])->name('change.theme');

    // Users
    Route::get('{business_id}/users', [App\Http\Controllers\BusinessAdmin\UserController::class, 'index'])->name('users.index');
    Route::get('{business_id}/users/add', [App\Http\Controllers\BusinessAdmin\UserController::class, 'addUser'])->name('add.user');
    Route::post('{business_id}/users/save', [App\Http\Controllers\BusinessAdmin\UserController::class, 'saveBusiness'])->name('save.user');
    Route::get('{business_id}/users/edit/{user_id}', [App\Http\Controllers\BusinessAdmin\UserController::class, 'editUser'])->name('edit.user');
    Route::post('{business_id}/users/update/{user_id}', [App\Http\Controllers\BusinessAdmin\UserController::class, 'updateUser'])->name('update.user');
    Route::get('{business_id}/users/delete/{user_id}', [App\Http\Controllers\BusinessAdmin\UserController::class, 'deleteUser'])->name('delete.user');
    Route::get('{business_id}users/activate/{user_id}', [App\Http\Controllers\BusinessAdmin\UserController::class, 'activateUser'])->name('activate.user');

    // Services
    Route::get('{business_id}/services', [App\Http\Controllers\BusinessAdmin\BusinessServiceController::class, 'index'])->name('services.index');
    Route::get('{business_id}/services/add', [App\Http\Controllers\BusinessAdmin\BusinessServiceController::class, 'addService'])->name('add.service');
    Route::post('{business_id}/services/save', [App\Http\Controllers\BusinessAdmin\BusinessServiceController::class, 'saveService'])->name('save.service');
    Route::get('{business_id}/services/edit/{business_service_id}', [App\Http\Controllers\BusinessAdmin\BusinessServiceController::class, 'editService'])->name('edit.service');
    Route::post('{business_id}/services/update/{business_service_id}', [App\Http\Controllers\BusinessAdmin\BusinessServiceController::class, 'updateService'])->name('update.service');
    Route::get('{business_id}/services/delete/{business_service_id}', [App\Http\Controllers\BusinessAdmin\BusinessServiceController::class, 'deleteService'])->name('delete.service');
    Route::get('{business_id}/services/activation/{business_service_id}', [App\Http\Controllers\BusinessAdmin\BusinessServiceController::class, 'activationService'])->name('activation.service');

    // Employees
    Route::get('{business_id}/employees', [App\Http\Controllers\BusinessAdmin\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('{business_id}/employees/add', [App\Http\Controllers\BusinessAdmin\EmployeeController::class, 'addEmployee'])->name('add.employee');
    Route::post('{business_id}/employees/save', [App\Http\Controllers\BusinessAdmin\EmployeeController::class, 'saveEmployee'])->name('save.employee');
    Route::get('{business_id}/employees/edit/{business_employee_id}', [App\Http\Controllers\BusinessAdmin\EmployeeController::class, 'editEmployee'])->name('edit.employee');
    Route::post('{business_id}/employees/update/{business_employee_id}', [App\Http\Controllers\BusinessAdmin\EmployeeController::class, 'updateEmployee'])->name('update.employee');
    Route::get('{business_id}/employees/delete/{business_employee_id}', [App\Http\Controllers\BusinessAdmin\EmployeeController::class, 'deleteEmployee'])->name('delete.employee');
    Route::get('{business_id}/employees/activation/{business_employee_id}', [App\Http\Controllers\BusinessAdmin\EmployeeController::class, 'activationEmployee'])->name('activation.employee');

    // Bookings
    Route::get('{business_id}/bookings', [App\Http\Controllers\BusinessAdmin\BookingController::class, 'index'])->name('bookings.index');
    Route::post('{business_id}/booking/reschedule/{booking_id}', [App\Http\Controllers\BusinessAdmin\BookingController::class, 'rescheduleBooking'])->name('reschedule.booking');
    Route::get('{business_id}/booking/cancel/{booking_id}', [App\Http\Controllers\BusinessAdmin\BookingController::class, 'cancelBooking'])->name('cancel.booking');

    // Ajax Routes For User
    Route::get('business-admin/fetch-slots', [App\Http\Controllers\AjaxController::class, 'slots'])->name('fetch.slots');
    Route::get('fetch-bookings', [App\Http\Controllers\AjaxController::class, 'bookingDetails'])->name('booking.details');

    // Wallet
    Route::get('{business_id}/wallet', [App\Http\Controllers\BusinessAdmin\WalletController::class, 'index'])->name('wallet.index');
    Route::post('{business_id}/withdraw-request', [App\Http\Controllers\BusinessAdmin\WalletController::class, 'withdrawRequest'])->name('withdraw-request');

    // Business Plans
    Route::get('{business_id}/business-plans', [App\Http\Controllers\BusinessAdmin\BusinessPlanController::class, 'index'])->name('business-plans.index');
    Route::get('{business_id}/business-plans/create', [App\Http\Controllers\BusinessAdmin\BusinessPlanController::class, 'create'])->name('business-plans.create');
    Route::post('{business_id}/business-plans', [App\Http\Controllers\BusinessAdmin\BusinessPlanController::class, 'store'])->name('business-plans.store');
    Route::get('{business_id}/business-plans/{business_plan_id}', [App\Http\Controllers\BusinessAdmin\BusinessPlanController::class, 'show'])->name('business-plans.show');
    Route::get('{business_id}/business-plans/{business_plan_id}/edit', [App\Http\Controllers\BusinessAdmin\BusinessPlanController::class, 'edit'])->name('business-plans.edit');
    Route::put('{business_id}/business-plans/{business_plan_id}', [App\Http\Controllers\BusinessAdmin\BusinessPlanController::class, 'update'])->name('business-plans.update');
    Route::delete('{business_id}/business-plans/{business_plan_id}', [App\Http\Controllers\BusinessAdmin\BusinessPlanController::class, 'destroy'])->name('business-plans.destroy');
    Route::get('{business_id}/business-plans/{business_plan_id}/toggle', [App\Http\Controllers\BusinessAdmin\BusinessPlanController::class, 'toggle'])->name('business-plans.toggle');
  });

  // User Routes
  Route::group(['as' => 'user.', 'prefix' => 'user', 'namespace' => 'User', 'middleware' => ['auth', 'user', 'setLocale'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
    // Account
    Route::get('account', [App\Http\Controllers\User\AccountController::class, "index"])->name('account.index');
    Route::get('account/edit', [App\Http\Controllers\User\AccountController::class, "editAccount"])->name('edit.account');
    Route::post('account/update', [App\Http\Controllers\User\AccountController::class, "updateAccount"])->name('update.account');
    Route::get('account/change-password', [App\Http\Controllers\User\AccountController::class, "changePassword"])->name('change.password');
    Route::post('account/update-password', [App\Http\Controllers\User\AccountController::class, "UpdatePassword"])->name('update.password');

    // Resend Email Verfication
    Route::get('verify-email-verification', [App\Http\Controllers\User\VerificationController::class, "verifyEmailVerification"])->name('verify.email.verification');
    Route::get('resend-email-verification', [App\Http\Controllers\User\VerificationController::class, "resendEmailVerification"])->name('resend.email.verification');

    // Booking
    Route::post('appointment/book', [App\Http\Controllers\User\BookingController::class, 'appointmentBooking'])->name('appointment.book');
    Route::get('book-appointment/{business_id}', [App\Http\Controllers\User\BookingController::class, 'booking'])->name('book-appointment.index');

    // Ajax Routes For User
    Route::get('user/fetch-slots', [App\Http\Controllers\AjaxController::class, 'slots'])->name('fetch.slots');

    // My Bookings
    Route::get('my-bookings', [App\Http\Controllers\User\BookingController::class, 'myBookings'])->name('my-bookings');

    // Booking Cancel
    Route::post('booking/cancel/{booking_id}', [App\Http\Controllers\User\BookingController::class, "cancelBooking"])->name('booking.cancel');

    // My Transactions
    Route::get('my-transactions', [App\Http\Controllers\User\TransactionController::class, 'myTransactions'])->name('my-transactions');
    Route::get('view-invoice/{id}', [App\Http\Controllers\User\TransactionController::class, "viewInvoice"])->name('view.invoice');
  });

  // Payment Routes
  Route::group(['middleware' => 'checkType'], function () {
    // Choose Payment Gateway for Business
    Route::post('/prepare-payment/{planId}', [App\Http\Controllers\Payment\PaymentController::class, "preparePaymentGateway"])->name('prepare.payment.gateway');

    // PayPal Payment Gateway
    Route::get('/payment-paypal/{planId}', [App\Http\Controllers\Payment\PaypalController::class, "paywithpaypal"])->name('paywithpaypal');
    Route::get('/payment/status', [App\Http\Controllers\Payment\PaypalController::class, "paypalPaymentStatus"])->name('paypalPaymentStatus');

    // RazorPay
    Route::get('/payment-razorpay/{planId}', [App\Http\Controllers\Payment\RazorPayController::class, "prepareRazorpay"])->name('paywithrazorpay');
    Route::get('/razorpay-payment-status/{oid}/{paymentId}', [App\Http\Controllers\Payment\RazorPayController::class, "razorpayPaymentStatus"])->name('razorpay.payment.status');

    // Phonepe
    Route::any('/phonepe-payment-status', [App\Http\Controllers\Payment\PhonepeController::class, 'phonepePaymentStatus'])->name('phonepe.payment.status');
    Route::get('/payment-phonepe/{planId}', [App\Http\Controllers\Payment\PhonepeController::class, 'preparePhonpe'])->name('paywithphonepe');

    // Stripe
    Route::get('/payment-stripe/{planId}', [App\Http\Controllers\Payment\StripeController::class, "stripeCheckout"])->name('paywithstripe');
    Route::post('/stripe-payment-status/{paymentId}', [App\Http\Controllers\Payment\StripeController::class, "stripePaymentStatus"])->name('stripe.payment.status');
    Route::get('/stripe-payment-cancel/{paymentId}', [App\Http\Controllers\Payment\StripeController::class, "stripePaymentCancel"])->name('stripe.payment.cancel');

    // OnePay routes
    Route::get('/paywithonepay/{planId}', [App\Http\Controllers\Payment\OnepayController::class, 'redirectToOnepay'])->name('paywithonepay');

    // Paystack
    Route::get('/paystack-payment/callback', [App\Http\Controllers\Payment\PaystackController::class, 'paystackHandleGatewayCallback'])->name('paystack.handle.gateway.callback');
    Route::get('/payment-paystack/{planId}', [App\Http\Controllers\Payment\PaystackController::class, "paystackCheckout"])->name('paywithpaystack');

    // Mollie
    Route::get('/mollie-payment-status', [App\Http\Controllers\Payment\MollieController::class, "molliePaymentStatus"])->name('mollie.payment.status');
    Route::get('/payment-mollie/{planId}', [App\Http\Controllers\Payment\MollieController::class, "prepareMollie"])->name('paywithmollie');

    // Offline
    Route::post('/mark-offline-payment', [App\Http\Controllers\Payment\OfflineController::class, "markOfflinePayment"])->name('mark.payment.payment');
    Route::get('/payment-offline/{planId}', [App\Http\Controllers\Payment\OfflineController::class, "offlineCheckout"])->name('paywithoffline');

    // Mercado Pago
    Route::get('/mercadopago-payment-status', [App\Http\Controllers\Payment\MercadoPagoController::class, "mercadoPagoPaymentStatus"])->name('mercadopago.payment.status');
    Route::get('/payment-mercadopago/{planId}', [App\Http\Controllers\Payment\MercadoPagoController::class, "prepareMercadoPago"])->name('paywithmercadopago');

    // Choose Payment Gateway for User
    Route::post('/prepare-payment-booking/{bookingId}', [App\Http\Controllers\Payment\PaymentController::class, "preparePaymentGateway"])->name('prepare.payment.gateway');

    // PayPal Payment Gateway
    Route::get('/booking/paypal/status', [App\Http\Controllers\User\Payment\PaypalController::class, "bookingPaypalPaymentStatus"])->name('bookingPaymentPaypalStatus');
    Route::get('/booking-payment-paypal/{bookingId}', [App\Http\Controllers\User\Payment\PaypalController::class, "paywithpaypal"])->name('bookingPaymentWithPaypal');

    // RazorPay
    Route::get('/booking-payment-razorpay/{bookingId}', [App\Http\Controllers\User\Payment\RazorPayController::class, "prepareRazorpay"])->name('bookingPaymentWithRazorpay');
    Route::get('/razorpay-booking-payment-status/{oid}/{paymentId}', [App\Http\Controllers\User\Payment\RazorPayController::class, "razorpayPaymentStatus"])->name('razorpay.booking.payment.status');

    // Phonepe
    Route::any('/phonepe-booking-payment-status', [App\Http\Controllers\User\Payment\PhonepeController::class, "phonepePaymentStatus"])->name('booking.payment.phonepe.status');
    Route::get('/booking-payment-phonepe/{bookingId}', [App\Http\Controllers\User\Payment\PhonepeController::class, 'preparePhonpe'])->name('bookingPaymentWithPhonepe');

    // Stripe
    Route::get('/booking-payment-stripe/{bookingId}', [App\Http\Controllers\User\Payment\StripeController::class, "stripeCheckout"])->name('bookingPaymentWithStripe');
    Route::post('/booking-payment-stripe-status/{paymentId}', [App\Http\Controllers\User\Payment\StripeController::class, "stripePaymentStatus"])->name('booking.payment.stripe.status');
    Route::get('/stripe-booking-payment-cancel/{paymentId}', [App\Http\Controllers\User\Payment\StripeController::class, "stripePaymentCancel"])->name('booking.stripe.payment.cancel');

    // Paystack
    Route::get('/paystack-payment-booking/callback', [App\Http\Controllers\User\Payment\PaystackController::class, "paystackHandleGatewayCallback"])->name('booking.paystack.handle.gateway.callback');
    Route::get('/booking-payment-paystack/{bookingId}', [App\Http\Controllers\User\Payment\PaystackController::class, "paystackCheckout"])->name('bookingPaymentWithPaystack');

    // Mollie
    Route::get('/mollie-booking-payment/status', [App\Http\Controllers\User\Payment\MollieController::class, "molliePaymentStatus"])->name('booking.payment.mollie.status');
    Route::get('/booking-payment-mollie/{bookingId}', [App\Http\Controllers\User\Payment\MollieController::class, "prepareMollie"])->name('bookingPaymentWithMollie');

    // Offline
    Route::post('/mark-offline-booking-payment', [App\Http\Controllers\User\Payment\OfflineController::class, "markOfflinePayment"])->name('mark.booking.payment.payment');
    Route::get('/booking-payment-offline/{bookingId}', [App\Http\Controllers\User\Payment\OfflineController::class, "offlineCheckout"])->name('bookingPaymentWithOffline');

    // Mercado Pago
    Route::get('/mercadopago-booking-payment-status', [App\Http\Controllers\User\Payment\MercadoPagoController::class, "mercadoPagoPaymentStatus"])->name('booking.payment.mercadopago.status');
    Route::get('/booking-payment-mercadopago/{bookingId}', [App\Http\Controllers\User\Payment\MercadoPagoController::class, "prepareMercadoPago"])->name('bookingPaymentWithMercadoPago');
  });

  Route::get('/payonepay/{bookingId}', [App\Http\Controllers\Payment\OnepayController::class, 'bookingOnepayNewtwo'])->name('user.appointment.booktest');
  Route::get('/onepay/booking/callback', [App\Http\Controllers\Payment\OnepayController::class, 'handleBookingCallback'])->name('user.booking.callback');

    Route::get('/booking-payment-onepay/{bookingId}', [App\Http\Controllers\Payment\OnepayController::class, "bookingOnepay"])->name('bookingOnepaytest');

});
