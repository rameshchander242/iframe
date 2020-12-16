<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller {
    
    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', 'profile');
    }

    protected function validator(array $data) {
        $rules = [
            'contact'=> 'required|numeric|digits_between:6,14',
            'name' => 'required|string|max:255',
        ];

        return Validator::make($data,$rules);
    }

    protected function validatorSecurity(array $data, $type=1) {
        $rules = [
            'old_password'=>'required',
            'password'=>'required|min:6|confirmed'
        ];

        return Validator::make($data,$rules);
    }

    public function index(Request $request) {
        $user = Auth::user();

        return view("admin.pages.profile.index",["user"=>$user]);
    }

    public function updateSecurity(Request $request) {
        $validator = $this->validatorSecurity($request->all(), 2);

        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }

        if(!Hash::check($request->old_password, Auth::user()->password)) {
            return back()->withErrors(['old_password'=>'You have entered a wrong old password']);
        }

        $update = ["password"=>Hash::make($request->password)];
        Admin::where("id",Auth::user()->id)->update($update);
        
        return back()->withSuccess("Password has been successfully updated");
    }

    protected function image_validator(array $data) {
        $rules = [
            'profile_img'=>'required|image'
        ];
        return Validator::make($data,$rules);
    }

    public function uploadImage(Request $request) {
        $validator = $this->image_validator($request->all());

        if($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $user_id = Auth::user()->id;
        $image = $request->file('profile_img');
        $destinationPath = public_path().'/admin/images';
        $filename = time()."_".str_random(20).".".$image->getClientOriginalExtension();
        if ($image->move($destinationPath,$filename)) {
            User::where("id",$user_id)->update(["profile_img"=>$filename]);
            return response()->json(['message'=>$filename],200); 
        }
   
    }

    public function edit(Request $request) { 

        $validator = $this->validator($request->all());
        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $userData = [
            'name'=>$request->name,
            'contact'=>$request->contact
        ]; 
        Admin::where("id",$request->id)->update($userData);
        
        return back()->withSuccess("Profile has been successfully updated");
    }
}