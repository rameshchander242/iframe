<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iframe;
use App\Models\User;
use App\Models\Location;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use DataTables;

class IframeController extends Controller
{
    use FileUploadTrait;
    public $nav = 'iframe';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }

    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $iframes = Iframe::with('user');

        return DataTables::of($iframes)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('iframe.edit', $row->id) ) . '&nbsp; ' .
                        //view_button( route('iframe.show', $row->id) ) . '&nbsp; ' .
                        delete_button( route('iframe.destroy', $row->id) );
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

    public function rules() {
        $rules = [
            'name'  => 'required',
            'user_id'  => 'required',
            'location'  => 'required',
            'category'  => 'required',
        ];
        return $rules;
    }

    public function create() {
        $users = User::where('status', '1')->get()->pluck('name', 'id');

        return view('admin.pages.'.$this->nav.'.create', compact( 'users'));
    }

    public function store(request $request) {
        $request->all();
        $validator = $request->validate($this->rules());
        $iframe = Iframe::create($request->all());
        
        
        return redirect()->route('iframe.index')->withSuccess("Iframe Create successfully");
    }

    public function edit($id) {
        $iframe = Iframe::findorfail($id);
        

        $users = User::where('status', '1')->get()->pluck('name', 'id');
        $locations = Location::where( ['user_id'=>$iframe['user_id'], 'status'=>'1'] )->get();
        $categories = Category::where('status', '1')->get();
        $services = Service::where('status', '1')->get();
        $catBrands = Category::with('brands')->whereIn('id', $iframe['category'])->get();

        return view('admin.pages.'.$this->nav.'.edit', compact('iframe', 'users', 'locations', 'categories', 'catBrands', 'services'));
    }

    public function update(request $request, $id) {
        $iframe = Iframe::findOrFail($id);
        $validator = $request->validate($this->rules());
        $categories = Category::find($request->category);
        $iframe->categories()->sync( $categories );
        

        foreach ($categories as $category) {
            $services = [];
            // $services = Service::find( ($request->service[$category->id] ?? []) );
            foreach ($request->service[$category->id] as $service) {
                $services[$service]['category_id'] = $category->id;
            }
            // $iframe->category_services()->sync(
            //     [3 => ['category_id' => 1], 3 => ['category_id' => 2]]
            // );
            // $services =  [ 'category_id'=>$category->id, 'service_id'=>$request->service[$category->id][0] ];
            print_r($services);
            echo "<br>";
            $iframe->category_services()->sync( $services );
        }
        echo "<pre>"; print_r($services); exit;
        $iframe->update($request->all());
        
        //$iframe->categories()->attach($category);
        
        // print_r($iframe); exit;

        return redirect()->route('iframe.index')->withSuccess('Iframe Update successfully');
    }

    public function show($id) {
        $iframe = Iframe::with('user')->find($id);
        $iframe['user']['locations'] = Location::wherein('id', $iframe->location)->get();

        $allBrands = [];
        foreach ($iframe->brand as $brand) {
            $allBrands = array_merge( $allBrands, $brand );
        }
        $iframe['categories'] = Category::with(['brands' => function($query) use ($allBrands) {
            $query->wherein('id', $allBrands);
        }])->wherein('id', $iframe->category)->get();
        

        $services = $iframe->service;
        $allservices = [];
        foreach ($iframe['categories'] as $key=>$category) {
            $ser = $iframe->service[$category['id']];
            $iframe['categories'][$key]['services'] = Service::wherein('id', $ser)->get();
        }

        return view('admin.pages.'.$this->nav.'.show', $iframe);
    }

    public function destroy($id) {
        $iframe = Iframe::findOrFail($id);
        $iframe->delete();

        return redirect()->route('iframe.index')->withSuccess('Iframe Delete successfully');
    }

    public function user_locations(request $request, $return=false) {
        $locations = Location::where( ['user_id'=>$request->id, 'status'=>'1'] )->get();
        if ($return === true) {
            return $locations;
        }
        if ( $locations->isEmpty() ) {
            return '<div class="text-danger">Not Found any Location</div>';
        }
        
        return view('admin.pages.'.$this->nav.'.ajax.location', compact('locations'));
    }

    public function categories(request $request) {
        $categories = Category::where('status', '1')->get();
        if ( $categories->isEmpty() ) {
            return '<div class="text-danger">Not Found any Category</div>';
        }
        
        return view('admin.pages.'.$this->nav.'.ajax.category', compact('categories'));
    }

    public function category_brands(request $request) {
        $services = Service::where('status', '1')->get();
        $categories = Category::with('brands')->whereIn('id', $request->cats)->get();
        
        return view('admin.pages.'.$this->nav.'.ajax.service-brand', compact('services', 'categories'));
    }
}
