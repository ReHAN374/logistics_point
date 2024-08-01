<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('/');

Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::get('dashboard', [UserController::class, 'dashboard'])->middleware('auth')->name('dashboard');

Route::get('users', [UserController::class, 'customer'])->middleware('auth')->name('users');
Route::post('create_user', [UserController::class, 'create_user'])->name('create_user');
Route::post('edit_user', [UserController::class, 'edit_user'])->name('edit_user');
Route::post('update_user', [UserController::class, 'update_user'])->name('update_user');
Route::post('delete_user', [UserController::class, 'destroy_user'])->name('delete_user');

Route::get('product', [ProductController::class, 'index'])->middleware('auth')->name('product');
Route::post('create_product', [ProductController::class, 'store'])->name('create_product');
Route::post('edit_product', [ProductController::class, 'edit'])->name('edit_product');
Route::post('update_product', [ProductController::class, 'update'])->name('update_product');
Route::post('delete_product', [ProductController::class, 'destroy'])->name('delete_product');
Route::post('edit_product_by_code', [ProductController::class, 'single_product_data'])->name('edit_product_by_code');

Route::get('warehouse', [WarehouseController::class, 'index'])->middleware('auth')->name('warehouse');
Route::post('create_warehouse', [WarehouseController::class, 'store'])->name('create_warehouse');
Route::post('edit_warehouse', [WarehouseController::class, 'edit'])->name('edit_warehouse');
Route::post('update_warehouse', [WarehouseController::class, 'update'])->name('update_warehouse');
Route::post('delete_warehouse', [WarehouseController::class, 'destroy'])->name('delete_warehouse');

Route::get('invoice', [InvoiceController::class, 'index'])->middleware('auth')->name('invoice');
Route::post('create_invoice', [InvoiceController::class, 'store'])->name('create_invoice');
Route::post('invoice_items', [InvoiceController::class, 'show'])->name('invoice_items');
Route::post('invoice_delete', [InvoiceController::class, 'destroy'])->name('invoice_delete');

Route::get('invoice_report', [InvoiceController::class, 'invoice_report'])->name('invoice_report');
Route::get('invoice_sale_report', [InvoiceController::class, 'invoice_sale_report'])->name('invoice_sale_report');
Route::get('invoice_stats', [InvoiceController::class, 'invoice_stats'])->name('invoice_stats');
Route::post('issue_notes_for_report', [InvoiceController::class, 'issue_notes_for_report'])->name('issue_notes_for_report');
Route::get('invoice_outstanding_stats', [InvoiceController::class, 'invoice_outstanding_stats'])->name('invoice_outstanding_stats');

Route::get('issue_notes', [InvoiceController::class, 'issue_notes'])->middleware('auth')->name('issue_notes');
Route::post('issue_note_items', [InvoiceController::class, 'issue_note_items'])->name('issue_note_items');
Route::post('submit_issue_items', [InvoiceController::class, 'submit_issue_items'])->name('submit_issue_items');
Route::post('issue_note_delete', [InvoiceController::class, 'issue_note_delete'])->name('issue_note_delete');

Route::get('balance_notes', [InvoiceController::class, 'balance_notes'])->middleware('auth')->name('balance_notes');
Route::post('balance_note_items', [InvoiceController::class, 'balance_note_items'])->name('balance_note_items');
Route::post('submit_balance_items', [InvoiceController::class, 'submit_balance_items'])->name('submit_balance_items');

Route::get('delivery_notes', [InvoiceController::class, 'delivery_notes'])->middleware('auth')->name('delivery_notes');

Route::get('purchase_order', [PurchaseOrderController::class, 'index'])->middleware('auth')->name('purchase_order');
Route::post('create_purchase_order', [PurchaseOrderController::class, 'store'])->name('create_purchase_order');