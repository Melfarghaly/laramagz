<?php

use App\Helpers\Settings;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MailChimController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\Facades\Image;

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

//Auth

if (Schema::hasTable('settings')) {
    Settings::get('register') == 'y' ? Auth::routes() : Auth::routes(['register'=>false]);
}
Route::post('logout', LogoutController::class);

//Logo & Image

Route::middleware('optimizeImages')->group(function () {
    Route::patch('image-crop', [ImageController::class, 'uploadUserPhoto']);
    Route::post('upload-image', [ImageController::class, 'uploadImagePost'])
        ->name('uploadImage');
    Route::post('upload-image-ad', [ImageController::class, 'uploadImageAd'])
        ->name('uploadImageAd');
    Route::post('resizeImagePost', [ImageController::class, 'resizeImagePost'])
        ->name('resizeImagePost');
});

Route::get('photo-author/{filename}', function ($filename){
    return Image::make(storage_path('app/public/avatar/' . $filename))->response();
})->name('author.photo');

Route::get('logo/{filename}', function ($filename)
{
    if(Storage::disk('public')->exists('assets/' . $filename)){
        return Image::make(storage_path('app/public/assets/' . $filename))->response();
    }else{
        return Image::make(public_path('themes/magz/images/logo.png'))->response();
    }
})->name('logo.display');

Route::get('icon/{filename}', function ($filename)
{
    if(Storage::disk('public')->exists('assets/' . $filename)){
        return Image::make(storage_path('app/public/assets/' . $filename))->response();
    }else{
        return Image::make(public_path('favicons/favicon-96x96.png'))->response();
    }
})->name('icon.display');

Route::get('ogi/{filename}', function ($filename)
{
    if(Storage::disk('public')->exists('images/' . $filename)){
        return Image::make(storage_path('app/public/images/' . $filename))->response();
    }else{
        return Image::make(public_path('img/cover.png'))->response();
    }
})->name('ogi.display');


Route::get('dashboard/logo/{filename}', function ($filename)
{
    if(Storage::disk('public')->exists('assets/' . $filename)){
        return Image::make(storage_path('app/public/assets/' . $filename))->response();
    }else{
        return Image::make(public_path('img/logo.png'))->response();
    }
})->name('dashboard.logo');

Route::get('auth/logo/{filename}', function ($filename)
{
    if(Storage::disk('public')->exists('assets/' . $filename)){
        return Image::make(storage_path('app/public/assets/' . $filename))->response();
    }else{
        return Image::make(public_path('img/logo-auth.png'))->response();
    }
})->name('auth.logo');


Route::get('image/{filename}', [ImageController::class, 'displayImage'])
    ->name('image.displayImage');
Route::get('get/post/image/{filename}', [ImageController::class, 'displayPostImage'])
    ->name('post.image');
Route::post('image-delete', [ImageController::class, 'removeUploadImage'])
    ->name('removeUploadImage');
Route::delete('photo-delete', [ImageController::class,'removeUserPhoto'])
    ->name('user.removePhoto');
Route::post('image-crop', [ImageController::class, 'uploadUserPhoto']);


Route::get('search', [SearchController::class, 'search'])
    ->name('search');
Route::post('subscribe', [MailChimController::class, 'subscribe'])->name('subscribe');

