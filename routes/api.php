<?php

use Illuminate\Http\Request;

Route::post('register', 'auth\AuthController@register');
Route::post('login', 'auth\AuthController@login');
Route::post('recover', 'auth\AuthController@recover');
Route::post('reset-password', 'auth\AuthController@resetPassword');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'auth\AuthController@logout');
    Route::get('test', function(){
        return response()->json(['foo'=>'bar']);
    });
});