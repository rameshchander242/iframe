<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RepairCategory;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use DataTables;

class RepairCategoryController extends Controller
{
    use FileUploadTrait;
    public $nav = 'category';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $categories = Category::select('*');
        return DataTables::of($categories)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="'.route('category.edit', $row->id).'" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a> &nbsp;
                        <a href="'.route('category.show', $row->id).'" class="edit btn btn-success btn-sm"><i class="fa fa-eye"></i> View</a> &nbsp;' . 
                        delete_button( route('category.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('image', function ($q) {
                        return ($q->image != null) ? '<img height="50px" src="' . asset( upload_url('category') . $q->image ) . '">' : 'N/A';
                    })
                    ->rawColumns(['action', 'image'])
                    ->make(true);
    }

    public function create() {
        return view('admin.pages.'.$this->nav.'.create');
    }

    public function store(request $request) {
        $request->all();
        $data = [
            'status' => ($request->status == 'on' ? '1' : '0'),
            'position' => ( empty($request->position) ? '1' : $request->position),
        ];
        $request = $this->saveFiles($request, 'category');
        $request = new Request(array_merge($request->all(), $data));
        $category = Category::create($request->all());

        
        returnredirect()->route('category.index')->withSuccess("Category Create successfully");

    }

    public function edit($id) {
        $category = Category::find($id);
        return view('admin.pages.'.$this->nav.'.edit', $category);
    }

    public function update(request $request, $id) {
        $data = [
            'position' => ( empty($request->position) ? '1' : $request->position),
        ];
        $category = Category::findOrFail($id);
        $request = $this->saveFiles($request, 'category');
        $request = new Request(array_merge($request->all(), $data));
        $category->update($request->all());
        return redirect()->route('category.index')->withSuccess('Category Update successfully');
    }

    public function show($id) {
        $category = Category::find($id);
        return view('admin.pages.'.$this->nav.'.show', $category);
    }

    public function destroy($id) {
        // echo $id; exit;
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index')->withSuccess('Category Delete successfully');
    }
}
