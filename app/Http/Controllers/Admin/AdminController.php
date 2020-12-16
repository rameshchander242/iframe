<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\View;
use App\Models\Widget_Quote;
use App\Models\Category;
use DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', 'dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['from'] = $from = (isset($_GET['from']) && !empty($_GET['from'])) ? $_GET['from'] : date('Y-m-d', strtotime('-30 days'));
        $data['to'] = $to = (isset($_GET['to']) && !empty($_GET['to'])) ? $_GET['to'] : date('Y-m-d');
        $data['to'] = $to = (strtotime($to) < strtotime($from)) ? $from : $to;
        $data['categories'] = Widget_Quote::with(['category'=>function($query) {
            $query->select('*');
        }])->groupBy('category_id')->select(DB::raw('category_id, count(*) as total'))
        ->orderBy( DB::raw('count(*)'), 'desc' )
        ->take(5)
        ->whereBetween('created_at', array($from, $to))
        ->get()->toArray();
        
        $data['items'] = Widget_Quote::with(['item'=>function($query) {
            $query->select('*');
        }])->groupBy('item_id')->select( DB::raw('item_id, count(*) as total') )
        ->orderBy( DB::raw('count(*)'), 'desc' )
        ->take(5)
        ->whereBetween('created_at', array($from, $to))
        ->get()->toArray();
        
        $data['services'] = Widget_Quote::with(['service'=>function($query) {
            $query->select('*');
        }])->groupBy('service_id')->select( DB::raw('service_id, count(*) as total') )
        ->orderBy( DB::raw('count(*)'), 'desc' )
        ->take(5)
        ->whereBetween('created_at', array($from, $to))
        ->get()->toArray();
        
        return view('admin.pages.dashboard', $data);
    }
}
