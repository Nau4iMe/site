<?php
namespace admin;

use Session;
use View;
use Auth;
use Redirect;
use User;
use Input;
use Request;
use Sanitizer;

class LoginController extends \BaseController {

    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function index()
    {
        session_start();
        $user = Input::get('username');
        $pass = Input::get('password');

        require_once User::getSmfApiPath();
        if (Request::isMethod('post')) {

            $sanitize = new Sanitizer(Input::all());
            Input::merge($sanitize->get());

            if (smfapi_authenticate($user, $pass) == true) {
                smfapi_login($user);
                $userModel = User::where('member_name', $user)->orWhere('email_address', $user)->first();

                // $userModel->is_admin = $user_info['is_admin']; // We don't work :(
                // $userModel->avatar = $user_info['avatar'];
                
                // Whatever is set manually, must be unset manually too
                Session::set('is_admin', $user_info['is_admin']);
                Session::set('posts', $user_info['posts']);

                Auth::login($userModel);
                return Redirect::route('admin');

            }
            return View::make('layouts.admin.guest')->with('global_error', 'Грешно име или парола!');
        }

        return View::make('layouts.admin.guest');
    }

    public function logged()
    {
        require_once User::getSmfApiPath();
        $info = smfapi_getUserData(Auth::user()->member_name);

        $this->data['posts'] = $info['posts'];
        $this->data['active'] = 'home';

        return View::make('admin.index', $this->data);
    }

    public function logout()
    {
        Auth::logout();
        Session::forget('is_admin');
        Session::forget('posts');
        return Redirect::to('/');
    }

}
