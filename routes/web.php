<?php

use App\Http\Controllers\Auth\MenuController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Master\BundlingController;
use App\Http\Controllers\Master\ItemController;
use App\Http\Controllers\Master\WarehouseController;
use App\Http\Controllers\Master\UomController;
use App\Http\Controllers\Transaction\InvoiceController;
use App\Http\Controllers\Master\VendorController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Pos\PosController;
use App\Http\Controllers\Pos\PesananOnlineController;
use App\Http\Controllers\Transaction\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::post('/login/employee', [App\Http\Controllers\Auth\LoginController::class, 'employeeLoginSubmit'])->name('loginSubmit');

Route::get('/tes', [App\Http\Controllers\HomeController::class, 'tes']);
Route::get('/print', [InvoiceController::class, 'print'])->name('print');

Route::name('pesanan-online.')->prefix('pesanan-online')->group(function () {
    Route::post('/card', [PesananOnlineController::class, 'card'])->name('card');
    Route::get('/items/data', [ItemController::class, 'data'])->name('items.data');
    Route::post('/add-to-cart', [PesananOnlineController::class, 'addToCart'])->name('add-to-cart');
    Route::put('/batal/{id}', [PesananOnlineController::class, 'batal',])->name('batal');
    Route::get('/invoice/{id}', [PesananOnlineController::class, 'invoice',])->name('invoice');
    Route::get('/struk/{id}', [PesananOnlineController::class, 'struk',])->name('struk');
    Route::post('/cari', [PesananOnlineController::class, 'cari'])->name('cari');
    Route::post('/getItemByCategory', [ItemController::class, 'getItemByCategory'])->name('getItemByCategory');
    Route::post('/getItemByBundling', [BundlingController::class, 'getItemByBundling'])->name('getItemByBundling');
    Route::resource('', PesananOnlineController::class, ['parameters' => ['' => 'id']]);
});

