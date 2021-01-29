@extends('admin.layouts.app')
@section('title','Edit '.$title)

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => [$nav.'.update', $id], 'files' => true,]) !!}
    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!!Form::label('value', $title)!!} <span class="text-danger">*</span>
        {!! Form::text('value', $value, ['class'=>'form-control', 'placeholder'=>'Value', 'maxlength'=>200, 'required'=>true]) !!}
        {!! $errors->first('value', '<p class="text-danger">:message</p>') !!}
    </div>
    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Update', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route($nav.'.index')) }}
    </div>
{!! Form::close() !!}
@endsection

