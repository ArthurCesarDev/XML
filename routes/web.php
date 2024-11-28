<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\YourController;
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

Route::get('/', [HomeController::class, 'index']);


Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login.index');
    Route::post('/login', 'store')->name('login.store');
    Route::post('/logout', 'destroy')->name('login.destroy');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'index')->name('register.index');
    Route::post('/register', 'store')->name('register.store');
});

// Grupo de rotas protegidas pelo middleware 'auth'
Route::middleware(['auth'])->group(function () {

    // Rota principal 'auth'
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rota danfe 'auth'
    Route::post('/import-xml', [DashboardController::class, 'importXML'])->name('import.xml');
    Route::get('/ver-xml/{arquivo}', [DashboardController::class, 'verXML'])->name('ver-xml');
    Route::delete('/xml/apagar/{arquivo}', [DashboardController::class, 'apagarXML'])->name('apagar-xml');
    Route::post('/apagar-tudo', [DashboardController::class, 'apagarTudo'])->name('apagar-tudo');
    Route::get('/visualizar-xml/{arquivo}', [DashboardController::class, 'visualizarXML'])->name('visualizar-xml');
    Route::post('/visualizar-todas-xmls', [DashboardController::class, 'visualizarTodasXmls'])->name('visualizar-todas-xmls');


    // Rota buscar tabelas danfe 'auth'
    Route::get('/buscar-sap/{ean}', [DashboardController::class, 'buscarSapPorEan'])->name('buscar.sap');
    Route::post('/atualizar-informacoes', [DashboardController::class, 'atualizarInformacoes'])->name('atualizar.informacoes');


    // Rota material 'auth'
    Route::get('/base-dados', [DashboardController::class, 'baseDados'])->name('base-dados');
    Route::put('/produtos/{id}', [DashboardController::class, 'update'])->name('update.produto');
    Route::post('/produtos', [DashboardController::class, 'store'])->name('produtos.store');

    // base de usuarios 'auth'
    Route::get('/list-users', [DashboardController::class, 'listUsers'])->name('list-users');
    Route::put('/users/{id}', [DashboardController::class, 'updateUser'])->name('update.user');
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');

    // Rota buscar XPED 'auth'
    Route::get('/search-xped', [DashboardController::class, 'searchXped'])->name('search-xped');
    Route::post('/save-single-pedido', [DashboardController::class, 'saveSinglePedido'])->name('save-single-pedido');
    Route::post('/save-pedidos', [DashboardController::class, 'savePedidos'])->name('save-pedidos');
    Route::get('/pedidos', [DashboardController::class, 'listarPedidos'])->name('listar-pedidos');
    Route::put('/pedidos/{id}', [DashboardController::class, 'atualizarPedido'])->name('atualizar-pedido');
    Route::delete('/pedidos/{id}', [DashboardController::class, 'deletarPedido'])->name('deletar-pedido');
    Route::get('/buscar-chave-xml/{pedido}', [DashboardController::class, 'buscarChaveXml'])->name('buscar-chave-xml');
    Route::post('/produtos/store', [DashboardController::class, 'store'])->name('produtos.store');
    Route::delete('/produtos/{id}', [DashboardController::class, 'destroy'])->name('produtos.destroy');
    Route::post('/produto/{id}/atualizar-caixaria', [DashboardController::class, 'atualizarProdutoCaixaria']);




});