Route::middleware('auth:employee')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::name('auth.')->prefix('auth')->group(function () {
        Route::name('user.')->prefix('user')->group(function () {
            Route::post('/datatable', [UserController::class, 'datatable'])->name('datatable');
            Route::resource('', UserController::class, ['parameters' => ['' => 'id']]);
        });
        Route::name('role.')->prefix('role')->group(function () {
            Route::post('/datatable', [RoleController::class, 'datatable'])->name('datatable');
            Route::resource('', RoleController::class, ['parameters' => ['' => 'id']]);
        });
        Route::name('permission.')->prefix('permission')->group(function () {
            Route::post('/datatable', [PermissionController::class, 'datatable'])->name('datatable');
            Route::resource('', PermissionController::class, ['parameters' => ['' => 'id']]);
        });
        Route::name('menu.')->prefix('menu')->group(function () {
            Route::post('/datatable', [MenuController::class, 'datatable'])->name('datatable');
            Route::resource('', MenuController::class, ['parameters' => ['' => 'id']]);
        });
        // Route::name('wilayah.')->prefix('wilayah')->group(function () {
        //     Route::get('/data', [WilayahController::class, 'data'])->name('data');
        //     Route::get('/data/branch', [WilayahController::class, 'dataBranch'])->name('dataBranch');
        //     Route::get('/data/user', [WilayahController::class, 'dataUser'])->name('dataUser');
        //     Route::get('/data/vendor', [WilayahController::class, 'dataVendor'])->name('dataVendor');
        //     Route::get('/data/customer', [WilayahController::class, 'data/customer'])->name('data/customer');
        //     Route::resource('', MenuController::class, ['parameters' => ['' => 'id']]);
        // });
    });
    Route::name('items.')->prefix('items')->group(function () {
        Route::get('/data', [ItemController::class, 'data'])->name('data');
        Route::get('/formUpload', [ItemController::class, 'formUpload'])->name('formUpload');
        Route::get('/formatExcel', [ItemController::class, 'formatExcel'])->name('formatExcel');
        Route::post('/uploadExcel', [ItemController::class, 'uploadExcel'])->name('uploadExcel');
        Route::post('/datatable', [ItemController::class, 'datatable'])->name('datatable');
        Route::post('/getItemByCategory', [ItemController::class, 'getItemByCategory'])->name('getItemByCategory');
        Route::get('/formUpdateUpload', [ItemController::class, 'formUpdateUpload'])->name('formUpdateUpload');
        Route::get('/formatUpdateExcel', [ItemController::class, 'formatUpdateExcel'])->name('formatUpdateExcel');
        Route::post('/updateuploadExcel', [ItemController::class, 'updateuploadExcel'])->name('updateuploadExcel');
        Route::post('/datatable', [ItemController::class, 'datatable'])->name('datatable');
        
        Route::get('/create-bundling', [ItemController::class, 'createBundling'])->name('createBundling');
        Route::post('/storeBundling', [ItemController::class, 'storeBundling'])->name('storeBundling');
        Route::get('/add-bundling', [ItemController::class, 'addBundling'])->name('addBundling');
        Route::resource('', ItemController::class, ['parameters' => ['' => 'id']]);
    });

    Route::name('bundling.')->prefix('bundling')->group(function () {
        Route::get('/data', [BundlingController::class, 'data'])->name('data');
        Route::post('/datatable', [BundlingController::class, 'datatable'])->name('datatable');
        Route::post('/getItemByBundling', [BundlingController::class, 'getItemByBundling'])->name('getItemByBundling');
        Route::get('/add-bundling', [BundlingController::class, 'addBundling'])->name('addBundling');
        Route::resource('', BundlingController::class, ['parameters' => ['' => 'id']]);
    });

    Route::name('master.')->prefix('master')->group(function () {
        Route::name('warehouse.')->prefix('warehouse')->group(function () {
            Route::get('/data', [WarehouseController::class, 'data'])->name('data');
            Route::get('/formUpload', [WarehouseController::class, 'formUpload'])->name('formUpload');
            Route::get('/formatExcel', [WarehouseController::class, 'formatExcel'])->name('formatExcel');
            Route::post('/uploadExcel', [WarehouseController::class, 'uploadExcel'])->name('uploadExcel');
            Route::post('/datatable', [WarehouseController::class, 'datatable'])->name('datatable');
            Route::resource('', WarehouseController::class, ['parameters' => ['' => 'id']]);
        });

        Route::name('uom.')->prefix('uom')->group(function () {
            Route::get('/data', [UomController::class, 'data'])->name('data');
            Route::get('/formUpload', [UomController::class, 'formUpload'])->name('formUpload');
            Route::get('/formatExcel', [UomController::class, 'formatExcel'])->name('formatExcel');
            Route::post('/uploadExcel', [UomController::class, 'uploadExcel'])->name('uploadExcel');
            Route::post('/datatable', [UomController::class, 'datatable'])->name('datatable');
            Route::resource('', UomController::class, ['parameters' => ['' => 'id']]);
        });

        Route::name('category.')->prefix('category')->group(function () {
            Route::get('/data', [CategoryController::class, 'data'])->name('data');
            Route::get('/formUpload', [CategoryController::class, 'formUpload'])->name('formUpload');
            Route::get('/formatExcel', [CategoryController::class, 'formatExcel'])->name('formatExcel');
            Route::post('/uploadExcel', [CategoryController::class, 'uploadExcel'])->name('uploadExcel');
            Route::post('/datatable', [CategoryController::class, 'datatable'])->name('datatable');
            Route::resource('', CategoryController::class, ['parameters' => ['' => 'id']]);
        });

        Route::name('vendor.')->prefix('vendor')->group(function () {
            Route::get('/data', [VendorController::class, 'data'])->name('data');
            Route::get('/formUpload', [VendorController::class, 'formUpload'])->name('formUpload');
            Route::get('/formatExcel', [VendorController::class, 'formatExcel'])->name('formatExcel');
            Route::post('/uploadExcel', [VendorController::class, 'uploadExcel'])->name('uploadExcel');
            Route::post('/datatable', [VendorController::class, 'datatable'])->name('datatable');
            Route::resource('', VendorController::class, ['parameters' => ['' => 'id']]);
        });
    });

    Route::name('invoice.')->prefix('invoice')->group(function () {
        Route::post('/datatable', [InvoiceController::class, 'datatable'])->name('datatable');
        Route::post('/addItem', [InvoiceController::class, 'addItem'])->name('addItem');
        Route::get('/download/{id}', [InvoiceController::class, 'download'])->name('download');
        Route::get('/receipt/{id}', [InvoiceController::class, 'receipt'])->name('receipt');
        Route::resource('', InvoiceController::class, ['parameters' => ['' => 'id']]);
    });
    Route::name('pos.')->prefix('pos')->group(function () {
        // Route::get('/download/{id}', [InvoiceController::class, 'download'])->name('download');
        Route::post('/datatable', [PosController::class, 'datatable'])->name('datatable');
        Route::post('/add-to-cart', [PosController::class, 'addToCart'])->name('add-to-cart');
        Route::put('/batal/{id}', [PosController::class, 'batal',])->name('batal');
        Route::get('/invoice/{id}', [PosController::class, 'invoice',])->name('invoice');
        Route::get('/struk/{id}', [PosController::class, 'struk',])->name('struk');
        Route::post('/cari', [PosController::class, 'cari'])->name('cari');
        Route::resource('', PosController::class, ['parameters' => ['' => 'id']]);
    });

    Route::name('report.')->prefix('report')->group(function () {
        Route::post('/transaction', [ReportController::class, 'transaction'])->name('transaction');
        Route::post('/detailTable', [ReportController::class, 'detailTable'])->name('detailTable');
        Route::get('/transaction/export/{date}', [ReportController::class, 'exportTransaction',])->name('exportTransaction');
        Route::post('/transaction/print', [ReportController::class, 'printTransaction',])->name('printTransaction');
        Route::post('/transaction/detail/print', [ReportController::class, 'printDetailTransaction',])->name('printDetailTransaction');
        Route::get('/detail', [ReportController::class, 'detail',])->name('detail');
        Route::get('/detail/export/{date}', [ReportController::class, 'exportDetail',])->name('exportDetail');
        Route::resource('', ReportController::class, ['parameters' => ['' => 'id']]);
    });
});
