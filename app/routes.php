<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
*   ADMINISTRATION ROUTES
*   @todo CSRF, Auth...
*/
Route::group(array('namespace' => 'admin'), function() {
    /* Authenticated */
    Route::group(array('before' => 'auth'), function() {
        Route::get('admin', array('as' => 'admin', 'uses' => 'LoginController@logged'));

        /* Administrators only */
        Route::group(array('before' => 'admin'), function() {
            /* CATEGORY ROUTES */
            Route::resource('admin/category', 'CategoryController', array('except' => array('show')));
            Route::get('admin/category/{category}/up', array('as' => 'admin.category.up', 'uses' => 'CategoryController@up'));
            Route::get('admin/category/{category}/down', array('as' => 'admin.category.down', 'uses' => 'CategoryController@down'));
            Route::post('admin/category/path', array('as' => 'admin.category.path', 'uses' => 'CategoryController@ajaxPath'));
            Route::post('admin/category/slug', array('as' => 'admin.category.slug', 'uses' => 'CategoryController@ajaxTitle'));

            Route::resource('admin/content', 'ContentController', array('except' => array('show')));
            Route::resource('admin/video', 'VideoController', array('only' => array('index', 'show', 'destroy')));
            Route::resource('admin/ban', 'BanController', array('except' => array('edit', 'update', 'show')));
            Route::post('admin/ban/user', array('as' => 'admin.ban.findUser', 'uses' => 'BanController@findUser'));
        });

        /* Users who have enough posts to use additional features */
        Route::group(array('before' => 'has-enough-posts|ban'), function() {
            Route::get('admin/content/user/create', array('as' => 'admin.content.user.create', 'uses' => 'ContentController@create'));
            Route::post('admin/content/store', array('as' => 'admin.content.store', 'uses' => 'ContentController@store'));
            Route::get('admin/content/user/index', array('as' => 'admin.content.user.index', 'uses' => 'ContentController@index'));
            Route::get('admin/content/user/{content}/edit/', array('as' => 'admin.content.user.edit', 'uses' => 'ContentController@edit'));
            Route::put('admin/content/user/{content}', array('as' => 'admin.content.user.update', 'uses' => 'ContentController@update'));
            Route::delete('admin/content/user/{content}', array('as' => 'admin.content.user.destroy', 'uses' => 'ContentController@destroy'));
            Route::any('admin/content/video/{content}', array('as' => 'admin.content.video', 'uses' => 'ContentController@videoUpload'))
                ->where(array('content' => '[0-9]+'));

            Route::get('admin/video/user/index', array('as' => 'admin.video.user.index', 'uses' => 'VideoController@index'));
            Route::get('admin/video/user/{video}', array('as' => 'admin.video.user.show', 'uses' => 'VideoController@show'));
            Route::delete('admin/video/user/{video}', array('as' => 'admin.video.user.destroy', 'uses' => 'VideoController@destroy'));
            Route::post('admin/video/user/get', array('as' => 'admin.video.user.get', 'uses' => 'VideoController@ajaxGetVideos'));
            Route::delete('admin/video/ajax/user/{video}', array('as' => 'admin.video.destroy.ajax.user', 'uses' => 'VideoController@ajaxDestroy'));
        });

        Route::get('admin/login/logout', array('as' => 'admin.logout', 'uses' => 'LoginController@logout'));
    });

    /* Unauthenticated */
    Route::group(array('before' => 'guest'), function() {
        /* LOGIN */
        Route::get('admin/login', array('as' => 'admin.login', 'uses' => 'LoginController@index'));
        Route::post('admin/login', array('as' => 'admin.login.post', 'uses' => 'LoginController@index'));
    });
});
/* END OF ADMINISTRATION ROUTES */

/**
* PUBLIC ROUTES (for now)
* @todo Lots of stuff
*/
Route::get('/', array('as' => 'home', 'uses' => 'HomeController@home'));
Route::get('search', array('as' => 'search', 'uses' => 'HomeController@search'));
Route::get('sitemap', array('as' => 'sitemap', 'uses' => 'HomeController@siteMap'));
Route::get('за-нас', array('as' => 'about', 'uses' => 'HomeController@about'));

Route::group(array('before' => 'csrf'), function() {
    Route::post('content/rate/{content}', array('as' => 'content.rate', 'uses' => 'HomeController@rateContent'));
});

// These routes must be at the very bottom
Route::get('/{path}/{content}', array('as' => 'content', 'uses' => 'HomeController@content'))
            ->where(array('path' => '(.+)', 'content' => '[0-9]+\-(.+)-t')); //[a-zA-Z0-9\-/]+
Route::get('/{path}', array('as' => 'page', 'uses' => 'HomeController@page'))
            ->where(array('path' => '(.+)'));
/* END OF ROUTES */

/* DEBUG */
Event::listen('illuminate.query', function($query, $bindings, $time, $name) {
    //echo $query . '<br><hr><br>';          // select * from my_table where id=? 
    //print_r($bindings); // Array ( [0] => 4 )
    //echo $time;         // 0.58 
});

App::missing(function($exception) {
    return Response::view('errors.404', array('url' => Request::url()), 404);
});
