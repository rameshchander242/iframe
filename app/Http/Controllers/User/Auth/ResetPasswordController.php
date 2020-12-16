<?php

namespace App\Http\Controllers\User\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct() {
        $this->middleware('guest');
    }
    public function index(Request $request)
    {
        return view('user.reset-password',["token"=>$request->token]);
    }

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());
        // $this->broker()->validator(function (array $credentials){
        //     [$password, $confirm] = [
        //         $credentials['password'],
        //         $credentials['password_confirmation'],
        //     ];
        //     return $password === $confirm && mb_strlen($password) >= 6;
        // });
                
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
