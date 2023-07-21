<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    Route::get('index', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('services', App\Http\Controllers\Dashboard\ServiceController::class);

    Route::resource('companies', App\Http\Controllers\Dashboard\CompanyController::class);

    Route::resource('users', App\Http\Controllers\Dashboard\UserController::class);

    Route::resource('floors', App\Http\Controllers\Dashboard\FloorController::class);

    Route::resource('paths', App\Http\Controllers\Dashboard\PathController::class);
    Route::post('paths/floors', [App\Http\Controllers\Dashboard\PathController::class, 'showFloors'])->name('paths.floors');

    Route::resource('offices', App\Http\Controllers\Dashboard\OfficeController::class);
    Route::post('offices/paths', [App\Http\Controllers\Dashboard\OfficeController::class, 'showPaths'])->name('offices.paths');

    Route::resource('contents', App\Http\Controllers\Dashboard\ContentController::class);
    Route::post('contents/offices', [App\Http\Controllers\Dashboard\ContentController::class, 'showOffices'])->name('contents.offices');

    Route::resource('supplies', App\Http\Controllers\Dashboard\SupplyController::class);

    Route::resource('orders/tickets', \App\Http\Controllers\Dashboard\Order\TicketController::class);

    Route::resource('orders/requests', \App\Http\Controllers\Dashboard\Order\RequestController::class);

    Route::resource('orders/supplies', \App\Http\Controllers\Dashboard\Order\SupplyController::class, ['as' => 'orders']);

    Route::post('orders/supply/data', [\App\Http\Controllers\Dashboard\Order\SupplyController::class, 'supplies'])->name('orders.supply.data');

    Route::get('notifications/clear', [\App\Http\Controllers\Dashboard\NotificationController::class, 'clear'])->name('notifications.clear');

    Route::post('office/contents', [\App\Http\Controllers\Dashboard\Order\TicketController::class, 'showContents'])->name('office.contents');

    Route::post('comments/store', [\App\Http\Controllers\Dashboard\Order\CommentController::class, 'store'])->name('comments.store');

    Route::post('orders/export', [\App\Http\Controllers\DashboardController::class, 'export'])->name('orders.export');

    Route::get('profile/edit', [App\Http\Controllers\Dashboard\ProfileController::class, 'edit'])->name('edit.profile');
    Route::post('profile/edit', [App\Http\Controllers\Dashboard\ProfileController::class, 'update'])->name('update.profile');

    Route::post('/upload/image', [App\Http\Controllers\Dashboard\ImageController::class, 'uploadPhoto'])->name('upload.image');
    Route::post('/remove/image', [App\Http\Controllers\Dashboard\ImageController::class, 'removePhoto'])->name('remove.image');

    Route::get('/language/{locale}', [App\Http\Controllers\Dashboard\SettingController::class, 'changeLocale'])->name('language');

    Route::get('settings', [App\Http\Controllers\Dashboard\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings/store', [App\Http\Controllers\Dashboard\SettingController::class, 'store'])->name('settings.store');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
