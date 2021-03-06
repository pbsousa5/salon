<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// 第二版
Route::group(['prefix' => 'api/v2', 'namespace' => 'Api\V2'], function(){
    // 门店
    Route::get('/suppliers', 'SupplierController@index');
    Route::get('/suppliers/{id}', 'SupplierController@show');
    Route::get('/suppliers/{account}/exist', 'SupplierController@checkExistUser');
    Route::get('/suppliers/{supplier_id}/get_customer', 'SupplierController@customer');
    Route::delete('/suppliers/{id}', 'SupplierController@logout');
    Route::post('/suppliers/login', 'SupplierController@login');
    Route::post('/suppliers/{id}/edit', 'SupplierController@modify');
    Route::post('/suppliers/passwd', 'SupplierController@resetPassWord');
    Route::post('/suppliers/user_consume', 'SupplierController@consume');
    Route::post('/suppliers/withdraw_cash', 'SupplierController@withdraw');
    Route::post('/suppliers/{supplier_id}/bind_barber', 'SupplierController@bindBarber');
    Route::post('/suppliers/{supplier_id}/unbund_barber', 'SupplierController@unbundBarber');
    
    // app
    Route::get('/app/init_info', 'AppController@bootstrap');
    Route::get('/app/qiniu_token/{type}', 'AppController@qiniuToken');
    Route::get('/app/banner', 'AppController@banner');
    Route::get('/app/banner/{id}', 'AppController@getBannerInfo');
    Route::get('/app/{mobile}/sms', 'AppController@sendSmsCode');
    Route::post('/app/upgrade', 'AppController@appUpgrade');
    Route::post('/app/join_apply', 'AppController@storeJoinApply');
    Route::get('/app/add_feedback', 'AppController@showFeedback');
    Route::post('/app/add_feedback', ['as'=>'feedback', 'uses'=>'AppController@storeFeedback']);
    Route::get('/app/{user_id}/push_info', 'AppController@updatePushInfo');
    Route::get('/app/about_us', 'AppController@aboutUs');

    // 消费者
    Route::get('/consumers/{account}/exist', 'ConsumerController@checkExistUser');
    Route::get('/consumers/{id}', 'ConsumerController@show');
    Route::post('/consumers', 'ConsumerController@register');
    Route::post('/consumers/login', 'ConsumerController@login');
    Route::delete('/consumers/{id}', 'ConsumerController@logout');
    Route::post('/consumers/{id}/edit', 'ConsumerController@modify');
    Route::post('/consumers/passwd', 'ConsumerController@resetPassWord');

    // 理发师
    Route::post('/barbers/login', 'BarberController@login');
    Route::delete('/barbers/{id}', 'BarberController@logout');
    Route::post('/barbers/{id}/edit', 'BarberController@modify');
    Route::get('/barbers', 'BarberController@index');
    Route::get('/barbers/{id}', 'BarberController@show');
    Route::post('/barbers/passwd', 'BarberController@resetPassWord');
    Route::get('/barbers/{id}/skill_tags', 'BarberController@skillTags');
    Route::post('/barbers/{barber_id}/add_product', 'BarberController@storeProduct');
    Route::get('/barbers/{barber_id}/get_customer', 'BarberController@customer');
    
    // 理发师作品集
    Route::get('/samples/{barber_id}', 'BarberSampleController@index');
    Route::post('/samples/{barber_id}/del/{sample_ids}', 'BarberSampleController@destory');
    Route::post('/samples/{barber_id}/add_sample', 'BarberSampleController@store');
    Route::post('/samples/{barber_id}/edit/{sample_id}', 'BarberSampleController@modify');

    // 关注\fans
    Route::get('/followers/{id}/watcher', 'FollowerController@listWatcher');
    Route::post('/followers', 'FollowerController@store');
    Route::delete('/followers/{consumer_id}/delete/{supplier_id}', 'FollowerController@destroy');
    Route::get('/followers/{user_type}/fans/{user_id}', 'FollowerController@getFans');

    // 搜索
    Route::get('/searchs', 'SearchController@search');

    // 通知
    Route::get('/notifys/{id}', 'NotifyController@show');
    Route::get('/notifys/{user_id}/list', 'NotifyController@index');
    Route::delete('/notifys/{id}', 'NotifyController@destroy');

    // 产品
    Route::get('/products/{user_id}/list', 'ProductController@index');
    Route::get('/products/list_caegory', 'ProductController@categorys');
    Route::get('/products/{product_id}/intro', 'ProductController@intro');
    Route::get('/products/sign/{product_id}', 'ProductController@show');
    Route::post('/products/{supplier_id}/edit/{product_id}', 'ProductController@update');
    Route::post('/products/{supplier_id}/add', 'ProductController@store');
    Route::post('/products/{supplier_id}/delete/{product_id}', 'ProductController@destory');

    // 评论
    Route::post('/reviews/{consumer_id}/create', 'ReviewController@store');
    Route::post('/reviews/mark_heart', 'ReviewController@markUserful');
    Route::get('/reviews/{user_id}', 'ReviewController@index');
    Route::get('/reviews/{consumer_id}/tags', 'ReviewController@tag');

    // 优惠券
    Route::get('/coupons/{consumer_id}/list', 'CouponController@index');
    Route::get('/coupons', 'CouponController@getCoupon');
    Route::post('/coupons', ['as'=>'coupons', 'uses'=>'CouponController@postCoupon']);

    // 订单
    Route::post('/orders/{consumer_id}/to_buy', 'OrderController@store');
    Route::get('/orders/{user_id}/list_order', 'OrderController@index');
    Route::post('/orders', 'OrderController@destroy');
    Route::post('/orders/{order_id}', 'OrderController@cancel');
    Route::get('/orders/{order_id}', 'OrderController@show');
    Route::get('/orders/{consumer_id}/{product_id}/{type}', 'OrderController@appointment');

    // 支付回调
    Route::post('/pays/{type}/notify', 'PayController@notify');
    Route::post('/pays/{order_id}', 'PayController@wxorder');

    // 退单操作
    Route::post('/back_orders/{order_id}', 'BackOrderController@store');

    // 门店提现
    Route::get('/withdraws/account_list', 'WithdrawController@index');
    Route::get('/withdraws/supplier_fee', 'WithdrawController@showFee');
    Route::post('/withdraws/apply', 'WithdrawController@withdraw');
    Route::post('/withdraws/{user_id}/update_account', 'WithdrawController@account');
    Route::get('/withdraws/{user_id}/list_income', 'WithdrawController@indexIncome');
    Route::get('/withdraws/{supplier_id}/list_withdraw', 'WithdrawController@indexWithdraw');

    // 帮助
    Route::post('/helper/aes_encode', 'HelperController@aesEncodeData');
    Route::post('/helper/aes_decode', 'HelperController@aesDecodeData');
    Route::get('/helper/sign', 'HelperController@createSignature');
    Route::get('/helper/check_sign', 'HelperController@checkSignature');
    Route::get('/helper/{id}/token', 'HelperController@getUserToken');

    // 定时任务
    Route::get('crontab/update_active', 'CrontabController@updateActiveProductStock');
});

