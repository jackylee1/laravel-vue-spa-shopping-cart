<?php
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group([], function () {
    Route::post('index', 'Api\PageController@index');
    Route::post('common', 'Api\PageController@common');
    Route::post('subscribe', 'Api\SubscribeController@store');
    Route::get('products', 'Api\ProductController@products');
    Route::get('product/view/{slug}', 'Api\ProductController@view');

    Route::prefix('favorite')->group(function () {
        Route::post('/', 'Api\FavoriteController@store');
        Route::post('/destroy', 'Api\FavoriteController@destroy');
    });

    Route::prefix('cart')->group(function () {
        Route::post('add_product', 'Api\CartController@addProduct');
        Route::post('delete_product', 'Api\CartController@deleteProduct');
        Route::post('update_quantity_product', 'Api\CartController@updateQuantityProduct');
        Route::post('update', 'Api\CartController@update');
        Route::post('update_promotional_code', 'Api\CartController@updatePromotionalCode');
    });

    Route::prefix('order')->group(function () {
        Route::post('create', 'Api\OrderController@create');
    });

    Route::prefix('nova_poshta')->group(function () {
        Route::post('areas', 'Api\NovaPoshtaAreaController@get');
        Route::post('cities', 'Api\NovaPoshtaCityController@get');
        Route::post('warehouses', 'Api\NovaPoshtaWarehouseController@get');
    });

    Route::prefix('user')->group(function () {
        Route::post('registration', 'Api\UserController@registration');
    });

    Route::post('/order/by_in_one_click', 'Api\OrderController@byInOneClick');
});

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::resource('user', 'Api\UserController')->only([
        'update'
    ]);
    Route::get('orders', 'Api\OrderController@getOrders');

    Route::prefix('order')->group(function () {
        Route::post('/', 'Api\OrderController@view');
    });
});


Route::group(['middleware' => ['jwt.auth', 'only-administration'], 'prefix' => 'admin'], function () {
    Route::post('/users/handle_promotional_code', 'Api\Admin\UserController@handlePromotionalCode');
    Route::post('/users/send_promotional_code', 'Api\Admin\UserController@sendPromotionalCode');
    Route::resource('users', 'Api\Admin\UserController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('types', 'Api\Admin\TypeController')->only([
        'index', 'show', 'store', 'destroy'
    ]);
    Route::post('/types/update', 'Api\Admin\TypeController@update');
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

    Route::resource('link_to_social_networks', 'Api\Admin\LinkToSocialNetworkController')->only([
        'index', 'store', 'destroy', 'show'
    ]);
    Route::post('link_to_social_networks/update', 'Api\Admin\LinkToSocialNetworkController@update');

    Route::resource('text_block_titles', 'Api\Admin\TextBlockTitleController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('utf_records', 'Api\Admin\UtfRecordController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::resource('size_tables', 'Api\Admin\SizeTableController')->only([
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

    Route::resource('subscribes', 'Api\Admin\SubscribeController')->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);
    Route::post('subscribes/update_read_status', 'Api\Admin\SubscribeController@updateReadStatus');

    Route::post('new_notifications', 'Api\Admin\NotificationController@getNew');

    Route::prefix('orders')->group(function () {
        Route::post('add_product', 'Api\Admin\OrderController@addProduct');
        Route::post('delete_product', 'Api\Admin\OrderController@deleteProduct');
        Route::post('delete_status', 'Api\Admin\OrderController@deleteStatus');
        Route::post('send_status', 'Api\Admin\OrderController@sendStatus');
        Route::post('update_read_status', 'Api\Admin\OrderController@updateReadStatus');
    });

    Route::prefix('settings')->group(function () {
        Route::post('/', 'Api\Admin\SettingController@get');
        Route::post('update', 'Api\Admin\SettingController@update');
    });

    Route::prefix('upload')->group(function () {
        Route::post('images', 'Api\Admin\UploadFileController@image');
    });
});


