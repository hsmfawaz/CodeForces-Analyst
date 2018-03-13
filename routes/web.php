<?php

Route::get('/', 'Reports@home');
Route::get('result/{id}', 'Reports@result');
Route::get('Download/{id}', 'Reports@Download');
Route::get('latest', 'Reports@latest');

Route::prefix('get')->group(function () {
    Route::get('problems/{pass}', 'Initialization@Problems');
    Route::get('report/{id}', 'Reports@get');
    Route::get('contests/{pass}', 'Initialization@GetAllContests');
});

Route::prefix('set')->group(function () {
    Route::post('report', 'Reports@create');
});