// 第一版
Route::group(['prefix' => 'api/v1', 'namespace' => 'Api\V1'], function(){
    // app
    Route::get('/app/init_info', 'AppController@bootstrap');
    Route::get('/app/qiniu_token/{type}', 'AppController@qiniuToken');
    Route::get('/app/banner', 'AppController@banner');
    Route::get('/app/banner/{id}', 'AppController@getBannerInfo');
    Route::get('/app/{mobile}/sms', 'AppController@sendSmsCode');
    Route::post('/app/upgrade', 'AppController@appUpgrade');
    Route::post('/app/join_apply', 'AppController@storeJoinApply');
    Route::get('/app/add_feedback', 'AppController@showFeedback');
    Route::post('/app/add_feedback', ['as'=>'feedback', 'uses'=>'AppController@storeFeedback']);
    Route::get('/app/{user_id}/push_info', 'AppController@updatePushInfo');
    Route::get('/app/about_us', 'AppController@aboutUs');
    
    // 消费者
    Route::get('/consumers/{account}/exist', 'ConsumerController@checkExistUser');
    Route::get('/consumers/{id}', 'ConsumerController@getUserInfo');
    Route::post('/consumers', 'ConsumerController@register');
    Route::post('/consumers/login', 'ConsumerController@login');
    Route::delete('/consumers/{id}', 'ConsumerController@logout');
    Route::post('/consumers/{id}/edit', 'ConsumerController@modify');
    Route::post('/consumers/passwd', 'ConsumerController@resetPassWord');
    
    // 门店
    Route::get('/suppliers/{id}', 'SupplierController@show');
    Route::get('/suppliers', 'SupplierController@index');
    Route::get('/suppliers/{account}/exist', 'SupplierController@checkExistUser');
    Route::get('/suppliers/{supplier_id}/get_customer', 'SupplierController@customer');
    Route::delete('/suppliers/{id}', 'SupplierController@logout');
    Route::post('/suppliers/login', 'SupplierController@login');
    Route::post('/suppliers/{id}/edit', 'SupplierController@modify');
    Route::post('/suppliers/passwd', 'SupplierController@resetPassWord');
    Route::post('/suppliers/user_consume', 'SupplierController@consume');
    Route::post('/suppliers/withdraw_cash', 'SupplierController@withdraw');
    Route::post('/suppliers/{supplier_id}/bind_barber', 'SupplierController@bindBarber');
    Route::post('/suppliers/{supplier_id}/unbund_barber', 'SupplierController@unbundBarber');
    
    // 理发师
    Route::post('/barbers/login', 'BarberController@login');
    Route::delete('/barbers/{id}', 'BarberController@logout');
    Route::post('/barbers/{id}/edit', 'BarberController@modify');
    Route::get('/barbers', 'BarberController@index');
    Route::get('/barbers/{id}', 'BarberController@show');
    Route::post('/barbers/passwd', 'BarberController@resetPassWord');
    Route::post('/barbers/{id}/add_sample', 'BarberController@storeSample');
    Route::get('/barbers/{id}/list_sample', 'BarberController@listSample');
    Route::post('/barbers/{barber_id}/del/{sample_id}', 'BarberController@destorySample');
    Route::get('/barbers/{id}/skill_tags', 'BarberController@skillTags');
    Route::post('/barbers/{barber_id}/add_product', 'BarberController@storeProduct');
    Route::get('/barbers/{barber_id}/get_customer', 'BarberController@customer');
    
    // 关注
    Route::get('/followers/{id}/watcher', 'FollowerController@listWatcher');
    Route::post('/followers', 'FollowerController@store');
    Route::delete('/followers/{consumer_id}/delete/{supplier_id}', 'FollowerController@destroy');
    
    // 搜索
    Route::get('/searchs', 'SearchController@search');
    
    // 通知
    Route::get('/notifys/{id}', 'NotifyController@show');
    Route::get('/notifys/{user_id}/list', 'NotifyController@index');
    Route::delete('/notifys/{id}', 'NotifyController@destroy');
    
    // 产品
    Route::get('/products/{user_id}/list', 'ProductController@index');
    Route::get('/products/list_caegory', 'ProductController@categorys');
    Route::get('/products/{product_id}/intro', 'ProductController@intro');
    Route::get('/products/sign/{product_id}', 'ProductController@show');
    Route::post('/products/{supplier_id}/edit/{product_id}', 'ProductController@update');
    Route::post('/products/{supplier_id}/add', 'ProductController@store');
    Route::post('/products/{supplier_id}/delete/{product_id}', 'ProductController@destory');
    
    // 评论
    Route::post('/reviews/{consumer_id}/create', 'ReviewController@store');
    Route::post('/reviews/mark_heart', 'ReviewController@markUserful');
    Route::get('/reviews/{user_id}', 'ReviewController@index');
    Route::get('/reviews/{consumer_id}/tags', 'ReviewController@tag');
    
    // 优惠券
    Route::get('/coupons/{consumer_id}/list', 'CouponController@index');
    Route::get('/coupons', 'CouponController@getCoupon');
    Route::post('/coupons', ['as'=>'coupons', 'uses'=>'CouponController@postCoupon']);
    
    // 订单
    Route::post('/orders/{consumer_id}/to_buy', 'OrderController@store');
    Route::get('/orders/{user_id}/list_order', 'OrderController@index');
    Route::post('/orders', 'OrderController@destroy');
    Route::post('/orders/{order_id}', 'OrderController@cancel');
    Route::get('/orders/{order_id}', 'OrderController@show');
    Route::get('/orders/{consumer_id}/{product_id}/{type}', 'OrderController@appointment');
    
    // 支付回调
    Route::post('/pays/{type}/notify', 'PayController@notify');
    Route::post('/pays/{order_id}', 'PayController@wxorder');

    // 退单操作
    Route::post('/back_orders/{order_id}', 'BackOrderController@store');
    
    // 门店提现
    Route::get('/withdraws/account_list', 'WithdrawController@index');
    Route::get('/withdraws/supplier_fee', 'WithdrawController@showFee');
    Route::post('/withdraws/apply', 'WithdrawController@withdraw');
    Route::post('/withdraws/{user_id}/update_account', 'WithdrawController@account');
    Route::get('/withdraws/{user_id}/list_income', 'WithdrawController@indexIncome');
    Route::get('/withdraws/{supplier_id}/list_withdraw', 'WithdrawController@indexWithdraw');
    
    // 帮助
    Route::post('/helper/aes_encode', 'HelperController@aesEncodeData');
    Route::post('/helper/aes_decode', 'HelperController@aesDecodeData');
    Route::get('/helper/sign', 'HelperController@createSignature');
    Route::get('/helper/check_sign', 'HelperController@checkSignature');
    Route::get('/helper/{id}/token', 'HelperController@getUserToken');
    
    // 定时任务
    Route::get('crontab/update_active', 'CrontabController@updateActiveProductStock');
});


/**
 * 测试
 */
Route::get('/test/rate', 'TestController@rateTest');
Route::get('/test/t', 'TestController@test');
Route::get('/test/mongodb', 'TestController@toMongodb');
Route::get('/test/push', 'TestController@push');
Route::get('/test/longlat', 'TestController@longlat');
Route::get('/test/event', 'TestController@event');
Route::get('/test/pay', 'TestController@wxpay');
Route::get('/test/format', 'TestController@format');
Route::get('/test/testCoupon', 'TestController@testCoupon');
Route::get('/test/div', function (){
    return bcdiv(500, 1000, 4);
});
Route::get('/test/invitation', 'TestController@invitation');
Route::get('/test/seeder', 'TestController@seeder');
