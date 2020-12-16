<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Series;
use App\Models\Item;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use DataTables;
use Validator;

class BrandController extends Controller
{
    use FileUploadTrait;
    public $nav = 'brand';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }

    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $brands = Brand::with('category');

        return DataTables::of($brands)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('brand.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('brand.show', $row->id) ) . '&nbsp; ' .
                        delete_button( route('brand.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('category', function ($row) {
                        return isset($row->category) ? $row->category->name : '--';
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
        $categories = Category::where('status', '1')->get()->pluck('name', 'id');

        return view('admin.pages.'.$this->nav.'.create', compact( 'categories'));
    }

    public function store(request $request) {
        $rules = [
            'name'  => 'required|max:200|unique:brands',
            'image' => 'required|image'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $request = $this->saveFiles($request, $this->nav);
        $brand = Brand::create($request->all());
        
        return redirect()->route('brand.index')->withSuccess("Brand Create successfully");
    }

    public function edit($id) {
        $brand = Brand::find($id);
        $categories = Category::where('status', '1')->get()->pluck('name', 'id');
        return view('admin.pages.'.$this->nav.'.edit', $brand, compact('categories'));
    }

    public function update(request $request, $id) {
        $rules = [
            'name'  => 'required|max:200|unique:brands,name,'.$id.',id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }

        $brand = Brand::findOrFail($id);
        $status = $brand->status;
        $request = $this->saveFiles($request, $this->nav);
        $brand->update($request->all());
        if ($request->status != $status ) {
            $this->disable_by_brand($id, $request->status);
        }

        return redirect()->route('brand.index')->withSuccess('Brand Update successfully');
    }

    public function show($id) {
        $brand = Brand::with('category')->find($id);

        return view('admin.pages.'.$this->nav.'.show', $brand);
    }

    public function destroy($id) {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('brand.index')->withSuccess('Brand Delete successfully');
    }

    private function disable_by_brand($brand_id, $status) {
        Series::where('brand_id', $brand_id)->update([
            'status' => $status
        ]);
        
        Item::where('brand_id', $brand_id)->update([
            'status' => $status
        ]);
    }
}
