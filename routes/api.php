<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['as' => 'api.', 'namespace' => 'API'], function () {

    Route::post('login', 'AuthController@login')->name('api.login');
    Route::post('register', 'AuthController@register')->name('api.register');
    
    Route::post('get-price', 'RestaurantApiController@GetRestPrice');

    Route::get('setting_contacts', 'HomePageController@setting_contacts')->name('api.setting_contacts');
    Route::get('setting', 'HomePageController@setting')->name('api.setting');

    Route::get('category', 'HomePageController@category')->name('api.category');
    Route::get('sub_category', 'HomePageController@sub_category')->name('api.sub_category');
    Route::get('city', 'HomePageController@city')->name('api.city');
    Route::get('country', 'HomePageController@country');
    Route::any('offers', 'HomePageController@offers');
    Route::any('UpdateOfferCount', 'HomePageController@UpdateOfferCount');

    Route::post('get-restaurant-category', 'HomePageController@restaurant')->name('api.restaurant');
    Route::get('get-restaurant-api', 'RestaurantApiController@GetRestaurant')->name('api.restaurant');
    Route::get('get-restaurant-category/{id}', 'RestaurantApiController@GetRestaurantCategory')->name('api.restaurant');
    Route::get('get-product-api/{id}', 'RestaurantApiController@GetProduct')->name('api.restaurant');
    Route::post('search-product', 'RestaurantApiController@GetProductSearch')->name('api.restaurant');
    // Zita added
    Route::post('search-restaurant', 'RestaurantApiController@SearchRestaurant')->name('api.restaurant');
    // Zita added
    Route::get('get-verify-sms/{phone}', 'RestaurantApiController@GetVerifySMS')->name('api.restaurant');
    Route::post('restaurant_view', 'HomePageController@restaurant_view')->name('api.restaurant_view');

    Route::post('store-order-information', 'HomePageController@cart_save')->name('api.cart_save');
    Route::post('store-order-payment', 'HomePageController@payment_type')->name('api.payment_type');
    Route::post('store-order-address', 'HomePageController@cart_save_address')->name('api.cart_save_address');
    Route::post('send-verify-sms', 'HomePageController@complete_order')->name('api.complete_order');
    Route::post('check-verifycode', 'HomePageController@complete_order_verfiy')->name('api.complete_order_verfiy');
    Route::post('restaurant_comment', 'HomePageController@restaurant_comment')->name('api.restaurant_comment');
    Route::post('get-client-comment', 'HomePageController@client_comment')->name('api.client_comment');
    Route::post('store-pos-order', 'HomePageController@createPosOrder');
    Route::post('store-pos-order-new', 'HomePageController@createPosOrderNew');
    
    Route::any('order-history', 'OrderApiController@OrderHistory');
    Route::get('cancel-order/{ID}', 'OrderApiController@CancelOrder');
    
    Route::get('EmailTest', 'OrderApiController@EmailTest');
    
    Route::get('getUnreadCounter', 'OrderApiController@getUnreadCounter');
    Route::get('getHelpLine', 'HomePageController@getHelpLine');
    
    Route::get('getUnreadOrders', 'OrderApiController@getUnreadOrders');
    
    Route::post('save-order-new', 'HomePageController@SaveOrderNew');
    Route::get('send-order-email/{ID}', 'HomePageController@SendOrderEmail');
    
    Route::post('save_fcm_token', 'NotificationController@SaveFCM');
    Route::post('logout', 'NotificationController@logout');
    Route::post('register_restaurant', 'RestaurantApiController@Register');
    Route::post('register_rider', 'RiderController@RegisterRider');
    
    
    Route::post('RegisterAds', 'AdvertController@RegisterAds');
    Route::any('ResturantOrderHistory', 'OrderApiController@ResturantOrderHistory');
    Route::any('OrderDetail', 'OrderApiController@OrderDetail');
    
    Route::any('VerifyCoupon', 'OrderApiController@VerifyCoupon');
    
    Route::post('AcceptOrder', 'OrderApiController@AcceptOrder');
    Route::post('RejectedOrder', 'OrderApiController@RejectedOrder');
    Route::get('UpdateMyOrder/{id}', 'OrderApiController@UpdateMyOrder');
    Route::post('ViewOrder', 'OrderApiController@ViewOrder');
    Route::post('PickupReady', 'OrderApiController@PickupReady');
    Route::post('DriverList', 'OrderApiController@DriverList');
    Route::post('SendOrderToDriver', 'OrderApiController@SendOrderToDriver');
    
    Route::get('CrewList', 'CrewController@CrewList');
    Route::get('GetNotificationList', 'OrderApiController@GetNotificationList');
    
    Route::get('PrintOrder/{ID}', 'OrderApiController@viewPrintOrder');
    Route::get('ViewOrder/{ID}', 'OrderApiController@ViewInvoice');
    Route::get('TodayOrderSummary/{ID}', 'OrderApiController@TodayOrderSummary');
    Route::get('TodayOrderSummaryPDF/{ID}', 'OrderApiController@TodayOrderSummaryPDF');

    Route::group(['prefix' => 'secret-api-not-allow-test'], function () {
        Route::post('reset-product-avatar/{product_id}', 'ProductController@resetProductAvatar');
        Route::post('delete-product/{product_id}', 'ProductController@deleteProduct');
    });
    
    
    Route::get('GetOrderDriverLocation', 'OrderApiController@GetOrderDriverLocation');
    Route::post('RestaurantPaymentSummary', 'DriverController@RestaurantPaymentSummary');
    Route::post('RestaurantPaymentCollect', 'DriverController@RestaurantPaymentCollect');
    
    Route::get('ChageOrderTime', 'DriverController@ChageOrderTime');
    
    Route::post('SaveDriverRestaurantComment', 'DriverController@SaveDriverRestaurantComment');
    Route::post('customer_driver_comment', 'DriverController@customer_driver_comment');
    
    Route::group(['prefix' => 'driver'], function () {
        Route::post('login', 'DriverController@Login');
        Route::post('AutoLogout', 'DriverController@AutoLogout');
        
        Route::post('SaveComplaint', 'DriverController@SaveComplaint');
        
        Route::post('AllOrders', 'DriverController@AllOrders');
        Route::post('MyOrders', 'DriverController@MyOrders');
        
        Route::post('CheckForNewOrder', 'DriverController@CheckForNewOrder');
        
        Route::post('ChangeStatus', 'DriverController@ChangeStatus');
        Route::any('PaymentSummary', 'DriverController@PaymentSummary');
        Route::post('UpdateDriverLocation', 'DriverController@UpdateDriverLocation');
        Route::post('SavePaymentHandover', 'DriverController@SavePaymentHandover');
        Route::post('ShowDerliveryCharges', 'DriverController@ShowDerliveryCharges');
    });
});
