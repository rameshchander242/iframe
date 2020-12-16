@extends('admin.layouts.app')
@section('title','Create Client')

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['client.store'], 'files' => true,]) !!}
        <div class="form-group">
            {!!Form::label('name', 'Name')!!} <span class="text-danger">*</span>
            {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'Name', 'maxlength'=>200, 'required'=>true]) !!}
            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('email', 'Email')!!} <span class="text-danger">*</span>
            {!! Form::text('email', old('email'), ['class'=>'form-control', 'placeholder'=>'Email', 'maxlength'=>200, 'required'=>true]) !!}
            {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('password', 'Password')!!} <span class="text-danger">*</span>
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password', 'maxlength'=>30, 'required'=>true]) !!}
            {!! $errors->first('password', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('phone', 'Phone')!!} <span class="text-danger">*</span>
            {!! Form::text('phone', old('phone'), ['class'=>'form-control', 'placeholder'=>'Phone', 'maxlength'=>20, 'required'=>true]) !!}
            {!! $errors->first('phone', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('image', 'Profile Image')!!} <span class="text-danger">*</span>
            {!! Form::file('image', ['class' => 'form-control', 'accept'=>'.png,.jpg,.jpeg']) !!}
            {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('ctm_auth', 'CTM Authentication Code')!!} <span class="text-danger">*</span>
            {!! Form::text('ctm_auth', old('ctm_auth'), ['class'=>'form-control', 'placeholder'=>'CTM Authentication Code', 'maxlength'=>200, 'required'=>true]) !!}
            {!! $errors->first('ctm_auth', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('about', 'About')!!}
            {!! Form::textarea('about', old('about'), ['class'=>'form-control', 'placeholder'=>'About']) !!}
        </div>
        <div class="form-group">
            {!!Form::label('', 'Client Status')!!}<br>
            {!! Form::hidden('status', '0') !!}
            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                {!! Form::checkbox('status', '1', true, ['class'=>'custom-control-input', 'id'=>'status']) !!}
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