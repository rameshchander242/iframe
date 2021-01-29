<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth as Authenticatable;
use DataTables;
use Validator;

class UserController extends Controller
{
    use FileUploadTrait;
    public $nav = 'user';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {

        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $users = User::select('*');

        return DataTables::of($users)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('client.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('client.show', $row->id) ) . '&nbsp; ' .
                        view_button( route('client.autologin', $row->id), 'fa-user', 'Auto Login', 'target="_blank"' ) . '&nbsp; ' .
                        delete_button( route('client.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('status', function ($row) {
                        return '<div class="text-center">' . view_status($row->status) . '</div>';
                    })
                    ->editColumn('image', function ($row) {
                        return ($row->image != null) ? '<img height="40px" width="60px" src="' . asset( upload_url($this->nav) . $row->image ) . '">' : 'N/A';
                    })
                    ->rawColumns(['action', 'image', 'status'])
                    ->make(true);
    }

    public function create() {
        return view('admin.pages.'.$this->nav.'.create');
    }

    public function store(request $request) {
        $rules = [
            'name'  => 'required|unique:users',
            'phone'  => 'required|numeric',
            'email'=>'required|email|unique:users',
            'password'  => 'required|min:6',
            'image' => 'required|image',
            'ctm_auth'  => 'required|unique:users',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $password = $request->password;
        $request = $this->saveFiles($request, $this->nav);
        $request->merge(['password' => Hash::make($password)]);
        $user = User::create($request->all());

        $body = 'Thanks for signing up for an Instant Quote Widget! Please follow this link and login with your credentials here:
            <br><br>
            '.url('/').'
            <br>
            Email - '.$request->email.'
            <br>
            Password - '.$password.'
            <br><br>
            Please navigate to Profile > Security to update your password at your earliest convenience!';
        $mailData = [
            'name'  => $user->name,
            'email' => $user->email,
            'subject'   => 'New Account on Instant Quote Widget',
            'body'  => $body,
        ];
        sendgrid_mail($mailData);

        return redirect()->route('client.index')->withSuccess("User Create successfully");
    }

    public function edit($id) {
        $user = User::find($id);

        return view('admin.pages.'.$this->nav.'.edit', $user);
    }

    public function update(request $request, $id) {
        $rules = [
            'name'  => 'required',
            'phone'  => 'required|numeric|unique:users,phone,'.$id.',id',
            'ctm_auth'  => 'required|unique:users,ctm_auth,'.$id.',id',
        ];
        if($request->password != '') {
            $rules['password']  = 'min:6';
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);
        $request = $this->saveFiles($request, $this->nav);
        if (! empty($request->input('password')) ) {
            $user->password = Hash::make($request->password);
        }
        if (isset($request->image) && !empty($request->image)) {
            $user->image = $request->image;
        }
        $user->phone = $request->phone;
        $user->about = $request->about;
        $user->status = $request->status;
        $user->name = $request->name;
        $user->ctm_auth = $request->ctm_auth;
        $user->save();

        return redirect()->route('client.index')->withSuccess('User Update successfully');
    }

    public function show($id) {
        $user = User::find($id);

        return view('admin.pages.'.$this->nav.'.show', $user);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('client.index')->withSuccess('User Delete successfully');
    }
}