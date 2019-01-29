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

Route::get('/', function () {
    return view('welcome');
});

Route::any('/wechat', 'WechatController@serve');
Route::get('/wechat/users', 'WechatController@getUsers');
Route::get('/wechat/tags', 'WechatController@getTags');
Route::get('/tag/{id}', 'WechatController@getUsersBelongsToTag');

Route::get('/upload/image', 'WechatController@uploadImage');
Route::get('/material/{material_id}', 'WechatController@getMaterial');
Route::get('/materials', 'WechatController@getMaterials');

Route::get('/broadcasting', 'WechatController@broadcastTagUsers');
Route::get('/menu', 'WechatController@addMenu');
