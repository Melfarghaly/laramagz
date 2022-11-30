<?php
use App\Helpers\Settings;
use App\Http\Controllers\Front\AdvertisementController;
use App\Http\Controllers\Front\ArticleController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\TagController;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;


/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
|
*/

Route::get('ogi/{filename}', function ($filename) {
    if (Storage::disk('public')->exists('images/' . $filename)) {
        return Image::make(storage_path('app/public/images/' . $filename))->response();
    } else {
        return Image::make(public_path('img/cover.png'))->response();
    }
})->name('ogi.display');

//ad
Route::get('ad/{filename}', [AdvertisementController::class, 'displayAdImage'])
    ->name('ad.image');

Route::middleware('public')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/latest-news', [ArticleController::class, 'index'])
        ->name('articles.latest');
    Route::get('/contact', [ContactController::class, 'index'])
        ->name('contact');
    Route::get('/news/{post:post_name}', [ArticleController::class, 'show'])
        ->name('article.show');
    Route::get('/popular/news', [ArticleController::class, 'showPopular'])
        ->name('article.popular');
    Route::get('/category/{term:slug}', [CategoryController::class, 'index'])
        ->name('category.show');
    Route::get('/tag/{term:slug}', [TagController::class, 'index'])
        ->name('tag.show');
    Route::get('/page/{page:post_name}', [PageController::class,'show'])
        ->name('page.show');
    Route::post('/sendcontact', [ContactController::class, 'store'])
        ->name('sendcontact');
    Route::patch('/post/react', [ArticleController::class, 'react'])
        ->name('sendreact');
});

if (Schema::hasTable('settings')) {
    if (Settings::get('permalink')) {
        if (Settings::get('permalink') == "%year%/%month%/%day%")
        {
            Route::get('/{year}/{month}/{day}/{post:post_name}', function ($year, $month, $day, $post)  {
                $showpost = Post::whereYear('created_at', '=', $year)
                    ->whereYear('created_at', '=', $year)
                    ->whereMonth('created_at', '=', $month)
                    ->whereDay('created_at', '=', $day)
                    ->wherePostName($post)
                    ->firstOrFail();

                return app('App\Http\Controllers\Front\ArticleController')->show($showpost);
            })
                ->name('article.show')
                ->middleware('public')
                ->where('day', '[0-9]+');
        }
        elseif (Settings::get('permalink') == "%year%/%month%")
        {
            Route::get('/{year}/{month}/{post:post_name}', function ($year, $month, $post)  {
                $showpost = Post::whereYear('created_at', '=', $year)
                    ->whereYear('created_at', '=', $year)
                    ->whereMonth('created_at', '=', $month)
                    ->wherePostName($post)
                    ->firstOrFail();

                return app('App\Http\Controllers\Front\ArticleController')->show($showpost);
            })
                ->name('article.show')
                ->middleware('public');
        }
        else
        {
            $word = Settings::get('permalink') != 'post_name' ? Settings::get('permalink') : '';
            $uri = $word."/{post:post_name}";
            Route::get($uri, [ArticleController::class, 'show'])
                ->name('article.show')
                ->middleware('public');
        }
    }
}

