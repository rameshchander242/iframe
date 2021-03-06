<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Series;

use Form;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

    }

    public function category_brands(request $request) {
        $brand = '';
        if (isset($request->id)) {
            $id = $request->id;
            $brands = Brand::where('category_id', $id)->where('status', '1')->get()->pluck('name', 'id');
            $brand .= Form::label('brand_id', 'Brand');
            $brand .= Form::select('brand_id', $brands, '', ['class'=>'form-control', 'placeholder'=>'-- Select Brand --']);
        } else if (isset($request->name)) {
            $name = $request->name;
            $brands = Brand::whereHas('category', function ($query) use ($name) {
                return $query->where('name', $name);
            })->where('status', '1')->get()->pluck('name', 'name');
            $brand .= Form::select('brand_id', $brands, '', ['class'=>'form-control', 'placeholder'=>'-- Select Brand --', 'id'=>'brand']);

        }
        return $brand;
    }

    public function brand_series(request $request) {
        $id = $request->id;
        $series = Series::where('brand_id', $id)->where('status', '1')->get()->pluck('name', 'id');
        $brand = Form::label('series_id', 'Series');
        $brand .= Form::select('series_id', $series, '', ['class'=>'form-control', 'placeholder'=>'-- Select Series --']);
        return $brand;
    }
}
