<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

// هذه المجموعة تشمل كل المسارات التي تحتاج تسجيل دخول
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return view('admin.home'); // صفحة الادمن
        } else {
            return view('user.home'); // صفحة باقي الأدوار
        }
    })->name('dashboard');

    // مسارات إدارة المستخدمين (فقط للادمن عادة)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
    });

    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    Route::get('/receipt', function () {
        return view('receipt');
    })->name('receipt');

    Route::get('/deliveries', [DeliveryController::class, 'filter'])->name('deliveries.filter');
    Route::put('/deliveries/{id}', [DeliveryController::class, 'update']);
    Route::put('/deliveries/{id}', [DeliveryController::class, 'update'])->name('deliveries.update');
    Route::delete('/deliveries/{id}', [DeliveryController::class, 'destroy'])->name('deliveries.destroy');



Route::get('/exchanges', [ExchangeController::class, 'filter'])->name('exchanges.filter');

    Route::put('/exchanges/{id}', [ExchangeController::class, 'update']);
    Route::delete('/exchanges/{id}', [ExchangeController::class, 'destroy'])->name('exchanges.destroy');


    Route::get('/transactions', function () {
        return view('transactions');
    });
});
