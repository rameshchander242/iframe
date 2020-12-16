@extends('admin.layouts.app')
@section('title','View Query')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Name</label></div>
        <div class="col-sm-8"> {{$fullname}} </div>
        
        <div class="col-sm-4"><label>Email</label></div>
        <div class="col-sm-8"> {{$email}} </div>
        
        <div class="col-sm-4"><label>Phone</label></div>
        <div class="col-sm-8"> {{$phone}} </div>
        
        <div class="col-sm-4"><label>Category</label></div>
        <div class="col-sm-8"> {{$category['name']}} </div>

        @if (isset($brand['name']))
        <div class="col-sm-4"><label>Brands</label></div>
        <div class="col-sm-8"> {{$brand['name']}} </div>
        @endif
        
        @if (isset($series['name']))
        <div class="col-sm-4"><label>Series</label></div>
        <div class="col-sm-8"> {{$series['name']}} </div>
        @endif
        
        <div class="col-sm-4"><label>Item</label></div>
        <div class="col-sm-8"> {{$item['name']}} </div>
        
        <div class="col-sm-4"><label>Service</label></div>
        <div class="col-sm-8"> {{$service['name']}} </div>
        
        <div class="col-sm-4"><label>Location</label></div>
        <div class="col-sm-8"> {{$location['store_name']}} </div>
        
        <div class="col-sm-4"><label>Message</label></div>
        <div class="col-sm-8"> {{$message}} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('admin.queries.index')) }}
    </div>
@endsection