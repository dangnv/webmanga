<?php

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::group(['prefix' => 'night-mode'], function () {
        Route::get('/', 'HomeController@index')->name('night.home');

        Route::get('/latest', 'HomeController@latest')->name('night.post.latest');
        Route::get('/completed', 'HomeController@completed')->name('night.post.completed');
        Route::get('/newest', 'HomeController@newest')->name('night.post.newest');
        Route::get('/all', 'HomeController@all')->name('night.post.all');
        Route::get('/manga/{slug}', 'HomeController@detailPost')->name('night.post.detail');
        Route::get('/chapter/{post_slug}/{chapter_slug}', 'HomeController@detailChapter')->name('night.chapters.detail');

        Route::get('/news', 'HomeController@news')->name('night.news.index');
        Route::get('/news/{slug}', 'HomeController@newsDetail')->name('night.news.detail');

        Route::get('/profile', 'HomeController@profile')->name('night.profile.index');
    });

    /*light*/
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/latest', 'HomeController@latest')->name('post.latest');
    Route::get('/completed', 'HomeController@completed')->name('post.completed');
    Route::get('/newest', 'HomeController@newest')->name('post.newest');
    Route::get('/all', 'HomeController@all')->name('post.all');
    Route::get('/genre/{slug}', 'HomeController@postByCategory')->name('post.category');
    Route::get('/manga/{slug}', 'HomeController@detailPost')->name('post.detail');
    Route::get('/chapter/{post_slug}/{chapter_slug}', 'HomeController@detailChapter')->name('chapters.detail');

    Route::get('/news', 'HomeController@news')->name('news.index');
    Route::get('/news/{slug}', 'HomeController@newsDetail')->name('news.detail');

    Route::get('/profile', 'HomeController@profile')->name('profile.index');

    Route::get('/auth/redirect/{provider}', 'SocialController@redirect')->name('social.login');
    Route::get('/callback/{provider}', 'SocialController@callback');

    Route::post('/comment/post', 'HomeController@postComment')->name('comment.post');

    Route::post('bookmark/post', 'HomeController@postBookmark')->name('bookmark.post');
    Route::post('bookmark/remove', 'HomeController@removeBookmark')->name('post.bookmark.remove');
    Route::post('bookmark/remove/all', 'HomeController@removeAllBookmark')->name('post.bookmark.remove.all');
});

Auth::routes();
