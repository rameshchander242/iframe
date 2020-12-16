<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\View;
use DataTables;
use Auth;
use Validator;

class LocationController extends Controller
{
    public $nav = 'location';

    public function __construct() {
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('user.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $locations = Location::where('user_id', Auth::id());

        return DataTables::of($locations)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = view_button( route('user.location.show', $row->id) ) . '&nbsp; ' .
                        edit_button( route('user.location.edit', $row->id) );
                        return $btn;
                    })
                    ->editColumn('status', function ($row) {
                        return '<div class="text-center">' . view_status($row->status) . '</div>';
                    })
                    ->editColumn('image', function ($row) {
                        return ($row->image != null) ? '<img height="50px" src="' . asset( upload_url($this->nav) . $row->image ) . '">' : 'N/A';
                    })
                    ->rawColumns(['action', 'image', 'status'])
                    ->make(true);
    }

    public function edit($id) {
        $location = Location::find($id);
        $users = User::where('status', '1')->get()->pluck('name', 'id');
        $hrs = [];
        for($i=1; $i<24; $i++) {
            $hrs[$i . ':00'] = $i . ':00';
        }

        return view('user.pages.'.$this->nav.'.edit', $location, compact('users', 'hrs'));
    }

    public function update(request $request, $id) {
        $rules = [
            'store_name'  => 'required|max:200|unique:locations,store_name,'.$id.',id',
            'email'  => 'required|email',
            'address_1'  => 'required',
            'city'  => 'required',
            'phone'=> 'required|numeric|digits_between:6,14',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $location = Location::findOrFail($id);
        $location->update($request->all());

        return redirect()->route('user.location.index')->withSuccess('Location Update successfully');
    }

    public function show($id) {
        $location = Location::with('user')->find($id);

        return view('user.pages.'.$this->nav.'.show', $location);
    }
}
