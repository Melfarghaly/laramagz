<?php

use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\AnalyticController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnvController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlacementController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialmediaController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//DASHBORD
Route::get('admin/dashboard', DashboardController::class)
    ->name('admin.dashboard')
    ->middleware('auth');

Route::get('admin', function (){
    return redirect()->route('admin.dashboard');
});

Route::get('admin/manage', function () {
    return redirect()->route('admin.dashboard');
});

Route::group(['middleware' => 'auth'], function () {
    //SETTING
    Route::patch('changeStatusMaintenance', [SettingController::class, 'changeMaintenance']);
    Route::patch('changeRegisterMember',[SettingController::class, 'changeRegisterMember']);

    // CHANGE PERMISSION
    Route::patch('change-permission', [RoleController::class, 'changePermission']);
    Route::patch('change-all-permission', [RoleController::class, 'changeAllPermission']);

    //ADS
    Route::get('change-ad-active', [AdvertisementController::class, 'changeAdActive']);
    Route::get('change-placement-active', [PlacementController::class, 'changePlacementActive']);

    //THEMES
    Route::get('data/themes', [ThemeController::class, 'theme'])
        ->name('theme')
        ->middleware('auth');

    //USER
    Route::get('getsocmed', [SocialmediaController::class, 'getSocmed']);

    //GALLERY
    Route::get('gallery/show/{filename}', function ($filename)
    {
        return Image::make(storage_path('app/public/images/' . $filename))->response();
    })->name('gallery.show.image');
});

//AJAX
Route::group(['prefix' => 'ajax', 'middleware' => 'auth'], function () {
    Route::get('categories/search', [CategoryController::class, 'ajaxSearch'])
        ->name('categories.search');
    Route::get('tags/search', [TagController::class, 'tagsSearch'])
        ->name('tags.search');
    Route::get('roles/search', [RoleController::class, 'ajaxSearch'])
        ->name('roles.search');
    Route::get('socialmedia/search', [SocialmediaController::class, 'ajaxSearch'])
        ->name('socialmedia.search');
    Route::get('menu/search', [MenuController::class, 'ajaxSearch'])
        ->name('menu.search');
    Route::get('advertisement/search', [AdvertisementController::class, 'ajaxSearch'])
        ->name('advertisement.search');
});

//PROFILE
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('avatar/{filename}', function ($filename)
    {
        if(Storage::disk('public')->exists('avatar/' . $filename)){
            return Image::make(storage_path('app/public/avatar/' . $filename))->response();
        }else{
            return Image::make(public_path('img/noavatar.png'))->response();
        }
    })->name('profile.photo');
    Route::get('profile', [ProfileController::class, 'index'])
        ->name('profile.index');
    Route::patch('profile/{id}', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::get('change-password', [ProfileController::class, 'change_password'])
        ->name('view.password.edit');
    Route::post('change-password', [ProfileController::class, 'password_update'])
        ->name('auth.password.update');
});

// MENU
Route::group(['middleware' => config('menu.middleware')], function () {
    $path = rtrim(config('menu.route_path'));
    Route::post($path .'/addcustommenu', [MenuController::class , 'addcustommenu'])
        ->name('haddcustommenu');
    Route::post($path .'/deleteitemmenu', [MenuController::class, 'deleteitemmenu'])
        ->name('hdeleteitemmenu');
    Route::post($path .'/deletemenug', [MenuController::class, 'deletemenug'])
        ->name('hdeletemenug');
    Route::post($path .'/createnewmenu', [MenuController::class, 'createnewmenu'])
        ->name('hcreatenewmenu');
    Route::post($path .'/updateitem', [MenuController::class, 'updateitem'])
        ->name('hupdateitem');
    Route::post($path . '/generatemenucontrol', [MenuController::class, 'generatemenucontrol'])
        ->name('hgeneratemenucontrol');
});

//GALLERY
Route::get('gallery/show/{filename}', function ($filename)
{
    return Image::make(storage_path('app/public/images/' . $filename))->response();
})->name('gallery.show.image');

//ADMIN MANAGE
Route::prefix('admin/manage')->middleware('auth')->group(function () {
    Route::get('posts/massdestroy', [PostController::class, 'massdestroy'])
        ->name('posts.massdestroy');
    Route::get('categories/massdestroy', [CategoryController::class, 'massdestroy'])
        ->name('categories.massdestroy');
    Route::get('tags/massdestroy', [TagController::class, 'massdestroy'])
        ->name('tags.massdestroy');
    Route::get('contacts/massdestroy', [ContactController::class, 'massdestroy'])
        ->name('contacts.massdestroy');
    Route::patch('themes/activated', [ThemeController::class, 'activated'])
        ->name('theme.activated');
    Route::get('ads/massdestroy', [AdvertisementController::class, 'massdestroy'])
        ->name('ads.massdestroy');
    Route::get('users/massdestroy', [UserController::class, 'massdestroy'])
        ->name('users.massdestroy');
    Route::get('roles/massdestroy', [RoleController::class, 'massdestroy'])
        ->name('roles.massdestroy');
    Route::get('permissions/massdestroy', [PermissionController::class, 'massdestroy'])
        ->name('permissions.massdestroy');
    Route::get('socialmedia/massdestroy', [SocialmediaController::class, 'massdestroy'])
        ->name('socialmedia.massdestroy');

    Route::resource('posts', PostController::class);
    Route::resource('pages', PageController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
    Route::resource('contacts', ContactController::class);

    Route::get('menu', [MenuController::class, 'index'])
        ->name('menu.index');
    Route::delete('menu/{id}', [MenuController::class, 'destroy'])
        ->name('menu.destroy');
    Route::put('menu/{id}', [MenuController::class, 'update'])
        ->name('menu.update');
    Route::get('menu/position', [MenuController::class, 'position'])
        ->name('menu.position');
    Route::post('menu', [MenuController::class, 'store'])
        ->name('menu.store');

    Route::resource('themes', ThemeController::class);

    Route::resource('placements', PlacementController::class);
    Route::resource('advertisement', AdvertisementController::class);
    Route::resource('galleries', GalleryController::class);

    Route::get('filemanager', function () {
        return view('admin.filemanager.index');
    });

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('socialmedia', SocialmediaController::class);
    Route::resource('analytics', AnalyticController::class);

    Route::get('settings', [SettingController::class, 'setting'])
        ->name('settings.index');
    Route::patch('settings/update', [SettingController::class, 'settingUpdate'])
        ->name('settings.update');
    Route::post('env/upload', [EnvController::class, 'upload'])
        ->name('upload');

});
