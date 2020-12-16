<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Error_log;
use Illuminate\Support\Facades\View;
use DataTables;

class ErrorLogController extends Controller
{
    public $nav = 'error-log';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $categories = Error_log::with('iframe')->select('*');

        return DataTables::of($categories)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = view_button( route('error-log.show', $row->id) );
                        return $btn;
                    })
                    ->editColumn('iframe', function ($row) {
                        return $row['iframe']['name'];
                    })
                    ->editColumn('icon', function ($row) {
                        return ($row->icon != null) ? '<i class="fa-lg '.$row->icon.'"></i>' : 'N/A';
                    })
                    ->rawColumns(['action', 'icon', 'status'])
                    ->make(true);
    }
    public function show($id) {
       $error = Error_log::with('iframe')->find($id);

        return view('admin.pages.'.$this->nav.'.show',$error);
    }

    public function destroy($id) {
       $error = Error_log::findOrFail($id);
       $error->delete();

        return redirect()->route('error-log.index')->withSuccess('Service Delete successfully');
    }
}
