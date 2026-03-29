<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InternaController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PropiedadesController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\PropertiesController;
use App\Http\Controllers\Dashboard\PostsController;
use App\Http\Controllers\Dashboard\AboutController;
use App\Http\Controllers\Dashboard\PropertyLeadsController;
use App\Http\Controllers\PropertyLeadController;
use App\Http\Controllers\Dashboard\CitiesController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\SitemapController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/interna', [InternaController::class, 'index']);
Route::get('/nosotros', [NosotrosController::class, 'index']);
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/interna', [BlogController::class, 'interna']);
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/propiedades', [PropiedadesController::class, 'index'])->name('propiedades.index');
Route::get('/propiedades/{slug}', [PropiedadesController::class, 'show'])->name('propiedades.show');
Route::post('/propiedades/{slug}/contacto', [PropertyLeadController::class, 'store'])
    ->name('propiedades.contact')
    ->middleware('throttle:5,1');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/storage/{path}', [HomeController::class, 'verImagen'])
    ->where('path', '.*')
    ->name('storage.fallback');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:10,1');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::resource('users', UsersController::class)->except(['show']);
        Route::resource('properties', PropertiesController::class)->except(['show']);
        Route::resource('posts', PostsController::class)->except(['show']);
        Route::get('/about', [AboutController::class, 'edit'])->name('about.edit');
        Route::put('/about', [AboutController::class, 'update'])->name('about.update');
        Route::resource('leads', PropertyLeadsController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('cities', CitiesController::class)->except(['show']);
        Route::resource('categories', CategoriesController::class)->except(['show']);
    });
});
