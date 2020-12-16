@extends('admin.layouts.app')
@section('title','View Series')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Series Name</label></div>
        <div class="col-sm-8"> {{$name}} </div>

        <div class="col-sm-4"><label>Series Image</label></div>
        <div class="col-sm-8"> {!! Html::image( upload_url($nav).$image, '', array('class' => 'img-thumb')) !!} </div>
        
        <div class="col-sm-4"><label>Series Category</label></div>
        <div class="col-sm-8"> {{$category['name']}} </div>
        
        <div class="col-sm-4"><label>Series Brand</label></div>
        <div class="col-sm-8"> {{$brand['name']}} </div>
        
        <div class="col-sm-4"><label>Series Description</label></div>
        <div class="col-sm-8"> {{$description}} </div>
        
        <div class="col-sm-4"><label>Series Position</label></div>
        <div class="col-sm-8"> {{$position}} </div>
        
        <div class="col-sm-4"><label>Series Status</label></div>
        <div class="col-sm-8"> {!! view_status($status) !!} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('series.index')) }}
    </div>
@endsection

