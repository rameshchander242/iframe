@extends('admin.layouts.app')
@section('title','View Error')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Iframe</label></div>
        <div class="col-sm-8"> {{$iframe['name']}} </div>

        <div class="col-sm-4"><label>Error</label></div>
        <div class="col-sm-8"> {{$description}} </div>
        
        <div class="col-sm-4"><label>IP Address</label></div>
        <div class="col-sm-8"> {{$ip_address}} </div>
        
        <div class="col-sm-4"><label>Browser</label></div>
        <div class="col-sm-8"> {{$browser}} </div>
        
        <div class="col-sm-4"><label>Location</label></div>
        <div class="col-sm-8"> {{$location}} </div>
        
        <div class="col-sm-4"><label>Date/Time</label></div>
        <div class="col-sm-8"> {{$date_time}} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('error-log.index')) }}
    </div>
@endsection