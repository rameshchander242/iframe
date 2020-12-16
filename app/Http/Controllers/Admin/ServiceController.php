<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\View;
use DataTables;
use Validator;

class ServiceController extends Controller
{
    public $nav = 'service';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $categories = Service::select('*');

        return DataTables::of($categories)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('service.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('service.show', $row->id) ) . '&nbsp; ' .
                        delete_button( route('service.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('status', function ($row) {
                        return '<div class="text-center">' . view_status($row->status) . '</div>';
                    })
                    ->editColumn('icon', function ($row) {
                        return ($row->icon != null) ? '<i class="fa-lg '.$row->icon.'"></i>' : 'N/A';
                    })
                    ->rawColumns(['action', 'icon', 'status'])
                    ->make(true);
    }

    public function create() {
        return view('admin.pages.'.$this->nav.'.create');
    }

    public function store(request $request) {
        $rules = [
            'name'  => 'required|max:200|unique:services,name',
            'icon'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $service = Service::create($request->all());
        
        return redirect()->route('service.index')->withSuccess("Service Create successfully");
    }

    public function edit($id) {
        $service = Service::find($id);

        return view('admin.pages.'.$this->nav.'.edit', $service);
    }

    public function update(request $request, $id) {
        $rules = [
            'name'  => 'required|max:200|unique:services,name,'.$id.',id',
            'icon'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $service = Service::findOrFail($id);
        $service->update($request->all());

        return redirect()->route('service.index')->withSuccess('Service Update successfully');
    }

    public function show($id) {
        $service = Service::find($id);

        return view('admin.pages.'.$this->nav.'.show', $service);
    }

    public function destroy($id) {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('service.index')->withSuccess('Service Delete successfully');
    }
}
