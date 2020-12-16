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

class SeriesController extends Controller
{
    use FileUploadTrait;
    public $nav = 'series';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $series = Series::with('category')->with('brand');

        return DataTables::of($series)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('series.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('series.show', $row->id) ) . '&nbsp; ' .
                        delete_button( route('series.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('category', function ($row) {
                        return isset($row->category) ? $row->category->name : '--';
                    })
                    ->editColumn('brand', function ($row) {
                        return isset($row->brand) ? $row->brand->name : '--';
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

        return view('admin.pages.'.$this->nav.'.create', compact('categories'));
    }

    public function store(request $request) {
        $rules = [
            'name'  => 'required|max:200|unique:series',
            'image' => 'required|image'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $request = $this->saveFiles($request, $this->nav);
        $series = Series::create($request->all());
        
        return redirect()->route('series.index')->withSuccess("Series Create successfully");
    }

    public function edit($id) {
        $series = Series::find($id);
        $categories = Category::where('status', '1')->get()->pluck('name', 'id');
        $brands = Brand::where(['status'=>'1', 'category_id'=>$series->category_id])->get()->pluck('name', 'id');

        return view('admin.pages.'.$this->nav.'.edit', $series, compact('categories', 'brands'));
    }

    public function update(request $request, $id) {
        $rules = [
            'name'  => 'required|max:200|unique:series,name,'.$id.',id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $series = Series::findOrFail($id);
        $status = $series->status;
        $request = $this->saveFiles($request, $this->nav);
        $series->update($request->all());
        if ($request->status != $status ) {
            $this->disable_by_series($id, $request->status);
        }

        return redirect()->route('series.index')->withSuccess('Series Update successfully');
    }

    public function show($id) {
        $series = Series::with('category')->with('brand')->find($id);

        return view('admin.pages.'.$this->nav.'.show', $series);
    }

    public function destroy($id) {
        $series = Series::findOrFail($id);
        $series->delete();

        return redirect()->route('series.index')->withSuccess('Series Delete successfully');
    }

    private function disable_by_series($series_id, $status) {
        Item::where('series_id', $series_id)->update([
            'status' => $status
        ]);
    }
}
