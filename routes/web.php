<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Wave\Facades\Wave;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FinanzasController;

// Authentication routes
Auth::routes();

// Voyager Admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
     Route::get('finanzas',[FinanzasController::class, 'finanzas' ] )->name("IndexFinanzas");
});
Route::get('/admin/clientesuni/{id}', function ($id) {
    return \App\Models\clientes::select('nombres', 'telefono')->findOrFail($id);
});

Route::get('/admin/pedidos/{pedido}/factura', [FacturaController::class, 'generarFacturaImagen'])->name('pedidos.factura');
//Route::get('/admin/pedidos/{pedido}/estadistica', [FacturaController::class, 'descargarEstadistica'])->name('pedidos.estadistica');

Route::get('/admin/finanzas/pdf', [FinanzasController::class, 'finanzasPdf'])
    ->name('finanzas.pdf');
// Wave routes
Wave::routes();
