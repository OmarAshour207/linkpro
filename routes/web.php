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

    Route::resource('office/contents', App\Http\Controllers\Dashboard\OfficeContentController::class);
    Route::post('contents/offices', [App\Http\Controllers\Dashboard\OfficeContentController::class, 'showOffices'])->name('contents.offices');

    Route::resource('supplies', App\Http\Controllers\Dashboard\SupplyController::class);

    // save tickets from users
    Route::get('tickets/{mode}', [App\Http\Controllers\Dashboard\TicketController::class, 'index'])->name('tickets');

    Route::post('tickets/supplies', [App\Http\Controllers\Dashboard\TicketController::class, 'supplies'])->name('tickets.supplies');

    Route::get('tickets/create/{mode}', [App\Http\Controllers\Dashboard\TicketController::class, 'create'])->name('tickets.create');
    Route::post('tickets/store/{mode}', [App\Http\Controllers\Dashboard\TicketController::class, 'store'])->name('tickets.store');

    Route::get('tickets/edit/{mode}/{ticket}', [App\Http\Controllers\Dashboard\TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('tickets/update/{mode}/{ticket}', [App\Http\Controllers\Dashboard\TicketController::class, 'update'])->name('tickets.update');

    Route::delete('tickets/destroy/{id}', [App\Http\Controllers\Dashboard\TicketController::class, 'destroy'])->name('tickets.destroy');

    Route::post('office/contents', [App\Http\Controllers\Dashboard\TicketController::class, 'showContents'])->name('office.contents');

    Route::get('profile/edit', [App\Http\Controllers\Dashboard\ProfileController::class, 'edit'])->name('edit.profile');
    Route::post('profile/edit', [App\Http\Controllers\Dashboard\ProfileController::class, 'update'])->name('update.profile');

    Route::post('/upload/image', [App\Http\Controllers\Dashboard\ImageController::class, 'uploadPhoto'])->name('upload.image');
    Route::post('/remove/image', [App\Http\Controllers\Dashboard\ImageController::class, 'removePhoto'])->name('remove.image');

    Route::get('/language/{locale}', [App\Http\Controllers\Dashboard\SettingController::class, 'changeLocale'])->name('language');

    Route::get('settings', [App\Http\Controllers\Dashboard\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings/store', [App\Http\Controllers\Dashboard\SettingController::class, 'store'])->name('settings.store');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
