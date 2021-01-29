@extends('admin.layouts.app')
@section('title','View '.$title)

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Name</label></div>
        <div class="col-sm-8"> {{$name}} </div>
        
        <div class="col-sm-4"><label>Value</label></div>
        <div class="col-sm-8"> {{$value}} </div>
        
        <div class="col-sm-4"><label>Type</label></div>
        <div class="col-sm-8"> {{$type}} </div>
        
        <div class="col-sm-4"><label>Option</label></div>
        <div class="col-sm-8"> {{$option}} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route($nav.'.index')) }}
    </div>
@endsection