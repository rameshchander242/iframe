@extends('admin.layouts.app')
@section('title','Create Location')

@section('content')
{!! Form::open(['method' => 'POST', 'route' => ['location.store'],]) !!}
    <div class="form-group">
        {!!Form::label('user_id', 'Client')!!} <span class="text-danger">*</span>
        {!! Form::select('user_id', $users, '', ['class'=>'form-control', 'placeholder'=>'-- Select Client --', 'required'=>true]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('store_name', 'Store Name')!!} <span class="text-danger">*</span>
        {!! Form::text('store_name', old('store_name'), ['class'=>'form-control', 'placeholder'=>'Store Name', 'required'=>true]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('address_1', 'Address')!!} <span class="text-danger">*</span>
        {!! Form::text('address_1', old('address_1'), ['class'=>'form-control', 'placeholder'=>'Address', 'required'=>true]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('address_2', 'Address 2')!!}
        {!! Form::text('address_2', old('address_2'), ['class'=>'form-control', 'placeholder'=>'Address 2']) !!}
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('city', 'City')!!}
                {!! Form::text('city', old('city'), ['class'=>'form-control', 'placeholder'=>'City']) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('phone', 'Phone')!!}
                {!! Form::text('phone', old('phone'), ['class'=>'form-control', 'placeholder'=>'Phone']) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('email', 'Email')!!}
                {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=>'Email']) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('additional_email', 'Additional Email')!!}
                {!! Form::email('additional_email', old('additional_email'), ['class'=>'form-control', 'placeholder'=>'Additional Email']) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {!!Form::label('map_url', 'Map Url')!!}
        {!! Form::url('map_url', old('map_url'), ['class'=>'form-control', 'placeholder'=>'Map Url']) !!}
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('ctm_code', 'CTM Code')!!}
                {!! Form::text('ctm_code', old('ctm_code'), ['class'=>'form-control', 'placeholder'=>'CTM Code']) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('hours', 'Hours')!!}
                {!! Form::text('hours', old('hours'), ['class'=>'form-control', 'placeholder'=>'Hours']) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('price_sheet', 'Price Sheet')!!}
                {!! Form::text('price_sheet', old('price_sheet'), ['class'=>'form-control', 'placeholder'=>'Price Sheet']) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::hidden('status', '0') !!}
                {!!Form::label('', 'Status')!!}<br>
                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                    {!! Form::checkbox('status', '1', true, ['class'=>'custom-control-input', 'id'=>'status']) !!}
                    {!!Form::label('status', 'Publish', ['class'=>'custom-control-label'])!!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        {!!Form::label('description', 'Description')!!}
        {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'placeholder'=>'Description', 'rows'=>'5']) !!}
    </div>
    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('location.index')) }}
    </div>
{!! Form::close() !!}
@endsection