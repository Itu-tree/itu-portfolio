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

//laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php line 1163
//Auth::routes();


Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/sitemap.xml', 'FrontController@sitemap')->name('sitemap');
Route::get('/privacy', 'FrontController@showPrivacy')->name('privacy');

Route::get('/', 'ArticleController@index')->name('top');
Route::get('/article/{article}', 'ArticleController@show')->name('article.show');


Route::group(['prefix' => 'tag'], function () {
    Route::get('/', 'TagController@showTags')->name('tag.index');
    Route::get('/{tag}', 'TagController@showArticles')->name('tag.articles');
});
