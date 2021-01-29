<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Widget_Quote;
use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use DataTables;

class QueryController extends Controller
{
    use FileUploadTrait;
    public $nav = 'query';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        // $queries = Widget_Quote::select('*')
        // ->with('user', 'category', 'brand', 'series', 'item', 'service', 'location')
        // ->get();
        // echo $queries[0]->email;
        // echo "<pre>"; print_r($queries); exit;

        $users = User::orderBy('name', 'asc')->get()->pluck('name','name');
        $categories = Category::get()->pluck('name','name');
        $services = Service::get()->pluck('name','name');

        return view('admin.pages.'.$this->nav.'.index', compact('users', 'categories', 'services'));
    }

    public function list_ajax() {
        $queries = Widget_Quote::with('user', 'category', 'brand', 'series', 'item', 'service', 'location')->select('widget_quote.*');

        return DataTables::of($queries)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = view_button( route('admin.queries.show', $row->id) );
                        return $btn;
                    })
                    ->addColumn('client', function ($row) {
                        return $row->user->name ?? '';
                    })
                    ->editColumn('category', function ($row) {
                        return $row->category->name ?? '';
                    })
                    ->editColumn('item', function ($row) {
                        return $row->item->name ?? '';
                    })
                    ->editColumn('service', function ($row) {
                        return $row->service->name ?? '';
                    })
                    ->editColumn('created_at', function ($row) {
                        return date('Y-m-d H:i:s', strtotime($row->created_at));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function show($id) {
        // $query = Widget_Quote::find($id);
        $query = Widget_Quote::with('user', 'category', 'brand', 'series', 'item', 'service', 'location')->find($id);

        return view('admin.pages.'.$this->nav.'.show', $query);
    }

    public function destroy($id) {
        $query = Widget_Quote::findOrFail($id);
        $query->delete();

        return redirect()->route('query.index')->withSuccess('Widget_Quote Delete successfully');
    }
}
