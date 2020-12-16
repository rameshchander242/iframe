<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iframe;
use App\Models\Widget_Quote;
use App\Models\Widget_Quote_Reply;
use App\Models\EmailTemplate;
use App\Models\Location;
use App\Models\Category;
use App\Models\Service;
use Auth;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use DataTables;

class QueryController extends Controller
{
    use FileUploadTrait;
    public $nav = 'query';

    public function __construct() {
        $this->middleware('auth');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        // $queries = Widget_Quote::select('*')
        // ->with('user', 'category', 'brand', 'series', 'item', 'service', 'location')
        // ->get();
        // echo $queries[0]->email;
        // echo "<pre>"; print_r($queries); exit;
        $categories = Category::get()->pluck('name','name');
        $services = Service::get()->pluck('name','name');
        $locations = Location::where('user_id', Auth::id())->get()->pluck('store_name','store_name');


        return view('user.pages.'.$this->nav.'.index', compact('locations', 'categories', 'services'));
    }

    public function list_ajax() {
        // $user_id = Auth::id();
        $queries = Widget_Quote::with( 'category', 'brand', 'series', 'item', 'service', 'location')->select('widget_quote.*');

        return DataTables::of($queries)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = view_button( route('user.queries.show', $row->id) )
                            . ' &nbsp;' .
                            view_button( route('user.queries.reply', $row->id), 'fa-envelope', 'Reply' );
                        return $btn;
                    })
                    ->addColumn('location', function ($row) {
                        return $row->location->store_name;
                    })
                    ->editColumn('category', function ($row) {
                        return $row->category->name;
                    })
                    ->editColumn('item', function ($row) {
                        return $row->item->name;
                    })
                    ->editColumn('service', function ($row) {
                        return $row->service->name;
                    })
                    ->editColumn('created_at', function ($row) {
                        return date('Y-m-d H:i:s', strtotime($row->created_at));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function show($id) {
        // $query = Widget_Quote::find($id);
        $query = Widget_Quote::with('category', 'item', 'service', 'location')->find($id);

        return view('user.pages.'.$this->nav.'.show', $query);
    }

    public function destroy($id) {
        $query = Widget_Quote::findOrFail($id);
        $query->delete();

        return redirect()->route('query.index')->withSuccess('Widget_Quote Delete successfully');
    }

    public function reply($id) {
        $query = Widget_Quote::with('item', 'service', 'location')->findorfail($id);
        $where = ['email_type'=>'client_email', 'iframe_id'=>$query['iframe_id']];
        $emailTemplate = EmailTemplate::where($where)->first();
        $replies = Widget_Quote_Reply::where('quote_id', $id)->orderby('id', 'desc')->get();

        return view('user.pages.'.$this->nav.'.reply', compact('query', 'emailTemplate', 'replies'));
    }

    public function send($id, Request $request) {
        $query = Widget_Quote::with('item', 'service', 'location')->findorfail($id);
        $formData = $request->all();
        $formData['iframe_id'] = $query['iframe_id'];
        $formData['quote_id'] = $query['id'];

        Widget_Quote_Reply::create($formData);

        $location = Location::find($query['location_id'])->toArray();

        if ( $formData['contact_type'] == 'sms' ) {
            $sms_data = [
                'from'  => $location['ctm_code'],
                'to'  => $formData['email_phone'],
                'msg'  => $formData['message']
            ];
            send_sms_message($sms_data);
        } elseif ( $formData['contact_type'] == 'email' ) {
            $mail_data = [
                'email' => $formData['email_phone'], 
                'name'  => $formData['name'], 
                'subject'   => $formData['subject'], 
                'body'  => $formData['message']
            ];
            sendgrid_mail($mail_data);
        }

        return redirect()->route('user.queries.index')->withSuccess('Message Send successfully');
    }
}
