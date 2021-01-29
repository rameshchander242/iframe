@extends('admin.layouts.app')
@section('title','View Message Template')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4"><label>Message Type</label></div>
        <div class="col-sm-8"> {{ config('widget.'.$email_type) }} </div>

        <div class="col-sm-4"><label>Group</label></div>
        <div class="col-sm-8"> 
            <?php
            $group = isset($category['name']) ? 'Category: '.$category['name'].', ' : '';
            $group .= isset($brand['name']) ? 'Brand: '.$brand['name'].', ' : '';
            $group .= isset($series['name']) ? 'Series: '.$series['name'].'' : '';
            ?>
            {!! $group !!} 
        </div>
        
        <div class="col-sm-4"><label>Subject</label></div>
        <div class="col-sm-8"> {{$subject}} </div>
        
        <div class="col-sm-4"><label>Email Template</label></div>
        <div class="col-sm-8"> {!! $body !!} </div>
        
        <div class="col-sm-4"><label>SMS Template</label></div>
        <div class="col-sm-8"> {!! $sms_message !!} </div>
                
        <div class="col-sm-4"><label>Status</label></div>
        <div class="col-sm-8"> {!! view_status($status) !!} </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('email-template.index')) }}
    </div>
@endsection