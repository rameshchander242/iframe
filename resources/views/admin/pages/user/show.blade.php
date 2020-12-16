@extends('admin.layouts.app')
@section('title','View Client')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Name</label></div>
        <div class="col-sm-8"> {{$name}} </div>
        
        <div class="col-sm-4"><label>Email</label></div>
        <div class="col-sm-8"> {{$email}} </div>
        
        <div class="col-sm-4"><label>Phone</label></div>
        <div class="col-sm-8"> {{$phone}} </div>

        <div class="col-sm-4"><label>Image</label></div>
        <div class="col-sm-8"> {!! Html::image( upload_url($nav).$image, $image, array('class' => 'img-thumb')) !!} </div>
        
        <div class="col-sm-4"><label>CTM  Code</label></div>
        <div class="col-sm-8"> {{$ctm_auth}} </div>

        <div class="col-sm-4"><label>About</label></div>
        <div class="col-sm-8"> {{$about}} </div>        
        
        <div class="col-sm-4"><label>Status</label></div>
        <div class="col-sm-8"> {!! view_status($status) !!} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('client.index')) }}
    </div>
@endsection

