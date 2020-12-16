<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iframe;
use App\Models\User;
use App\Models\Location;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Series;
use App\Models\Item;
use App\Models\Service;
use App\Models\Iframe_Info;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\View;
use DataTables;

class IframeController extends Controller
{
    public $nav = 'iframe';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }

    /* Index page of Iframes */
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    /* Ajax list of Iframes */
    public function list_ajax() {
        $iframes = Iframe::with('user');

        return DataTables::of($iframes)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('iframe.edit', $row->id) ) . '&nbsp; ' .
                        // view_button( route('iframe.widget', $row->id) ) . '&nbsp; ' .
                        delete_button( route('iframe.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('user', function ($row) {
                        return isset($row->user) ? $row->user->name : '--';
                    })
                    ->editColumn('status', function ($row) {
                        return '<div class="text-center">' . view_status($row->status) . '</div>';
                    })
                    ->editColumn('image', function ($row) {
                        return ($row->image != null) ? '<img height="50px" src="' . asset( upload_url($this->nav) . $row->image ) . '">' : 'N/A';
                    })
                    ->rawColumns(['action', 'image', 'status'])
                    ->make(true);
    }

    /* Rules of Iframe before Submit */
    public function rules() {
        $rules = [
            'name'  => 'required',
            'user_id'  => 'required',
            'location'  => 'required',
            'category'  => 'required',
        ];
        return $rules;
    }

    /* Create Form */
    public function create() {
        $users = User::where('status', '1')->get()->pluck('name', 'id');

        return view('admin.pages.'.$this->nav.'.create', compact( 'users'));
    }

    /* Create new Form in database */
    public function store(request $request) {
        $request->all();
        $validator = $request->validate($this->rules());
        $iframe = Iframe::create($request->all());
        $this->create_with_iframe($iframe);
        
        return redirect()->route('iframe.index')->withSuccess("Iframe Create successfully");
    }

    /* Edit Form */
    public function edit($id) {
        $iframe = Iframe::findorfail($id)->toArray();
        $users = User::where('status', '1')->get()->pluck('name', 'id')->toArray();
        $locations = Location::where( ['user_id'=>$iframe['user_id'], 'status'=>'1'] )->get()->toArray();
        $categories = Category::where('status', '1')->get()->toArray();
        $services = Service::where('status', '1')->get()->toArray();
        $catBrands = Category::with('brands.series.items', 'brands.items', 'items')->whereIn('id', $iframe['category'])->get()->toArray();
        // $series = Category::with('brands')->whereIn('id', $iframe['category'])->get()->toArray();

        return view('admin.pages.'.$this->nav.'.edit', compact('iframe', 'users', 'locations', 'categories', 'catBrands', 'services'));
    }

    /* Update Iframe in database */
    public function update(request $request, $id) {
        $iframe = Iframe::findOrFail($id);
        $validator = $request->validate($this->rules());
        $iframe->update($request->all());

        return redirect()->route('iframe.index')->withSuccess('Iframe Update successfully');
    }

    /* View information of Iframe */
    public function show($id) {
        $iframe = Iframe::with('user')->find($id);

        $iframe['user']['locations'] = Location::wherein('id', $iframe->location)->get();

        $allBrands = [];
        foreach ($iframe->brand as $brand) {
            $allBrands = array_merge( $allBrands, $brand );
        }
        $iframe['categories'] = Category::with(['brands' => function($query) use ($allBrands) {
            $query->wherein('id', $allBrands);
        }])->wherein('id', $iframe->category)->get();
        

        $services = $iframe->service;
        $allservices = [];
        foreach ($iframe['categories'] as $key=>$category) {
            $ser = $iframe->service[$category['id']];
            $iframe['categories'][$key]['services'] = Service::wherein('id', $ser)->get();
        }

        return view('admin.pages.'.$this->nav.'.show', $iframe);
    }

    /* Delete Iframe from database */
    public function destroy($id) {
        $iframe = Iframe::findOrFail($id);
        $iframe->delete();

        return redirect()->route('iframe.index')->withSuccess('Iframe Delete successfully');
    }

    /* Get All locations of User in Ajax */
    public function user_locations(request $request, $return=false) {
        $iframeId = $request->w_id ?? '-1';
        $iframe = Iframe::find($iframeId);
        if($iframe){
            $iframe = $iframe->toArray();
        }
        $locations = Location::where( ['user_id'=>$request->id, 'status'=>'1'] )->get()->toArray();
        if ($return === true) {
            return $locations;
        }
        if ( empty($locations) ) {
            return '<div class="text-danger">Not Found any Location</div>';
        }
        
        return view('admin.pages.'.$this->nav.'.ajax.location', compact('locations','iframe'));
    }

    /* List of All category in Ajax */
    public function categories(request $request) {
        $iframeId = $request->w_id ?? '-1';
        $iframe = Iframe::find($iframeId);
        if($iframe){
            $iframe = $iframe->toArray();
        }
        $categories = Category::where('status', '1')->get()->toArray();
        
        if ( empty($categories) ) {
            return '<div class="text-danger">Not Found any Category</div>';
        }
        
        return view('admin.pages.'.$this->nav.'.ajax.category', compact('categories', 'iframe'));
    }

    /* List of All Brand of Particular Category in Ajax */
    public function category_brands(request $request) {
        $iframeId = $request->w_id ?? '-1';
        $iframe = Iframe::find($iframeId);
        if($iframe){
            $iframe = $iframe->toArray();
        }
        $services = Service::where('status', '1')->get()->toArray();
        $categories = Category::with('brands.series.items', 'brands.items', 'items')->whereIn('id', $request->cats)->get()->toArray();
        
        return view('admin.pages.'.$this->nav.'.ajax.service-brand', compact('services', 'categories', 'iframe'));
    }

    /* Private function to reset iframe data and store in database */
    private function create_with_iframe($iframe) {
        /* Iframe Email Templates */
        $emailData = EmailTemplate::where('email_default', '1')->get()->toArray();
        foreach ($emailData as $e_key=>$e_val) {
            $emailData[$e_key]['email_default'] = '0';
            $emailData[$e_key]['iframe_id']     = $iframe['id'];
            $emailData[$e_key]['user_id']       = $iframe['user_id'];
            unset($emailData[$e_key]['id'], $emailData[$e_key]['created_at'], $emailData[$e_key]['updated_at']);
        }
        $email_template = EmailTemplate::insert($emailData);

        /* Iframe Information */
        $iframe_locations = $iframe['location'];
        $iframe_categories = $iframe['category'];
        $iframe_brands = $iframe['brand'];
        $iframe_series = $iframe['series'];
        $iframe_items = $iframe['item'];

        $categories = Category::whereIn('id', $iframe_categories)->get()->toArray();
        $data = [];
        foreach ($iframe_locations as $location_id) {
            foreach ($categories as $category) {
                $data[] = [
                    'user_id'       => $iframe['user_id'],
                    'iframe_id'     => $iframe['id'],
                    'category_id'   => $category['id'],
                    'brand_id'      => '0',
                    'series_id'     => '0',
                    'item_id'       => '0',
                    'location_id'   => $location_id,
                    'timeframe'     => $category['timeframe'],
                    'warranty'      => $category['warranty'],
                    'description'   => $category['description'],
                    'status'        => '1',
                ];

                if ( array_key_exists($category['id'], $iframe_brands??[]) ) {
                    $brands = Brand::whereIn( 'id', $iframe_brands[$category['id']] )->get()->toArray();
                    foreach ($brands as $brand) {
                        $data[] = [
                            'user_id'       => $iframe['user_id'],
                            'iframe_id'     => $iframe['id'],
                            'category_id'   => $category['id'],
                            'brand_id'      => $brand['id'],
                            'series_id'     => '0',
                            'item_id'       => '0',
                            'location_id'   => $location_id,
                            'timeframe'     => empty($brand['timeframe']) ? $category['timeframe'] : $brand['timeframe'],
                            'warranty'      => empty($brand['warranty']) ? $category['warranty'] : $brand['warranty'],
                            'description'   => empty($brand['description']) ? $category['description'] : $brand['description'],
                            'status'        => '0',
                        ];

                        if ( array_key_exists($brand['id'], $iframe_series??[]) ) {
                            $serieses = Series::whereIn( 'id', $iframe_series[$category['id']][$brand['id']]??[] )->get()->toArray();
                            foreach ($serieses as $series) {
                                $data[] = [
                                    'user_id'       => $iframe['user_id'],
                                    'iframe_id'     => $iframe['id'],
                                    'category_id'   => $category['id'],
                                    'brand_id'      => $brand['id'],
                                    'series_id'     => $series['id'],
                                    'item_id'       => '0',
                                    'location_id'   => $location_id,
                                    'timeframe'     => $series['timeframe'] ?? '',
                                    'warranty'      => $series['warranty'] ?? '',
                                    'description'   => $series['description'] ?? '',
                                    'status'        => '0',
                                ];

                                if ( array_key_exists($series['id'], $iframe_items??[]) ) {
                                    $items = Item::whereIn('id', $iframe_items[$category['id']])->get()->toArray();
                                    foreach ($items as $item) {
                                        $data[] = [
                                            'user_id'       => $iframe['user_id'],
                                            'iframe_id'     => $iframe['id'],
                                            'category_id'   => $category['id'],
                                            'brand_id'      => $brand['id'],
                                            'series_id'     => $series['id'],
                                            'item_id'       => $item['id'],
                                            'location_id'   => $location_id,
                                            'timeframe'     => $item['timeframe'] ?? '',
                                            'warranty'      => $item['warranty'] ?? '',
                                            'description'   => $item['description'] ?? '',
                                            'status'        => '0',
                                        ];
                                    }
                                }
                            }
                        } else {
                            $items = Item::whereIn('id', $iframe_items[$category['id']])->get()->toArray();
                            foreach ($items as $item) {
                                $data[] = [
                                    'user_id'       => $iframe['user_id'],
                                    'iframe_id'     => $iframe['id'],
                                    'category_id'   => $category['id'],
                                    'brand_id'      => $brand['id'],
                                    'series_id'     => '0',
                                    'item_id'       => $item['id'],
                                    'location_id'   => $location_id,
                                    'timeframe'     => $item['timeframe'] ?? '',
                                    'warranty'      => $item['warranty'] ?? '',
                                    'description'   => $item['description'] ?? '',
                                    'status'        => '0',
                                ];
                            }
                        }
                    }
                } else {
                    $items = Item::whereIn('id', $iframe_items[$category['id']] ?? [])->get()->toArray();
                    foreach ($items as $item) {
                        $data[] = [
                            'user_id'       => $iframe['user_id'],
                            'iframe_id'     => $iframe['id'],
                            'category_id'   => $category['id'],
                            'brand_id'      => '0',
                            'series_id'     => '0',
                            'item_id'       => $item['id'],
                            'location_id'   => $location_id,
                            'timeframe'     => $item['timeframe'] ?? '',
                            'warranty'      => $item['warranty'] ?? '',
                            'description'   => $item['description'] ?? '',
                            'status'        => '0',
                        ];
                    }
                }
            }
        }
        
        $iframe_info = new Iframe_Info();
        $iframe_info->insert($data);

        return $iframe_info;
    }
}
