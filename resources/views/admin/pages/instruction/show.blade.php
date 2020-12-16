@extends('admin.layouts.app')
@section('title','View Instruction')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Title</label></div>
        <div class="col-sm-8"> {{$title}} </div>
        
        <div class="col-sm-4"><label>Detail</label></div>
        <div class="col-sm-8"> {{$detail}} </div>
        
        <div class="col-sm-4"><label>Position</label></div>
        <div class="col-sm-8"> {{$position}} </div>
        
        <div class="col-sm-4"><label>Status</label></div>
        <div class="col-sm-8"> {!! view_status($status) !!} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('instruction.index')) }}
    </div>
@endsection