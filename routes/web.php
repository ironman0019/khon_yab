<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home routes (public)
Route::prefix('/')->name('home.')->group(function () {
    Route::get('/', [App\Http\Controllers\Home\HomeController::class, 'index'])->name('index');
    Route::get('/contact', [App\Http\Controllers\Home\HomeController::class, 'contact'])->name('contact');
    Route::get('/about', [App\Http\Controllers\Home\HomeController::class, 'about'])->name('about');
    Route::get('/search', [App\Http\Controllers\Home\HomeController::class, 'search'])->name('search');
});

Route::get('/language/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])
    ->name('language.switch');

Route::get('/api/cities', function (Illuminate\Http\Request $request) {
    if (! $request->has('province_id')) {
        return response()->json([]);
    }

    $cities = App\Models\City::where('province_id', $request->province_id)
        ->select('id', 'name')
        ->get();

    return response()->json($cities);
})->name('api.cities');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard.index');
    }

    return match ($user->user_type) {
        \App\Enums\UserType::Receiver->value => redirect()->route('receiver.dashboard.index'),
        \App\Enums\UserType::Donor->value => redirect()->route('donor.dashboard.index'),
        \App\Enums\UserType::Laboratory->value => redirect()->route('laboratory.dashboard.index'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Donor Dashboard
Route::prefix('donor')->middleware(['auth', 'verified'])->name('donor.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Donor\DashboardController::class, 'index'])->name('dashboard.index');

    // Donation Records
    Route::resource('donation-records', App\Http\Controllers\Donor\BloodDonationRecordController::class);
    Route::post('donation-records/{donation_record}/cancel', [App\Http\Controllers\Donor\BloodDonationRecordController::class, 'cancel'])->name('donation-records.cancel');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Donor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Donor\ProfileController::class, 'update'])->name('profile.update');

    // Reports
    Route::get('/reports', [App\Http\Controllers\Donor\DashboardController::class, 'reports'])->name('reports');

    // Messages
    Route::get('/messages', [App\Http\Controllers\Donor\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [App\Http\Controllers\Donor\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [App\Http\Controllers\Donor\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{user}', [App\Http\Controllers\Donor\MessageController::class, 'show'])->name('messages.show');
});

// Laboratory Dashboard
Route::prefix('laboratory')->middleware(['auth', 'verified'])->name('laboratory.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Laboratory\DashboardController::class, 'index'])->name('dashboard.index');

    // Blood Requests
    Route::resource('blood-requests', App\Http\Controllers\Laboratory\BloodRequestController::class);
    Route::get('blood-requests/{blood_request}/print', [App\Http\Controllers\Laboratory\BloodRequestController::class, 'print'])->name('blood-requests.print');

    // Blood Donation Records
    Route::resource('donation-records', App\Http\Controllers\Laboratory\BloodDonationRecordController::class);
    Route::get('donation-records/{donation_record}/print', [App\Http\Controllers\Laboratory\BloodDonationRecordController::class, 'print'])->name('donation-records.print');
    Route::get('donation-records/{donation_record}/test/create', [App\Http\Controllers\Laboratory\BloodDonationRecordController::class, 'createTest'])->name('donation-records.test.create');
    Route::post('donation-records/{donation_record}/test', [App\Http\Controllers\Laboratory\BloodDonationRecordController::class, 'storeTest'])->name('donation-records.test.store');
    Route::get('donation-records/{donation_record}/test/edit', [App\Http\Controllers\Laboratory\BloodDonationRecordController::class, 'editTest'])->name('donation-records.test.edit');
    Route::put('donation-records/{donation_record}/test', [App\Http\Controllers\Laboratory\BloodDonationRecordController::class, 'updateTest'])->name('donation-records.test.update');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Laboratory\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Laboratory\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/receipts/download', [App\Http\Controllers\Laboratory\ProfileController::class, 'downloadReceipts'])->name('receipts.download');

    // Messages
    Route::get('/messages', [App\Http\Controllers\Laboratory\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [App\Http\Controllers\Laboratory\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [App\Http\Controllers\Laboratory\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{user}', [App\Http\Controllers\Laboratory\MessageController::class, 'show'])->name('messages.show');
});

// Receiver Dashboard
Route::prefix('receiver')->middleware(['auth', 'verified'])->name('receiver.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Receiver\DashboardController::class, 'index'])->name('dashboard.index');

    // Blood Requests
    Route::resource('blood-requests', App\Http\Controllers\Receiver\BloodRequestController::class);
    Route::get('blood-requests/{blood_request}/print', [App\Http\Controllers\Receiver\BloodRequestController::class, 'print'])->name('blood-requests.print');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Receiver\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Receiver\ProfileController::class, 'update'])->name('profile.update');

    // Messages
    Route::get('/messages', [App\Http\Controllers\Receiver\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [App\Http\Controllers\Receiver\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [App\Http\Controllers\Receiver\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{user}', [App\Http\Controllers\Receiver\MessageController::class, 'show'])->name('messages.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Messages
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{message}/read', [App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages/conversation/{user}/read', [App\Http\Controllers\MessageController::class, 'markConversationAsRead'])->name('messages.conversation.read');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/notifications', [App\Http\Controllers\Admin\DashboardController::class, 'notifications'])->name('notifications');

    // Messages
    Route::get('/messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/unread-count', [App\Http\Controllers\Admin\MessageController::class, 'unreadCount'])->name('messages.unread-count');
    Route::get('/messages/create', [App\Http\Controllers\Admin\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [App\Http\Controllers\Admin\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{user}', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/read', [App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages/conversation/{user}/read', [App\Http\Controllers\Admin\MessageController::class, 'markConversationAsRead'])->name('messages.conversation.read');

    // User Management
    Route::resource('user-management', App\Http\Controllers\Admin\UserManagement\UserController::class);
    Route::post('user-management/{user}/toggle-admin', [App\Http\Controllers\Admin\UserManagement\UserController::class, 'toggleAdmin'])->name('user-management.toggle-admin');

    // Donor Management
    Route::resource('donor-management', App\Http\Controllers\Admin\DonorManagement\DonorController::class);
    Route::post('donor-management/{donor}/toggle-health-status', [App\Http\Controllers\Admin\DonorManagement\DonorController::class, 'toggleHealthStatus'])->name('donor-management.toggle-health-status');
    Route::post('donor-management/{donor}/toggle-donation-ability', [App\Http\Controllers\Admin\DonorManagement\DonorController::class, 'toggleDonationAbility'])->name('donor-management.toggle-donation-ability');

    // Receiver Management
    Route::resource('receiver-management', App\Http\Controllers\Admin\ReceiverManagement\ReceiverController::class);

    // Laboratory Management
    Route::resource('laboratory-management', App\Http\Controllers\Admin\LaboratoryManagement\LaboratoryController::class);
    Route::post('laboratory-management/{laboratory}/toggle-status', [App\Http\Controllers\Admin\LaboratoryManagement\LaboratoryController::class, 'toggleStatus'])->name('laboratory-management.toggle-status');

    // Blood Request Management
    Route::resource('blood-request-management', App\Http\Controllers\Admin\BloodRequestManagement\BloodRequestController::class)
        ->parameters(['blood-request-management' => 'bloodRequest']);
    Route::post('blood-request-management/{blood_request}/approve', [App\Http\Controllers\Admin\BloodRequestManagement\BloodRequestController::class, 'approve'])->name('blood-request-management.approve');
    Route::post('blood-request-management/{blood_request}/reject', [App\Http\Controllers\Admin\BloodRequestManagement\BloodRequestController::class, 'reject'])->name('blood-request-management.reject');
    Route::post('blood-request-management/{blood_request}/complete', [App\Http\Controllers\Admin\BloodRequestManagement\BloodRequestController::class, 'complete'])->name('blood-request-management.complete');

    // Blood Inventory Management
    Route::resource('inventory-management', App\Http\Controllers\Admin\InventoryManagement\BloodInventoryController::class);
    Route::post('inventory-management/{blood_inventory}/mark-as-used', [App\Http\Controllers\Admin\InventoryManagement\BloodInventoryController::class, 'markAsUsed'])->name('inventory-management.mark-as-used');
    Route::post('inventory-management/{blood_inventory}/mark-as-expired', [App\Http\Controllers\Admin\InventoryManagement\BloodInventoryController::class, 'markAsExpired'])->name('inventory-management.mark-as-expired');

    // Blood Donation Record Management
    Route::resource('blood-donation-management', App\Http\Controllers\Admin\BloodDonationManagement\BloodDonationRecordController::class);
    Route::get('blood-donation-management/{blood_donation_record}/print', [App\Http\Controllers\Admin\BloodDonationManagement\BloodDonationRecordController::class, 'printReceipt'])->name('blood-donation-management.print');
    Route::get('blood-donation-management/{blood_donation_management}/test/create', [App\Http\Controllers\Admin\BloodDonationManagement\BloodDonationRecordController::class, 'createTest'])->name('blood-donation-management.test.create');
    Route::post('blood-donation-management/{blood_donation_management}/test', [App\Http\Controllers\Admin\BloodDonationManagement\BloodDonationRecordController::class, 'storeTest'])->name('blood-donation-management.test.store');
    Route::get('blood-donation-management/{blood_donation_management}/test/edit', [App\Http\Controllers\Admin\BloodDonationManagement\BloodDonationRecordController::class, 'editTest'])->name('blood-donation-management.test.edit');
    Route::put('blood-donation-management/{blood_donation_management}/test', [App\Http\Controllers\Admin\BloodDonationManagement\BloodDonationRecordController::class, 'updateTest'])->name('blood-donation-management.test.update');

    // Language Management
    Route::resource('language-management', App\Http\Controllers\Admin\LanguageManagement\LanguageController::class)
        ->parameters(['language-management' => 'language']);
    Route::post('language-management/{language}/toggle-active', [App\Http\Controllers\Admin\LanguageManagement\LanguageController::class, 'toggleActive'])->name('language-management.toggle-active');
    Route::post('language-management/{language}/set-default', [App\Http\Controllers\Admin\LanguageManagement\LanguageController::class, 'setDefault'])->name('language-management.set-default');

    // Translation Management
    Route::resource('language-management.translations', App\Http\Controllers\Admin\LanguageManagement\TranslationController::class)
        ->parameters(['language-management' => 'language']);
    Route::post('language-management/translations/import', [App\Http\Controllers\Admin\LanguageManagement\TranslationController::class, 'importFromFiles'])->name('language-management.translations.import');

    // Province/City Management
    Route::resource('province-management', App\Http\Controllers\Admin\ProvinceManagement\ProvinceController::class);
    Route::resource('province-management.cities', App\Http\Controllers\Admin\ProvinceManagement\CityController::class);

    // Reports Management
    Route::get('reports-management', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'index'])->name('reports-management.index');
    Route::get('reports-management/donations', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'donations'])->name('reports-management.donations');
    Route::get('reports-management/blood-requests', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'bloodRequests'])->name('reports-management.blood-requests');
    Route::get('reports-management/inventory', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'inventory'])->name('reports-management.inventory');
    Route::get('reports-management/user-statistics', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'userStatistics'])->name('reports-management.user-statistics');
    Route::get('reports-management/summary', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'summary'])->name('reports-management.summary');
    Route::get('reports-management/active-donors', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'activeDonors'])->name('reports-management.active-donors');
    Route::get('reports-management/shortage', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'shortage'])->name('reports-management.shortage');
    Route::get('reports-management/approved-requests', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'approvedRequests'])->name('reports-management.approved-requests');
    Route::get('reports-management/donation-history', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'donationHistory'])->name('reports-management.donation-history');
    Route::get('reports-management/by-province', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'byProvince'])->name('reports-management.by-province');
    Route::get('reports-management/monthly-yearly', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'monthlyYearly'])->name('reports-management.monthly-yearly');
    Route::get('reports-management/bag-expiration', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'bagExpiration'])->name('reports-management.bag-expiration');

    // Reports Management - Excel Exports
    Route::get('reports-management/donations/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportDonations'])->name('reports-management.donations.export');
    Route::get('reports-management/blood-requests/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportBloodRequests'])->name('reports-management.blood-requests.export');
    Route::get('reports-management/inventory/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportInventory'])->name('reports-management.inventory.export');
    Route::get('reports-management/user-statistics/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportUserStatistics'])->name('reports-management.user-statistics.export');
    Route::get('reports-management/summary/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportSummary'])->name('reports-management.summary.export');
    Route::get('reports-management/active-donors/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportActiveDonors'])->name('reports-management.active-donors.export');
    Route::get('reports-management/shortage/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportShortage'])->name('reports-management.shortage.export');
    Route::get('reports-management/approved-requests/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportApprovedRequests'])->name('reports-management.approved-requests.export');
    Route::get('reports-management/donation-history/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportDonationHistory'])->name('reports-management.donation-history.export');
    Route::get('reports-management/by-province/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportByProvince'])->name('reports-management.by-province.export');
    Route::get('reports-management/monthly-yearly/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportMonthlyYearly'])->name('reports-management.monthly-yearly.export');
    Route::get('reports-management/bag-expiration/export', [App\Http\Controllers\Admin\ReportsManagement\ReportController::class, 'exportBagExpiration'])->name('reports-management.bag-expiration.export');

    // Database Backup
    Route::resource('backup-management', App\Http\Controllers\Admin\BackupManagement\BackupController::class)
        ->parameters(['backup-management' => 'backup']);
    Route::get('backup-management/{backup}/download', [App\Http\Controllers\Admin\BackupManagement\BackupController::class, 'download'])->name('backup-management.download');
    Route::post('backup-management/clean-old', [App\Http\Controllers\Admin\BackupManagement\BackupController::class, 'cleanOld'])->name('backup-management.clean-old');

    // Site Settings
    Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
