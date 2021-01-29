<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iframe;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Item;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\View;
use Auth;
use DataTables;

class EmailTemplateController extends Controller
{
    public $nav = 'email-template';

    public function __construct() {
        $this->middleware('auth');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        $iframes = Iframe::where('status', '1')->orderBy('id', 'asc')->where('user_id', auth()->id())->get()->pluck('name','id');
        return view('user.pages.'.$this->nav.'.index', compact('iframes'));
    }

    public function list_ajax() {
        $email_templates = EmailTemplate::with('category', 'brand', 'series')->where( ['email_default'=>'0', 'user_id'=>Auth::id()]);
        

        return DataTables::of($email_templates)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('user.email-template.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('user.email-template.show', $row->id) );
                        return $btn;
                    })
                    ->addColumn('group', function ($row) {
                        $group = isset($row->category->name) ? '<span class="text-nowrap">Category: '.$row->category->name.'</span><br>' : '';
                        $group .= isset($row->brand->name) ? '<span class="text-nowrap">Brand: '.$row->brand->name.'</span><br>' : '';
                        $group .= isset($row->series->name) ? '<span class="text-nowrap">Series: '.$row->series->name.'</span><br>' : '';
                        return !empty($group) ? $group : '--';
                    })
                    ->editColumn('email_type', function ($row) {
                        return config('widget.'.$row->email_type);
                    })
                    ->editColumn('status', function ($row) {
                        return '<div class="text-center">' . view_status($row->status) . '</div>';
                    })
                    ->rawColumns(['action', 'status', 'group'])
                    ->make(true);
    }

    public function create() {
        $data['email_types'] = config('widget.email_types');
        $data['iframes'] = Iframe::where(['status'=>'1', 'user_id'=>Auth::id()])->get()->pluck('name', 'id');
        $data['categories'] = Category::where(['status'=>'1'])->get()->pluck('name', 'id');
        return view('user.pages.'.$this->nav.'.create', $data);
    }

    public function store(request $request) {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $email_template = EmailTemplate::create($data);

        return redirect()->route('user.email-template.index')->withSuccess("Email Template Create successfully");
    }

    public function edit($id) {
        $email_template = EmailTemplate::with('category', 'brand', 'series')->where(['email_default'=>'0', 'user_id'=>Auth::id()])->find($id);
        return view('user.pages.'.$this->nav.'.edit', $email_template);
    }

    public function update(request $request, $id) {
        $email_template = EmailTemplate::where(['email_default'=>'0', 'user_id'=>Auth::id()])->findOrFail($id);
        $email_template->update($request->all());

        return redirect()->route('user.email-template.index')->withSuccess('Email Template Update successfully');
    }

    public function show($id) {
        $email_template = EmailTemplate::with('category', 'brand', 'series', 'iframe')->find($id);
        return view('user.pages.'.$this->nav.'.show', $email_template);
    }

    // public function destroy($id) {
    //     $email_template = EmailTemplate::findOrFail($id);
    //     $email_template->delete();
        
    //     return redirect()->route('user.email-template.index')->withSuccess('Email Template Delete successfully');
    // }
}
