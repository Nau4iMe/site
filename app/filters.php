<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
    //
});


App::after(function($request, $response)
{
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    if (Auth::guest()) return Redirect::guest('admin/login');
});


Route::filter('auth.basic', function()
{
    return Auth::basic('member_name');
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
    if (Auth::check()) return Redirect::to('admin');
});

/*
|--------------------------------------------------------------------------
| Custom Admin Filters
|--------------------------------------------------------------------------
|
|
|
*/

Route::filter('admin', function() {
    if (Session::get('is_admin') != true)
        return Redirect::route('admin')->with('global_error', 'Не сте администратор!');
});

/*
|--------------------------------------------------------------------------
| Custom User Filters
|--------------------------------------------------------------------------
|
|
|
*/

Route::filter('has-enough-posts', function() {
    if (User::getUserParam(Auth::User()->id_member, 'posts') < User::$enough_posts)
        return Redirect::route('admin')->with('global_error', 'Нямате достатъчно постове!');
});

Route::filter('ban', function() {
    $banned = Ban::where('user_id', Auth::User()->id_member)->count();
    if ($banned != 0) {
        return Redirect::route('admin')->with('global_error', 'Вашия достъп до това съдържание е спрян!');
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
    if (Session::token() != Input::get('_token'))
    {
        throw new Illuminate\Session\TokenMismatchException;
    }
});