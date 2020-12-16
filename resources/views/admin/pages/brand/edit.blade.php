@extends('admin.layouts.app')
@section('title','Edit Brand')

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => ['brand.update', $id], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('name', 'Name')!!} <span class="text-danger">*</span>
        {!! Form::text('name', $name, ['class'=>'form-control', 'placeholder'=>'Name', 'maxlength'=>200, 'required'=>true]) !!}
        {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('image', 'Image')!!} <span class="text-danger">*</span>
        {!! Form::file('image', ['class' => 'form-control', 'accept'=>'.png,.jpg,.jpeg']) !!}
        {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('name', 'Category')!!}
        {!! Form::select('category_id', $categories, $category_id, ['class'=>'form-control', 'placeholder'=>'-- Select Category --', 'required'=>true]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('timeframe', 'Timeframe')!!}
        {!! Form::text('timeframe', $timeframe, ['class'=>'form-control', 'placeholder'=>'Timeframe', 'maxlength'=>200]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('warranty', 'Warranty')!!}
        {!! Form::text('warranty', $warranty, ['class'=>'form-control', 'placeholder'=>'Warranty', 'maxlength'=>200]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('description', 'Description')!!}
        {!! Form::textarea('description', $description, ['class'=>'form-control', 'placeholder'=>'Description']) !!}
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('position', 'Position')!!}
                {!! Form::number('position', $position, ['class'=>'form-control', 'placeholder'=>'0']) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('', 'Status')!!}<br>
                {!! Form::hidden('status','0') !!}
                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                    {!! Form::checkbox('status', '1', ($status == '1'), ['class'=>'custom-control-input', 'id'=>'status']) !!}
                    {!!Form::label('status', 'Publish', ['class'=>'custom-control-label'])!!}
                </div>
                
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Update', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('brand.index')) }}
    </div>
{!! Form::close() !!}
@endsection