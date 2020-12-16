<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    public function auto_login($id) {
        if ( Auth::guard('admin')->check() ) {
            $user = User::where('id',$id)->first();
            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            }
        }
        return abort(404);
    }
}
