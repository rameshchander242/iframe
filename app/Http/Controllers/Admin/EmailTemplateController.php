<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\View;
use DataTables;
use Validator;

class EmailTemplateController extends Controller
{
    public $nav = 'email-template';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $email_templates = EmailTemplate::where('email_default', '1');

        return DataTables::of($email_templates)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('email-template.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('email-template.show', $row->id) );
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
        $data['categories'] = Category::where(['status'=>'1'])->get()->pluck('name', 'id');
        return view('admin.pages.'.$this->nav.'.create', $data);
    }

    public function store(request $request) {
        $data = $request->all();
        $data['email_default'] = '1';
        $email_template = EmailTemplate::create($data);

        return redirect()->route('email-template.index')->withSuccess("Email Template Create successfully");
    }

    public function edit($id) {
        $email_template = EmailTemplate::with('category', 'brand', 'series')->find($id);
        return view('admin.pages.'.$this->nav.'.edit', $email_template);
    }

    public function update(request $request, $id) {
        $rules = [
            'subject'  => 'required|max:200',
            'body'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }

        $email_template = EmailTemplate::findOrFail($id);
        $email_template->update($request->all());

        return redirect()->route('email-template.index')->withSuccess('Email Template Update successfully');
    }

    public function show($id) {
        $email_template = EmailTemplate::with('category', 'brand', 'series', 'iframe')->find($id);
        return view('admin.pages.'.$this->nav.'.show', $email_template);
    }

    // public function destroy($id) {
    //     $email_template = EmailTemplate::findOrFail($id);
    //     $email_template->delete();
        
    //     return redirect()->route('email-template.index')->withSuccess('Email Template Delete successfully');
    // }
}
