<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instruction;
use Illuminate\Support\Facades\View;
use DataTables;
use Validator;

class InstructionController extends Controller
{
    public $nav = 'instruction';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $instructions = Instruction::select('*');

        return DataTables::of($instructions)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('instruction.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('instruction.show', $row->id) ) . '&nbsp; ' .
                        delete_button( route('instruction.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('status', function ($row) {
                        return '<div class="text-center">' . view_status($row->status) . '</div>';
                    })
                    ->editColumn('detail', function ($row) {
                        return strlen($row->detail) > 140  ? substr($row->detail, 0, 140).'...' : $row->detail;
                    })
                    ->rawColumns(['action', 'image', 'status'])
                    ->make(true);
    }

    public function create() {
        return view('admin.pages.'.$this->nav.'.create');
    }

    public function store(request $request) {
        $rules = [
            'title'  => 'required|max:200|unique:instructions',
            'detail'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $instruction = Instruction::create($request->all());
        
        return redirect()->route('instruction.index')->withSuccess("Instruction Create successfully");
    }

    public function edit($id) {
        $instruction = Instruction::find($id);

        return view('admin.pages.'.$this->nav.'.edit', $instruction);
    }

    public function update(request $request, $id) {
        $rules = [
            'title'  => 'required|max:200|unique:instructions,title,'.$id.',id',
            'detail'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $instruction = Instruction::findOrFail($id);
        $instruction->update($request->all());

        return redirect()->route('instruction.index')->withSuccess('Instruction Update successfully');
    }

    public function show($id) {
        $instruction = Instruction::find($id);

        return view('admin.pages.'.$this->nav.'.show', $instruction);
    }

    public function destroy($id) {
        $instruction = Instruction::findOrFail($id);
        $instruction->delete();

        return redirect()->route('instruction.index')->withSuccess('Instruction Delete successfully');
    }
}
