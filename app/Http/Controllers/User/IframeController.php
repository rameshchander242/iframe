<?php
namespace App\Http\Controllers\User;

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
use Illuminate\Support\Facades\View;
use Auth;
use DataTables;
use DB;

class IframeController extends Controller
{
    public $nav = 'iframe';

    public function __construct() {
        $this->middleware('auth');
        View::share('nav', $this->nav);
    }

    public function index() {
        
        $where = ['user_id'=>Auth::user()->id];
        $iframes = Iframe::with('user')->where($where)->get();
        foreach ($iframes as $key_iframe=>$iframe) {
            $ids_ordered = implode(',', $iframe->location);
            $iframe['user']['locations'] = Location::wherein('id', $iframe->location)->orderByRaw("FIELD(id, $ids_ordered)")->get();

            $allBrands = ['-1'];
            if (isset($iframe->brand) && is_array($iframe->brand)) {
                foreach ($iframe->brand as $brand) {
                    $allBrands = array_merge( $allBrands, $brand );
                }
            }
            // DB::enableQueryLog();
            $ids_ordered = implode(',', $iframe->category);
            $iframe['categories'] = Category::with(['brands' => function($query) use ($allBrands) {
                $ids_ordered = implode(',', $allBrands);
                $query->wherein('id', $allBrands)->orderByRaw("FIELD(id, ".($ids_ordered ?? '-1') .")");
            }])->with(['items' => function($query) use ($allBrands) {
                $query->whereNull('brand_id');
            }])->wherein('id', $iframe->category)->orderByRaw("FIELD(id, $ids_ordered)")->get();
            //  dd(DB::getQueryLog());
            

            $services = $iframe->service;
            $allservices = ['-1'];
            foreach ($iframe['categories'] as $key=>$category) {
                $ser = $iframe->service[$category['id']] ?? [];
                $ids_ordered = implode(',', $ser);
                $ids_ordered = empty($ids_ordered) ? '-1' : $ids_ordered;
                $iframe['categories'][$key]['services'] = Service::wherein('id', $ser)->orderByRaw("FIELD(id, $ids_ordered)")->get();
            }
            $iframes[$key_iframe] = $iframe;
        }

        // echo "<pre>"; print_r($iframes); exit;

        return view('user.pages.'.$this->nav.'.index_edit', compact('iframes'));
        return view('user.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $iframes = Iframe::with('user')->where('user_id', auth()->user()->id);

        return DataTables::of($iframes)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('user.iframe.edit', $row->id) ) . '&nbsp; ' . 
                        view_button( route('iframe.widget', $row->id) ) . '&nbsp; ' .
                        modal_button( $row->id );
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

    public function edit($id) {
        $where = ['id'=>$id, 'user_id'=>Auth::user()->id];
        $iframe = Iframe::with('user')->where($where)->first();
        if (!$iframe) {
            return redirect()->route('user.iframe.index');
        }
        $ids_ordered = implode(',', $iframe->location);
        $iframe['user']['locations'] = Location::wherein('id', $iframe->location)->orderByRaw("FIELD(id, $ids_ordered)")->get();

        $allBrands = [];
        foreach ($iframe->brand as $brand) {
            $allBrands = array_merge( $allBrands, $brand );
        }
        // DB::enableQueryLog();
        $ids_ordered = implode(',', $iframe->category);
        $iframe['categories'] = Category::with(['brands' => function($query) use ($allBrands) {
            $ids_ordered = implode(',', $allBrands);
            $query->wherein('id', $allBrands)->orderByRaw("FIELD(id, $ids_ordered)");
        }])->with(['items' => function($query) use ($allBrands) {
            $query->whereNull('brand_id');
        }])->wherein('id', $iframe->category)->orderByRaw("FIELD(id, $ids_ordered)")->get();
        //  dd(DB::getQueryLog());
        

        $services = $iframe->service;
        $allservices = [];
        foreach ($iframe['categories'] as $key=>$category) {
            $ser = $iframe->service[$category['id']] ?? [];
            $ids_ordered = implode(',', $ser);
            $iframe['categories'][$key]['services'] = Service::wherein('id', $ser)->orderByRaw("FIELD(id, $ids_ordered)")->get();
        }

        return view('user.pages.'.$this->nav.'.edit', compact('iframe'));
    }

    public function update(request $request, $id) {
        $iframe = Iframe::findOrFail($id);
        $iframe->item_service = $request->item_service;
        $iframe->success_page = $request->success_page;

        /* Iframe Data */
        $iframe->iframe_data = json_encode( $this->iframe_data($iframe) );
        $iframe->update();
        
        return redirect()->route('user.iframe.index')->withSuccess('Iframe Update successfully');
    }

    private function iframe_data($iframe) {
        // echo "<pre>"; print_r($iframe); exit;
        $loc_cols = ['id', 'user_id', 'store_name', 'address_1', 'address_2', 'city', 'phone', 'email', 'additional_email', 'ctm_code', 'map_url', 'description', 'hours', 'price_sheet'];
        $category_cols = ['id', 'name', 'image'];
        $brand_cols = ['id', 'name', 'image'];
        $series_cols = ['id', 'name', 'image'];
        $item_cols = ['id', 'name', 'image'];
        $service_cols = ['id', 'name', 'icon'];

        $ids_ordered = implode(',', $iframe->location);
        $locations = Location::wherein('id', $iframe->location)->orderByRaw("FIELD(id, $ids_ordered)")->where('status', '1')->get($loc_cols)->toArray();

        $ids_ordered = implode(',', $iframe->category);
        $categories = Category::wherein('id', $iframe->category)->orderByRaw("FIELD(id, $ids_ordered)")->where('status', '1')->orderBy('position', 'asc')->get($category_cols)->toArray();
        foreach ($categories as $key=>$category) {
            if ( isset($iframe->item_service[$category['id']]) ) {
                $item_service = $iframe->item_service[$category['id']];
            } else {
                $item_service = [];
            }
            $services = [];
            if ( is_array($iframe->service) and !empty($iframe->service) ) {
                if ( array_key_exists($category['id'], $iframe->service) ) {
                    $ids = $iframe->service[$category['id']];
                    $ids_ordered = implode(',', $ids);
                    $services = Service::wherein( 'id', $ids )->orderByRaw("FIELD(id, $ids_ordered)")->where(['status'=>'1'])->orderBy('position', 'asc')->get($service_cols)->toArray();
                }
            }
            $categories[$key]['services'] = $services;

            $brands = [];
            if ( is_array($iframe->brand) and !empty($iframe->brand) ) {
                if ( array_key_exists($category['id'], $iframe->brand) ) {
                    $ids = $iframe->brand[$category['id']];
                    $ids_ordered = implode(',', $ids);
                    $brands = Brand::wherein( 'id', $ids )->orderByRaw("FIELD(id, $ids_ordered)")->where('status', '1')->orderBy('position', 'asc')->get($brand_cols)->toArray();

                    foreach ($brands as $b_key=>$brand) {
                        $series = Series::where(['brand_id'=>$brand['id'], 'status'=>'1'])->orderBy('position', 'asc')->get($series_cols);
                        $series = json_decode( json_encode($series), true);
                        if ( is_array($series) and !empty($series) ) {
                            foreach ($series as $s_key=>$ser) {
                                $items = Item::where(['series_id'=>$ser['id'], 'status'=>'1'])->orderBy('position', 'asc')->get($item_cols)->toArray();
                                $items = $this->itemService($items, $item_service);
                                $series[$s_key]['items'] = $items;
                                $series[$s_key]['image'] = asset( upload_url('series') . $ser['image'] );
                            }
                        } else {
                            $items = Item::where(['brand_id'=>$brand['id'], 'status'=>'1'])->orderBy('position', 'asc')->get($item_cols)->toArray();
                            $items = $this->itemService($items, $item_service);
                            $brands[$b_key]['items'] = $items;
                        }
                        $brands[$b_key]['series'] = $series;
                        $brands[$b_key]['image'] = asset( upload_url('brand') . $brand['image'] );
                    }
                } else {
                    $items = Item::where(['category_id'=>$category['id'], 'status'=>'1'])->orderBy('position', 'asc')->get($item_cols)->toArray();
                    $items = $this->itemService($items, $item_service);
                    $categories[$key]['items'] = $items;
                }
            }
            $categories[$key]['brands'] = $brands;
            $categories[$key]['image'] = asset( upload_url('category') . $category['image'] );
        }
        
        $iframeData = [
            'id'  => $iframe->id,
            'name'  => $iframe->name,
            'user_id'  => $iframe->user_id,
            'locations'  => $locations,
            'categories'  => $categories,
        ];
        return $iframeData;
    }

    private function itemService($items, $item_service) {
        if (!empty($items)) {
            $returnItems = [];
            foreach ($item_service as $key=>$is) {
                foreach ($items as $item) {
                    if ($item['id'] == $key) {
                        $item['service'] = $item_service[$item['id']];
                        $item['image'] = asset( upload_url('item') . $item['image'] );
                        $returnItems[] = $item;
                    }
                }
            }
            return $returnItems;
        }
        return $items;
    }

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

        return view('user.pages.'.$this->nav.'.show', $iframe);
    }
}
