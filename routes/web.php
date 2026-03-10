<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemBatchController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BudgetController;
use App\Http\Controllers\Admin\SalaryController;
use App\http\Controllers\Gudang\GudangSaldoController;
use App\Http\Controllers\Admin\RecipientController;
use App\Http\Controllers\Admin\ProductionPlanController;

// ==================== ROOT & AUTH ==================== //
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role ?? 'user';

    return match (strtolower($role)) {
        'admin'   => redirect()->route('admin.dashboard'),
        'gudang'  => redirect()->route('gudang.dashboard'),
        'dapur'   => redirect()->route('dapur.dashboard'),
        'kurir'   => redirect()->route('kurir.dashboard'),
        default   => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== ROUTE ROLE-BASED ==================== //
Route::middleware(['auth'])->group(function () {

    // Profile (Umum)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// --- ADMIN ---///
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Budget Management
    Route::get('/budget', [BudgetController::class, 'index'])->name('budget.index');
    Route::post('/budget', [BudgetController::class, 'store'])->name('budget.store');

    // Allocation
    Route::post('/budget/allocation', [BudgetController::class, 'storeAllocation'])->name('budget.allocation');
    Route::delete('/budget/allocation/{id}', [BudgetController::class, 'destroyAllocation'])->name('budget.destroyAllocation');

    // Budget Requests (Gudang ke Admin) - SEKARANG JADI admin.budget.request
    Route::get('/budget/request', [BudgetController::class, 'requestIndex'])->name('budget.request');
    Route::post('/budget/request/approve/{id}', [BudgetController::class, 'approveRequest'])->name('budget.approve');
    
    // Management Recipient
    Route::get('/recipient', [RecipientController::class, 'index'])->name('recipient.index');
    Route::get('/recipient/create', [RecipientController::class, 'create'])->name('recipient.create');
    Route::post('/recipient', [RecipientController::class, 'store'])->name('recipient.store');
    Route::get('/recipient/{id}/edit', [RecipientController::class, 'edit'])->name('recipient.edit');
    Route::put('/recipient/{id}', [RecipientController::class, 'update'])->name('recipient.update');
    
    // production plan
    Route::resource('production_plan', ProductionPlanController::class);
    Route::patch('production_plan/{id}/update-status', [ProductionPlanController::class, 'updateStatus'])->name('production_plan.update-status');
    Route::get('/production_plan/{id}/edit', [ProductionPlanController::class, 'edit'])->name('production_plan.edit');
    Route::put('/production_plan/{id}', [ProductionPlanController::class, 'update'])->name('production_plan.update');
    Route::get('/production_plan/create', [ProductionPlanController::class, 'create'])->name('production_plan.create');



    // Salary
        Route::get('/salary', [SalaryController::class, 'index'])->name('salary.index');
        Route::post('/salary/config', [SalaryController::class, 'storeConfig'])->name('salary.storeConfig');
        Route::put('/salary/config/{id}', [SalaryController::class, 'updateConfig'])->name('salary.updateConfig');
        Route::delete('/salary/config/{id}', [SalaryController::class, 'destroyConfig'])->name('salary.destroyConfig');
        Route::post('/salary/payment', [SalaryController::class, 'processPayment'])->name('salary.payment');
    });

// --- GUDANG ---////
    Route::middleware('role:gudang')->prefix('gudang')->group(function () {
        Route::get('/', [App\Http\Controllers\Gudang\DashboardController::class, 'index'])->name('gudang.dashboard');

        // Master Bahan Baku
        Route::get('/item', [ItemController::class, 'index'])->name('item.index');
        Route::post('/item/store', [ItemController::class, 'store'])->name('item.store');
        Route::delete('/item/delete/{id}', [ItemController::class, 'destroy'])->name('item.destroy');

        // Monitoring Stok & Opname
        Route::get('/stok', [ItemBatchController::class, 'index'])->name('stok.index');
        Route::get('/stok-opname', [ItemBatchController::class, 'opnameIndex'])->name('stok.opname.index');
        Route::post('/stok-opname/{id}', [ItemBatchController::class, 'processOpname'])->name('stok.opname.process');

        // Saldo Anggaran
            Route::middleware(['auth', 'role:gudang'])->prefix('gudang')->name('gudang.')->group(function () {
            Route::get('/saldo', [GudangSaldoController::class, 'index'])->name('saldo.index');
            Route::post('/saldo/request', [GudangSaldoController::class, 'storeRequest'])->name('saldo.request');
        });
        // Penerimaan Barang
        Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
        Route::get('/penerimaan/input', [PenerimaanController::class, 'create'])->name('penerimaan.input');
        Route::post('/penerimaan/store', [PenerimaanController::class, 'store'])->name('penerimaan.store');

        // Approval Request dari Dapur
        Route::prefix('/request')->group(function () {
            Route::get('/', [RequestController::class, 'index'])->name('gudang.request.index');
            Route::post('/{id}/approve', [RequestController::class, 'approve'])->name('request.approve');
            Route::get('/{id}/detail', [RequestController::class, 'getDetail'])->name('request.detail');
        });
    });

    // --- DAPUR ---
    Route::middleware('role:dapur')->prefix('dapur')->group(function () {
        Route::get('/', [App\Http\Controllers\Dapur\DashboardController::class, 'index'])->name('dapur.dashboard');

        // Master Resep
        Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
        Route::get('/menu/create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');
        Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
        Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');

        // Produksi
        Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
        Route::post('/production', [ProductionController::class, 'store'])->name('production.store');
        Route::post('/production/{id}/status', [ProductionController::class, 'updateStatus'])->name('production.updateStatus');

        // Monitoring Stok Dapur & Fitur Request
        // PREFIX /stok agar URL menjadi /dapur/stok/...
        Route::prefix('/stok')->group(function () {
            Route::get('/', [RequestController::class, 'kitchenStock'])->name('dapur.stok.index');
            Route::get('/riwayat', [RequestController::class, 'index'])->name('dapur.request.index');
            Route::get('/create', [RequestController::class, 'create'])->name('dapur.request.create');
            Route::post('/store', [RequestController::class, 'store'])->name('dapur.request.store');
        });
    });

    // --- KURIR ---
    Route::middleware('role:kurir')->prefix('kurir')->group(function () {
        Route::get('/', function () {
            return view('kurir.dashboard');
        })->name('kurir.dashboard');
    });
});

require __DIR__.'/auth.php';