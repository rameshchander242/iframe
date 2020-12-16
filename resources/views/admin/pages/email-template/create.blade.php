@extends('admin.layouts.app')
@section('title','Create Email Template')

@section('content')
{!! Form::open(['method' => 'POST', 'route' => ['email-template.store'],]) !!}
    <div class="form-group">
        {!!Form::label('iframe_id', 'Iframe')!!}
        {!! Form::select('iframe_id', $iframes, '', ['class'=>'form-control', 'placeholder'=>'-- Select Iframe --']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('subject', 'Subject')!!} <span class="text-danger">*</span>
        {!! Form::text('subject', old('subject'), ['class'=>'form-control', 'placeholder'=>'Subject Name', 'required'=>true]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('body', 'Body')!!}
        {!! Form::textarea('body', old('body'), ['class'=>'form-control', 'placeholder'=>'Email Body', 'rows'=>'5']) !!}
    </div><div class="form-group">
        {!! Form::hidden('status', '0') !!}
        {!!Form::label('', 'Status')!!}<br>
        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            {!! Form::checkbox('status', '1', true, ['class'=>'custom-control-input', 'id'=>'status']) !!}
            {!!Form::label('status', 'Publish', ['class'=>'custom-control-label'])!!}
        </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('email-template.index')) }}
    </div>
{!! Form::close() !!}
@endsection