<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iframe;
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
        $email_templates = EmailTemplate::where( ['email_default'=>'0', 'user_id'=>Auth::id()]);

        return DataTables::of($email_templates)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('user.email-template.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('user.email-template.show', $row->id) );
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

    // public function create() {
    //     $iframes = Iframe::where(['status'=>'1'])->get()->pluck('name', 'id');
    //     return view('user.pages.'.$this->nav.'.create', compact( 'iframes'));
    // }

    // public function store(request $request) {
    //     $request->all();
    //     $email_template = EmailTemplate::create($request->all());

    //     return redirect()->route('user.email-template.index')->withSuccess("Email Template Create successfully");
    // }

    public function edit($id) {
        $email_template = EmailTemplate::where(['email_default'=>'0', 'user_id'=>Auth::id()])->find($id);
        return view('user.pages.'.$this->nav.'.edit', $email_template);
    }

    public function update(request $request, $id) {
        $email_template = EmailTemplate::where(['email_default'=>'0', 'user_id'=>Auth::id()])->findOrFail($id);
        $email_template->update($request->all());

        return redirect()->route('user.email-template.index')->withSuccess('Email Template Update successfully');
    }

    public function show($id) {
        $email_template = EmailTemplate::with('iframe')->find($id);
        return view('user.pages.'.$this->nav.'.show', $email_template);
    }

    // public function destroy($id) {
    //     $email_template = EmailTemplate::findOrFail($id);
    //     $email_template->delete();
        
    //     return redirect()->route('user.email-template.index')->withSuccess('Email Template Delete successfully');
    // }
}
