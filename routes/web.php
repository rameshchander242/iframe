<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::group([
    'namespace' => 'User',
], function () {
    Route::group([
        'namespace' => 'Auth',
    ], function () {
        // Authentication Routes...
        Route::get('login', 'LoginController@showLoginForm')->name('login_page');
        Route::post('login', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        
   
        Route::get('password/forgot', ['uses'=>'ForgotPasswordController@index', 'as'=>'password.reset']);
        Route::post('password/email', ['uses'=>'ForgotPasswordController@sendResetLinkEmail', 'as'=>'password.email']);
        Route::get('password/reset/{token}', ['uses'=>'ResetPasswordController@index', 'as'=>'password.reset.token']);
        Route::post('password/reset', ['uses'=> 'ResetPasswordController@reset', 'as'=>'password.update']);
    });
    Route::get('auto-login/{id}', 'LoginController@auto_login')->name('client.autologin');

    

    Route::get('/', 'UserController@index')->name('front');
    Route::get('home', 'UserController@index')->name('home');
    Route::get('dashboard', 'UserController@index')->name('dashboard');
    
    Route::get('profile', 'ProfileController@index')->name('profile');
    Route::post('basic', ['uses'=>'ProfileController@edit','as' => 'basic-profile']);
    Route::post('security', ['uses' => 'ProfileController@updateSecurity', 'as' => 'security']);
    Route::post('upload-image', ['uses' => 'ProfileController@uploadImage', 'as' => 'upload-image']);

    
    Route::get('price-list', 'IframeController@index')->name('user.iframe.index');
    Route::get('price-list/{id}/edit', 'IframeController@edit')->name('user.iframe.edit');
    Route::put('price-list/{id}/update', 'IframeController@update')->name('user.iframe.update');
    Route::delete('price-list/{id}', 'IframeController@index')->name('user.iframe.destroy');
    Route::get('iframe-ajax', 'IframeController@list_ajax')->name('user.iframe.listajax');
    
    Route::get('widget-info', 'IframeInfoController@index')->name('user.iframe_info.index');
    Route::get('widget-info/{id}/edit', 'IframeInfoController@edit')->name('user.iframe_info.edit');
    Route::put('widget-info/{id}/update', 'IframeInfoController@update')->name('user.iframe_info.update');
    Route::get('widget-info/{id}', 'IframeInfoController@show')->name('user.iframe_info.show');
    Route::get('widget-iframe-ajax', 'IframeInfoController@list_ajax')->name('user.iframe_info.listajax');
    
    Route::get('email-template', 'EmailTemplateController@index')->name('user.email-template.index');
    Route::get('email-template/create', 'EmailTemplateController@create')->name('user.email-template.create');
    Route::post('email-template', 'EmailTemplateController@store')->name('user.email-template.store');
    Route::get('email-template/{id}', 'EmailTemplateController@show')->name('user.email-template.show');
    Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('user.email-template.edit');
    Route::put('email-template/{id}/update', 'EmailTemplateController@update')->name('user.email-template.update');
    Route::get('email-template-ajax', 'EmailTemplateController@list_ajax')->name('user.email-template.listajax');
    
    Route::get('location', 'LocationController@index')->name('user.location.index');
    Route::get('location/{id}', 'LocationController@show')->name('user.location.show');
    Route::get('location/{id}/edit', 'LocationController@edit')->name('user.location.edit');
    Route::put('location/{id}/update', 'LocationController@update')->name('user.location.update');
    Route::get('location-ajax', 'LocationController@list_ajax')->name('user.location.listajax');
    
    Route::get('queries', 'QueryController@index')->name('user.queries.index');
    Route::get('queries/ajax', 'QueryController@list_ajax')->name('user.queries.ajax');
    Route::get('queries/{id}', 'QueryController@show')->name('user.queries.show');
    Route::get('queries/{id}/reply', 'QueryController@reply')->name('user.queries.reply');
    Route::post('queries/{id}/send', 'QueryController@send')->name('user.queries.send');
});


Route::group([
    'name' => 'admin.', 
    'prefix' => 'admin', 
    'namespace' => 'Admin',
], function () {
        Route::group([
            'namespace' => 'Auth',
        ], function () {
            // Authentication Routes...
            Route::get('login', 'LoginController@showLoginForm')->name('admin.login_page');
            Route::post('admin-sign', 'LoginController@login')->name('admin.login');
            Route::get('logout', 'LoginController@logout')->name('admin.logout');
        });
        
        Route::group([
            'middleware' => [
                'auth:admin',
            ],
        ], function () {
            Route::get('/', 'AdminController@index')->name('admin.dashboard');
            Route::get('home', 'AdminController@index')->name('admin.dashboard');
            Route::get('dashboard', 'AdminController@index')->name('admin.dashboard');
            
            /* Category Route */
            Route::resource('category', 'CategoryController');
            Route::get('category-ajax', 'CategoryController@list_ajax')->name('category.listajax');
            
            /* Brand Route */
            Route::resource('brand', 'BrandController');
            Route::get('brand-ajax', 'BrandController@list_ajax')->name('brand.listajax');

            /* Series Route */
            Route::resource('series', 'SeriesController');
            Route::get('series-ajax', 'SeriesController@list_ajax')->name('series.listajax');

            /* Item Route */
            Route::resource('item', 'ItemController');
            Route::get('item-ajax', 'ItemController@list_ajax')->name('item.listajax');

            /* Client Route */
            Route::resource('client', 'UserController');
            Route::get('client-ajax', 'UserController@list_ajax')->name('client.listajax');

            /* Location Route */
            Route::resource('location', 'LocationController');
            Route::get('location-ajax', 'LocationController@list_ajax')->name('location.listajax');

            /*  Service */
            Route::resource('service', 'ServiceController');
            Route::get('service-ajax', 'ServiceController@list_ajax')->name('service.listajax');

            /* Iframe Route */
            Route::resource('iframe', 'IframeController');
            Route::get('iframe-ajax', 'IframeController@list_ajax')->name('iframe.listajax');
            Route::post('iframe-user-locations', 'IframeController@user_locations')->name('iframe.user.locations');
            Route::post('iframe-categories', 'IframeController@categories')->name('iframe.categories');
            Route::post('iframe-category-brands', 'IframeController@category_brands')->name('iframe.category.brands');
            
            Route::get('queries', 'QueryController@index')->name('admin.queries.index');
            Route::get('queries/ajax', 'QueryController@list_ajax')->name('admin.queries.ajax');
            Route::get('queries/{id}', 'QueryController@show')->name('admin.queries.show');

            
            Route::get('profile', 'ProfileController@index')->name('admin.profile');
            Route::post('basic', ['uses'=>'ProfileController@edit','as' => 'admin-basic-profile']);
            Route::post('security', ['uses' => 'ProfileController@updateSecurity', 'as' => 'admin-security']);
            Route::post('upload-image', ['uses' => 'ProfileController@uploadImage', 'as' => 'admin-upload-image']);

            /* Email Template Route */
            Route::resource('email-template', 'EmailTemplateController');
            Route::get('email-template-ajax', 'EmailTemplateController@list_ajax')->name('email-template.listajax');
            
            Route::get('error-log', 'ErrorLogController@index')->name('error-log.index');
            Route::get('error-log-ajax', 'ErrorLogController@list_ajax')->name('error-log.listajax');
            Route::get('error-log/{id}', 'ErrorLogController@show')->name('error-log.show');

            /* Instruction Route */
            Route::resource('instruction', 'InstructionController');
            Route::get('instruction-ajax', 'InstructionController@list_ajax')->name('instruction.listajax');
            
            /* Setting Route */
            Route::resource('setting', 'SettingController');
            Route::get('setting-ajax', 'SettingController@list_ajax')->name('setting.listajax');

            Route::get('/cache/clear', function() {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                return redirect()->route('admin.dashboard')->with('cache','System Cache Has Been Removed.');
              })->name('admin-cache-clear');
        });
});


Route::post('ajax/category-brands', 'AjaxController@category_brands')->name('ajax.category-brands');
Route::post('ajax/brand-series', 'AjaxController@brand_series')->name('ajax.brand-series');


Route::get('iframe/{id}', 'IframeController@iframe')->name('iframe.widget');
Route::post('iframe/submit_widget', 'IframeController@submit_widget')->name('iframe.widget.submit');