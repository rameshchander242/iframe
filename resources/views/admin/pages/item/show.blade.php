@extends('admin.layouts.app')
@section('title','View Item')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Name</label></div>
        <div class="col-sm-8"> {{$name}} </div>

        <div class="col-sm-4"><label>Image</label></div>
        <div class="col-sm-8"> {!! Html::image( upload_url($nav).$image, '', array('class' => 'img-thumb')) !!} </div>
        
        <div class="col-sm-4"><label>Category</label></div>
        <div class="col-sm-8"> {{$category['name']}} </div>
        
        <div class="col-sm-4"><label>Description</label></div>
        <div class="col-sm-8"> {{$description}} </div>
        
        <div class="col-sm-4"><label>Position</label></div>
        <div class="col-sm-8"> {{$position}} </div>
        
        <div class="col-sm-4"><label>Status</label></div>
        <div class="col-sm-8"> {!! view_status($status) !!} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('item.index')) }}
    </div>
@endsection

