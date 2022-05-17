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

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
    Route::get('/', 'HomeController@index')->name('home.index');
    Route::get('/users',  'UserController@getUsers')->name('users.get');

    Route::group(['middleware' => ['guest']], function() {
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');
    });

    Route::group(['middleware' => ['auth']], function() {
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
        Route::get('/{id}/username',  'UserController@getUserName')->name('username.get');
        Route::post('/{id}/add_comment', 'CommentsController@addComment')->name('comment.add');
        Route::post('/{id}/delete_comment',  'CommentsController@destroy')->name('comment.delete');
        Route::post('/{id}/reply_comment',  'CommentsController@replyComments')->name('comment.reply');
    });

    Route::get('/{id}', 'CommentsController@getComments')->name('comment.get');
    Route::get('/{id}/all_comments',  'CommentsController@getAllComments')->name('comment.all');
});
