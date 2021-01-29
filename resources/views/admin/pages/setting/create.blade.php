@extends('admin.layouts.app')
@section('title','Create Setting')

@section('content')
{!! Form::open(['method' => 'POST', 'route' => [$nav.'.store'], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('name', 'Name')!!} <span class="text-danger">*</span>
        {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'Name', 'maxlength'=>100, 'required'=>true]) !!}
        {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('title', 'Title')!!}
        {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=>'Title', 'maxlength'=>200]) !!}
        {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('value', 'Value')!!}
        {!! Form::text('value', old('value'), ['class'=>'form-control', 'placeholder'=>'Value', 'maxlength'=>200]) !!}
        {!! $errors->first('value', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('type', 'Type')!!}
        {!! Form::text('type', old('type'), ['class'=>'form-control', 'placeholder'=>'Type']) !!}
        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('option', 'Options')!!}
        {!! Form::text('option', old('option'), ['class'=>'form-control', 'placeholder'=>'Options']) !!}
    </div>
    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route($nav.'.index')) }}
    </div>
{!! Form::close() !!}
@endsection

