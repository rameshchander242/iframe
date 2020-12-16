@extends('admin.layouts.app')
@section('title','View Brand')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Name</label></div>
        <div class="col-sm-8"> {{$name}} </div>

        <div class="col-sm-4"><label>Client</label></div>
        <div class="col-sm-8"> {{ $user['name'] }} </div>

        <div class="col-sm-4"><label>Locations</label></div>
        <div class="col-sm-8">
            <ul>
            @foreach ($user['locations'] as $location)
                <li>{{ $location['store_name'] }}</li>
            @endforeach
            </ul>
        </div>
        
        <div class="col-sm-4"><label>Description</label></div>
        <div class="col-sm-8"> {{$description}} </div>
        
        <div class="col-sm-4"><label>Status</label></div>
        <div class="col-sm-8"> {!! view_status($status) !!} </div>
        
        <div class="col-sm-4"><label>Categories</label></div>
        <div class="col-sm-8"> 
            <ul>
            @foreach ($categories as $category)
                <li>{{ $category['name'] }}</li>
            @endforeach
            </ul>
        </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('brand.index')) }}
    </div>
@endsection

