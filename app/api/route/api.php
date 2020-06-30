<?php
/**
 * create by XinyuLi
 * @since 09/06/2020 21:56
 */
use think\facade\Route;

Route::post('api/auth/smscode','api/Auth/smsCode');
Route::post('api/auth/login','api/Auth/login');
Route::post('api/auth/register','api/Auth/register');

Route::post('api/user/logout','api/User/logout');
Route::resource('user','User');

Route::rule('mall/lists','mall.lists/index');
Route::rule('mall/detail/:id',"mall.detail/index");
Route::rule('mall/recommend',"mall.recommend/index");

Route::rule('category/search/:id','category/search');
Route::rule('subcategory/:id','category/sub');

Route::rule('order/all','order.lists/getAllOrders');
Route::resource('order','order.index');

Route::post('api/address','api/Address/add');
Route::get('api/address','api/Address/read');