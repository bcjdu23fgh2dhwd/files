<?php

Route::group(['middleware' => 'auth', 'prefix' => 'adm'], function()
{
    Route::post('/file/upload',   ['as' => 'file_upload',  'uses' => 'Interpro\Files\Http\FilesController@upload']);
});
