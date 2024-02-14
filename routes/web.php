<?php

use App\Http\Controllers\ProgressController;
use App\Http\Controllers\UploadController;
use App\Models\Team;
use Illuminate\Support\Facades\Response;
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
    return view('home');
})->name("home");

Route::get("/upload", [UploadController::class, 'index']);
Route::get("/progress", [UploadController::class, 'progress']);
Route::post('/upload/file', [UploadController::class, 'uploadFile'])->name('process_file');