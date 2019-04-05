<?php
Route::get('preview', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@preview')->name('fm.preview');
Route::get('thumbnails', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@thumbnails')->name('fm.thumbnails');
Route::get('download', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@download')->name('fm.download');

Route::prefix('file-manager')->middleware(['jwt.auth', 'only-administration'])->group(function (){

    Route::get('initialize', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@initialize')->name('fm.initialize');

    Route::get('content', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@content')->name('fm.content');

    Route::get('tree', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@tree')->name('fm.tree');

    Route::get('select-disk', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@selectDisk')->name('fm.selectDisk');

    Route::post('create-directory', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@createDirectory')->name('fm.createDirectory');

    Route::post('upload', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@upload')->name('fm.upload');

    Route::post('delete', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@delete')->name('fm.delete');

    Route::post('paste', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@paste')->name('fm.paste');

    Route::post('rename', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@rename')->name('fm.rename');

    Route::get('properties', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@properties')->name('fm.properties');

    Route::get('url', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@url')->name('fm.url');

    // Integration with editors
    Route::get('ckeditor', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@ckeditor')->name('fm.ckeditor');
});

Route::get('/admin/{vue_capture?}', function () {
	return view('admin');
})->where('vue_capture', '[\/\w\.-]*');

Route::get('/{vue_capture?}', function () {
	return view('public');
})->where('vue_capture', '[\/\w\.-]*');