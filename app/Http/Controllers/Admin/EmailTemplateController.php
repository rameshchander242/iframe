<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iframe;
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
                    ->editColumn('email_type', function ($row) {
                        return title($row->email_type);
                    })
                    ->editColumn('status', function ($row) {
                        return '<div class="text-center">' . view_status($row->status) . '</div>';
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
    }

    public function edit($id) {
        $email_template = EmailTemplate::find($id);
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
        $email_template = EmailTemplate::with('iframe')->find($id);
        return view('admin.pages.'.$this->nav.'.show', $email_template);
    }

    // public function destroy($id) {
    //     $email_template = EmailTemplate::findOrFail($id);
    //     $email_template->delete();
        
    //     return redirect()->route('email-template.index')->withSuccess('Email Template Delete successfully');
    // }
}
