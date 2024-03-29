<?php

//front route
Route::get('/','IndexController@index');
//listing page
Route::get('/products/{url}', 'ProductController@products');
//for product detail of particular id
Route::get('/product/{id}','ProductController@product');
//product attribute price
Route::get('/get-product-price','ProductController@getProductPrice'); 

//admin route
Route::match(['get','post'],'/admin/register', 'AdminController@register');
Route::match(['get','post'], '/admin', 'AdminController@login');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth']],function(){
    Route::get('/admin/dashboard','AdminController@dashboard');
    Route::get('/admin/settings','AdminController@settings');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get','post'],'/admin/update-pwd','AdminController@updatePassword');

    //route for admin category
    Route::match(['get', 'post'],'/admin/add-category','CategoryController@addCategory');
    Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory');
    Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory');
    Route::get('/admin/view-categories','CategoryController@viewCategories');

    //route for product
    Route::match(['get','post'],'/admin/add-product','ProductController@addProduct');
    Route::get('/admin/view-products','ProductController@viewProducts');
    Route::match(['get','post'],'/admin/edit-product/{id}','ProductController@editProduct');
    Route::get('/admin/delete-product-image/{id}','ProductController@deleteProductImage');
    Route::get('/admin/delete-product/{id}','ProductController@deleteProduct');

    Route::match(['get','post'],'/admin/add-attributes/{id}','ProductController@addAttributes');
    Route::match(['get','post'],'/admin/add-images/{id}','ProductController@addImages');
    Route::get('/admin/delete-attribute/{id}','ProductController@deleteattribute');
    Route::get('/admin/delete-alt-image/{id}','ProductController@deleteAltImage');
});

Route::get('/logout','AdminController@logout');