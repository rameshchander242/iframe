@extends('user.layouts.app')
@section('title','View Location')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Store</label></div>
        <div class="col-sm-8"> {{$store_name}} </div>
        
        <div class="col-sm-4"><label>City</label></div>
        <div class="col-sm-8"> {{$city}} </div>
        
        <div class="col-sm-4"><label>Address 1</label></div>
        <div class="col-sm-8"> {{$address_1}} </div>
        
        <div class="col-sm-4"><label>Address 2</label></div>
        <div class="col-sm-8"> {{$address_2}} </div>
        
        <div class="col-sm-4"><label>Phone</label></div>
        <div class="col-sm-8"> {{$phone}} </div>
        
        <div class="col-sm-4"><label>Email</label></div>
        <div class="col-sm-8"> {{$email}} </div>
        
        <div class="col-sm-4"><label>Map URL</label></div>
        <div class="col-sm-8"> {{$map_url}} </div>
        
        <div class="col-sm-4"><label>Email</label></div>
        <div class="col-sm-8"> {{$email}} </div>
        
        <div class="col-sm-4"><label>Hours</label></div>
        <div class="col-sm-8"> 
            @if(is_array($hours))
            @foreach ($hours as $hr_key=>$hr_val) 
                @if ( isset($hr_val['status']) && $hr_val['status'] == '1')
                    {{$hr_key.' '. $hr_val['from'] .'-'. $hr_val['to']}}
                @endif
            @endforeach
            @endif
        </div>
        
        <div class="col-sm-4"><label>Description</label></div>
        <div class="col-sm-8"> {{$description}} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('user.location.index')) }}
    </div>
@endsection