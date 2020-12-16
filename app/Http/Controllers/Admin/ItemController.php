<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Series;
use App\Models\Item;
use App\Models\Iframe;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Traits\FileUploadTrait;
use DataTables;
use Validator;

class ItemController extends Controller
{
    use FileUploadTrait;
    public $nav = 'item';

    public function __construct() {
        $this->middleware('auth:admin');
        View::share('nav', $this->nav);
    }
    
    public function index() {
        return view('admin.pages.'.$this->nav.'.index');
    }

    public function list_ajax() {
        $items = Item::with('category');

        return DataTables::of($items)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = edit_button( route('item.edit', $row->id) ) . '&nbsp; ' .
                        view_button( route('item.show', $row->id) ) . '&nbsp; ' .
                        delete_button( route('item.destroy', $row->id) );
                        return $btn;
                    })
                    ->editColumn('category', function ($row) {
                        return isset($row->category) ? $row->category->name : '--';
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

    public function create() {
        $categories = Category::where('status', '1')->get()->pluck('name', 'id');

        return view('admin.pages.'.$this->nav.'.create', compact('categories'));
    }

    public function store(request $request) {
        $rules = [
            'name'  => 'required|max:200|unique:items,name',
            'image' => 'required|image'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $request = $this->saveFiles($request, $this->nav);
        $item = Item::create($request->all());
        
        $this->itemtoIframes($item['id']);
        
        return redirect()->route('item.index')->withSuccess("Item Create successfully");
    }

    public function edit($id) {
        $item = Item::find($id);
        $categories = Category::where('status', '1')->get()->pluck('name', 'id');
        $brands = Brand::where(['status'=>'1', 'category_id'=>$item->category_id])->get()->pluck('name', 'id');

        return view('admin.pages.'.$this->nav.'.edit', $item, compact('categories','brands'));
    }

    public function update(request $request, $id) {
        $rules = [
            'name'  => 'required|max:200|unique:items,name,'.$id.',id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())  {
            return back()->withErrors($validator)->withInput();
        }
        $item = Item::findOrFail($id);
        $request = $this->saveFiles($request, $this->nav);
        $item->update($request->all());

        return redirect()->route('item.index')->withSuccess('Item Update successfully');
    }

    public function show($id) {
        $item = Item::with('category')->find($id);

        return view('admin.pages.'.$this->nav.'.show', $item);
    }

    public function destroy($id) {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('item.index')->withSuccess('Item Delete successfully');
    }

    private function itemtoIframes($id) {
        $item = Item::find($id)->toArray();
        $iframes = Iframe::where('status', '1')->get()->toArray();
        foreach ($iframes as $iframe) {
            if (empty($iframe['item_service']))
                continue;
            $addItem = false;
            if (is_array($iframe['category']) && in_array($item['category_id'], $iframe['category'])) {
                if ( !empty($item['brand_id']) ) {
                    if (is_array($iframe['brand'])) {
                        foreach ( $iframe['brand'] as $brand) {
                            if ( is_array($brand) && in_array($item['brand_id'], $brand)) {
                                if ( !empty($item['series_id']) ) {
                                    if (is_array($iframe['series'])) {
                                        foreach ( $iframe['series'] as $cat_id=>$series_brands) {
                                            if ($cat_id == $item['category_id']) {
                                                foreach ( $series_brands as $brand_id=>$series) {
                                                    if ($brand_id == $item['brand_id']) {
                                                        if ( is_array($series) && in_array($item['series_id'], $series)) {
                                                            $this->addItemToIframe($iframe, $item);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $this->addItemToIframe($iframe, $item);
                                }
                            }
                        }
                    }
                } else {
                    $this->addItemToIframe($iframe, $item);
                }
            }
        }
    }

    private function addItemToIframe($iframeData, $item) {
        $item_id = $item['id'];
        $category_id = $item['category_id'];
        $brand_id = $item['brand_id'] ?? '';
        $series_id = $item['series_id'] ?? '';
        $services = $iframeData['service'][$category_id];
        $locations = $iframeData['location'];
        $items = $iframeData['item'];
        $item_service = json_decode($iframeData['item_service'], true);
        $iframe_data = json_decode($iframeData['iframe_data'], true);
        // $iframe[$category_id][$item_id];
        $itemData = [
            'id'    => $item['id'],
            'name'    => $item['name'],
            'image'    => asset(upload_url('item') . $item['image']),
        ];
        foreach ($services as $service_id) {
            $item_service[$category_id][$item_id][$service_id]['default'] = '';
            $itemData['service'][$service_id]['default'] = '';
            foreach ($locations as $location_id) {
                $itemData['service'][$service_id][$location_id] = '';
                $item_service[$category_id][$item_id][$service_id][$location_id] = '';
            }
        }
        foreach ($iframe_data['categories'] as $key=>$category) {
            if ($category['id'] == $category_id) {
                if (isset($category['brands']) && !empty($category['brands'])) {
                    foreach ($category['brands'] as $b_key=>$brand) {
                        if ($brand['id'] == $brand_id) {
                            if (isset($brand['series']) && !empty($brand['series'])) {
                                foreach ($brand['series'] as $s_key=>$series) {
                                    if ($series['id'] == $series_id) {
                                        $iframe_data['categories'][$key]['brands'][$b_key]['series'][$s_key]['items'][] = $itemData;
                                    }
                                }
                            } else {
                                $iframe_data['categories'][$key]['brands'][$b_key]['items'][] = $itemData;
                            }
                        }
                    }
                } else {
                    $iframe_data['categories'][$key]['item'][] = $itemData;
                }
            }

        }
        $items[$category_id][] = $item_id;
        $iframe = Iframe::findOrFail($iframeData['id']);
        $iframe['item_service'] = json_encode($item_service);
        $iframe['iframe_data'] = json_encode($iframe_data);
        $iframe['item'] = $items;
        $iframe->update();
    }
}
