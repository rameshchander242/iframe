<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->redirectTo = route('admin.dashboard');
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm() {
        //return redirect()->route('admin.dashboard');
        return view('admin.auth.login');
    }

    protected function guard(){
        return Auth::guard('admin');
    }

    protected function authenticated(Request $request, $user) {
        return redirect()->route('admin.dashboard');
    }

    public function logout(){
        $this->guard('admin')->logout();
        // Auth::logout();
        return redirect()->route('admin.login_page');
    }
}
