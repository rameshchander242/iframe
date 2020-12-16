@extends('admin.layouts.app')
@section('title','Edit Client')

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => ['client.update', $id], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('name', 'Name')!!} <span class="text-danger">*</span>
        {!! Form::text('name', $name, ['class'=>'form-control', 'placeholder'=>'Name', 'maxlength'=>200, 'required'=>true]) !!}
        {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('email', 'Email')!!} <span class="text-danger">*</span>
        <div class="form-control bg-light">{{$email}}</div>
    </div>
    <div class="form-group">
        {!!Form::label('password', 'Password')!!}
        {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password', 'maxlength'=>30]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('phone', 'Phone')!!} <span class="text-danger">*</span>
        {!! Form::text('phone', $phone, ['class'=>'form-control', 'placeholder'=>'Phone', 'maxlength'=>20, 'required'=>true]) !!}
        {!! $errors->first('phone', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('image', 'Profile Image')!!}
        {!! Form::file('image', ['class' => 'form-control', 'accept'=>'.png,.jpg,.jpeg']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('ctm_auth', 'CTM Authentication Code')!!} <span class="text-danger">*</span>
        {!! Form::text('ctm_auth', $ctm_auth, ['class'=>'form-control', 'placeholder'=>'CTM Authentication Code', 'maxlength'=>200, 'required'=>true]) !!}
        {!! $errors->first('ctm_auth', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('about', 'About')!!}
        {!! Form::textarea('about',$about, ['class'=>'form-control', 'placeholder'=>'About']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('', 'Client Status')!!}<br>
        {!! Form::hidden('status', '0') !!}
        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            {!! Form::checkbox('status', '1', $status==1, ['class'=>'custom-control-input', 'id'=>'status']) !!}
            {!!Form::label('status', 'Publish', ['class'=>'custom-control-label'])!!}
        </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('client.index')) }}
    </div>
{!! Form::close() !!}
@endsection