<?php
/**
 * create by XinyuLi
 * @since 09/06/2020 21:56
 */
use think\facade\Route;

Route::rule("smscode","sms/code","POST");
Route::resource('user','User');
Route::rule('lists','mall.lists/index');
Route::rule('subcategory/:id','category/sub');