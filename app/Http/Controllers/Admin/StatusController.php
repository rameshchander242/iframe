<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use DataTables;

class CategoryController extends Controller
{
    use FileUploadTrait;
    public $nav = 'category';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $categories = Category::select('*');

        return DataTables::of($categories)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('category.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('category.show', $row->id) ) . '&nbsp; ' .
                        delete_button( route('category.destroy', $row->id) );
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

    public function create() {
        return view('admin.pages.'.$this->nav.'.create');
    }

    public function store(request $request) {
        $request->all();
        $request = $this->saveFiles($request, $this->nav);
        $category = Category::create($request->all());
        
        return redirect()->route('category.index')->withSuccess("Category Create successfully");
    }

    public function edit($id) {
        $category = Category::find($id);

        return view('admin.pages.'.$this->nav.'.edit', $category);
    }

    public function update(request $request, $id) {
        $category = Category::findOrFail($id);
        $request = $this->saveFiles($request, $this->nav);
        $category->update($request->all());

        return redirect()->route('category.index')->withSuccess('Category Update successfully');
    }

    public function show($id) {
        $category = Category::find($id);

        return view('admin.pages.'.$this->nav.'.show', $category);
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')->withSuccess('Category Delete successfully');
    }
}
