<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Series;
use App\Models\Item;
use App\Models\Service;
use App\Models\Iframe;
use App\Models\Iframe_Info;
use Illuminate\Support\Facades\View;
use Auth;
use DataTables;
use DB;

class IframeInfoController extends Controller
{
    public $nav = 'iframe_info';

    public function __construct() {
        $this->middleware('auth');
        View::share('nav', $this->nav);
    }

    public function index() {
        // echo auth()->id();
        // $iframe_infos = Iframe_Info::with('user')->where('user_id', auth()->user()->id)->get();
        // echo "<pre>"; print_r($iframe_infos); exit;
        // DB::enableQueryLog();
        // $iframe_infos = Iframe_Info::with('iframe', 'location', 'category', 'series', 'brand', 'item')
        // ->where( 'iframe_info.user_id', auth()->id() )->select('iframe_info.*')->get();
        // echo "<pre>"; print_r($iframe_infos);
        // dd(DB::getQueryLog());

        $iframes = Iframe::where('status', '1')->orderBy('id', 'desc')->where('user_id', auth()->id())->get()->pluck('name','id')->toArray();
        $locations = Location::where('status', '1')->where('user_id', auth()->id())->get()->pluck('store_name','store_name')->toArray();
        $categories = Category::where('status', '1')->get()->pluck('name', 'name');
        $brands = Brand::where('status', '1')->get()->pluck('name','name');
        
        return view('user.pages.'.$this->nav.'.index', compact('iframes', 'locations', 'categories', 'brands'));
    }

    public function list_ajax() {
        $iframe_infos = Iframe_Info::with('iframe', 'location', 'category', 'brand', 'series', 'item')
        ->where( 'iframe_info.user_id', auth()->id() )->select('iframe_info.*');

        return DataTables::of($iframe_infos)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('user.iframe_info.edit', $row->id) ) . '&nbsp; ';
                        return $btn;
                    })
                    ->editColumn('iframe', function ($row) {
                        return $row->iframe->id ?? '';
                    })
                    ->editColumn('category', function ($row) {
                        return $row->category->name ?? '--';
                    })
                    ->editColumn('location', function ($row) {
                        return $row->location->store_name;
                    })
                    ->editColumn('brand', function ($row) {
                        return $row->brand->name ?? '--';
                    })
                    ->editColumn('series', function ($row) {
                        return $row->series->name ?? '--';
                    })
                    ->editColumn('item', function ($row) {
                        return $row->item->name ?? '--';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function edit($id) {
        $where = ['id'=>$id, 'user_id'=>Auth::id()];
        $iframe_info = Iframe_Info::with('iframe', 'category', 'brand', 'series', 'item')->where($where)->first();
        if (!$iframe_info) {
            return redirect()->route('user.iframe_info.index');
        }
        // echo "<pre>"; print_r($iframe_info); echo "</pre>";

        return view('user.pages.'.$this->nav.'.edit', compact('iframe_info'));
    }

    public function update(request $request, $id) {
        $iframe_info = Iframe_Info::findOrFail($id);
        $iframe_info->update($request->all());

        return redirect()->route('user.iframe_info.index')->withSuccess('Widget Info Update successfully');
    }

    public function show($id) {
        $iframe_info = Iframe_Info::with('iframe', 'location', 'category', 'brand', 'series', 'item')
        ->where( 'iframe_info.user_id', auth()->id() )->select('iframe_info.*')->find($id);

        return view('user.pages.'.$this->nav.'.show', $iframe_info);
    }
}
