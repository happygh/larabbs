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
//    return view('welcome');
});

Route::get('/', 'PagesController@root');

Auth::routes();

//用户资源路由
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

//话题资源路由
Route::resource('topics', 'TopicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);

//分类资源路由
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);