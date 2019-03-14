<?php
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group([
    'middleware' => ['jwt.auth', 'only-administration'],
    'prefix' => 'admin'
], function () {
    Route::post('/users/handle_promotional_code', 'Api\Admin\UserController@handlePromotionalCode');
    Route::post('/users/send_promotional_code', 'Api\Admin\UserController@sendPromotionalCode');
    Route::resource('users', 'Api\Admin\UserController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('types', 'Api\Admin\TypeController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);
    Route::post('/types/handle_filter', 'Api\Admin\TypeController@handleFilter');

    Route::post('/user_groups/user_action_handler', 'Api\Admin\UserGroupController@userActionHandler');
    Route::post('/user_in_groups/user_action_handler', 'Api\Admin\UserGroupController@userActionHandler');
    Route::resource('user_groups', 'Api\Admin\UserGroupController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::post('/promotional_codes/get_free', 'Api\Admin\PromotionalCodeController@getFree');
    Route::resource('promotional_codes', 'Api\Admin\PromotionalCodeController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::post('/categories/handle_filter', 'Api\Admin\CategoryController@handleFilter');
    Route::resource('categories', 'Api\Admin\CategoryController')->only([
        'store', 'update', 'destroy'
    ]);

    Route::resource('filters', 'Api\Admin\FilterController')->only([
        'index', 'store', 'destroy'
    ]);
    Route::post('filters/update', 'Api\Admin\FilterController@update');

    Route::resource('products', 'Api\Admin\ProductController')->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);
    Route::prefix('products')->group(function () {
        Route::post('add_filter', 'Api\Admin\ProductController@addFilter');
        Route::post('remove_filter', 'Api\Admin\ProductInFilterController@remove');

        Route::prefix('available')->group(function () {
            Route::post('create', 'Api\Admin\ProductAvailableController@create');
            Route::post('update_quantity', 'Api\Admin\ProductAvailableController@updateQuantity');
            Route::post('destroy', 'Api\Admin\ProductAvailableController@destroy');
        });
    });
    Route::prefix('product_images')->group(function () {
        Route::post('upload', 'Api\Admin\ProductController@uploadImage');
        Route::post('delete', 'Api\Admin\ProductController@deleteImage');
        Route::post('update', 'Api\Admin\ProductImageController@update');
        Route::post('update_preview', 'Api\Admin\ProductImageController@updatePreview');
    });

    Route::resource('sliders', 'Api\Admin\SliderController')->only([
        'index', 'store', 'destroy', 'show'
    ]);
    Route::post('sliders/update', 'Api\Admin\SliderController@update');

    Route::resource('text_block_titles', 'Api\Admin\TextBlockTitleController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('text_block_data', 'Api\Admin\TextBlockDataController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('order_statuses', 'Api\Admin\OrderStatusController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('order_payment_methods', 'Api\Admin\OrderPaymentMethodController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('orders', 'Api\Admin\OrderController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::prefix('upload')->group(function () {
        Route::post('images', 'Api\Admin\UploadFileController@image');
    });
});


