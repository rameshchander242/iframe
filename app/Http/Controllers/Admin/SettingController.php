<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use DataTables;
use Validator;

class SettingController extends Controller
{
    use FileUploadTrait;
    public $nav = 'setting';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $settings = Setting::select('*');

        return DataTables::of($settings)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('setting.edit', $row->id) );
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function create() {
        return view('admin.pages.'.$this->nav.'.create');
    }

    public function store(request $request) {
        $rules = [
            'name'  => 'required|max:100|unique:settings',
            'title'  => 'required|max:200',
            'value'  => 'required|max:200',
            'type'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $setting = Setting::create($request->all());
        
        return redirect()->route('setting.index')->withSuccess("Setting Create successfully");
    }

    public function edit($id) {
        $setting = Setting::find($id);

        return view('admin.pages.'.$this->nav.'.edit', $setting);
    }

    public function update(request $request, $id) {
        $rules = [
            'value'  => 'required|max:200',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $setting = Setting::findOrFail($id);
        $setting->update($request->all());

        return redirect()->route('setting.index')->withSuccess('Setting Update successfully');
    }
}
