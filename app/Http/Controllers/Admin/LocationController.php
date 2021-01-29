<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\View;
use DataTables;
use Validator;

class LocationController extends Controller
{
    public $nav = 'location';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        $users = User::where('status', '1')->orderBy('name', 'asc')->get()->pluck('name','name');

        return view('admin.pages.'.$this->nav.'.index', compact('users'));
    }

    public function list_ajax() {
        $locations = Location::with('user')->select('locations.*');
        if (isset($_GET['columns'][4]['search']['value']) && !empty($_GET['columns'][4]['search']['value'])) {
            session([ 'location_client' => $_GET['columns'][4]['search']['value'] ]);
        }

        return DataTables::of($locations)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('location.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('location.show', $row->id) ) . '&nbsp; ' .
                        delete_button( route('location.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('user', function ($row) {
                        return isset($row->user) ? $row->user->name : '--';
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

    public function create() {
        $users = User::where('status', '1')->orderBy('name', 'asc')->get()->pluck('name', 'id');
        $hrs = [];
        $location_client = '';
        $slc = session('location_client');
        if ( isset($slc) && !empty($slc)) {
            $userData = User::where('name', $slc)->first();
            $location_client = $userData->id;
        }
        
        $range=range(strtotime("00:00"),strtotime("23:30"), 30*60);
        foreach($range as $time){
                $dtime = date("h:i A", $time)."\n";
                $hrs[$dtime] = $dtime;
        }
        $hours_Arr = ['Mon'=>'Monday', 'Tue'=>'Tuesday', 'Wed'=>'Wednesday', 'Thur'=>'Thursday', 'Fri'=>'Friday', 'Sat'=>'Saturday', 'Sun'=>'Sunday'];

        return view('admin.pages.'.$this->nav.'.create', compact( 'users', 'hrs', 'hours_Arr', 'location_client'));
    }

    public function store(request $request) {
        $rules = [
            'store_name'  => 'required|max:200|unique:locations',
            'address_1'  => 'required',
            'email'  => 'required|email',
            'city'  => 'required',
            'ctm_code'  => 'required',
            'phone'=> 'required|numeric|digits_between:6,14',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $location = Location::create($request->all());

        return redirect()->route('location.index')->withSuccess("Location Create successfully");
    }

    public function edit($id) {
        $location = Location::find($id);
        $users = User::where('status', '1')->orderBy('name', 'asc')->get()->pluck('name', 'id');
        $hrs = [];
        
        $range=range(strtotime("00:00"),strtotime("23:30"), 30*60);
        foreach($range as $time){
                $dtime = date("h:i A", $time)."\n";
                $hrs[$dtime] = $dtime;
        }
        $hours_Arr = ['Mon'=>'Monday', 'Tue'=>'Tuesday', 'Wed'=>'Wednesday', 'Thur'=>'Thursday', 'Fri'=>'Friday', 'Sat'=>'Saturday', 'Sun'=>'Sunday'];

        return view('admin.pages.'.$this->nav.'.edit', $location, compact('users', 'hrs', 'hours_Arr'));
    }

    public function update(request $request, $id) {
        $rules = [
            'store_name'  => 'required|max:200|unique:locations,store_name,'.$id.',id',
            'address_1'  => 'required',
            'email'  => 'required|email',
            'city'  => 'required',
            'ctm_code'  => 'required',
            'phone'=> 'required|numeric|digits_between:6,14',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $location = Location::findOrFail($id);
        $location->update($request->all());

        return redirect()->route('location.index')->withSuccess('Location Update successfully');
    }

    public function show($id) {
        $location = Location::with('user')->find($id);

        return view('admin.pages.'.$this->nav.'.show', $location);
    }

    public function destroy($id) {
        $location = Location::findOrFail($id);
        $location->delete();
        
        return redirect()->route('location.index')->withSuccess('Location Delete successfully');
    }
}
