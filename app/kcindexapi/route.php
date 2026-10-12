<?php
use think\facade\Route;

Route::get('captcha/[:config]', '\\think\\captcha\\CaptchaController@index');
Route::post('message/add', 'app\\kcindexapi\\controller\\Message@add');