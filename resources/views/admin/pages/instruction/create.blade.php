@extends('admin.layouts.app')
@section('title','Create Instruction')

@section('content')
{!! Form::open(['method' => 'POST', 'route' => ['instruction.store'], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('title', 'Title')!!} <span class="text-danger">*</span>
        {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=>'Title', 'maxlength'=>200, 'required'=>true]) !!}
        {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('detail', 'Detail')!!} <span class="text-danger">*</span>
        {!! Form::textarea('detail', old('detail'), ['class'=>'form-control', 'placeholder'=>'Detail', 'required'=>true]) !!}
        {!! $errors->first('detail', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('position', 'Position')!!}
                {!! Form::number('position', old('position'), ['class'=>'form-control', 'placeholder'=>'0']) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('', 'Status')!!}<br>
                {!! Form::hidden('status','0') !!}
                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                    {!! Form::checkbox('status', '1', true, ['class'=>'custom-control-input', 'id'=>'status']) !!}
                    {!!Form::label('status', 'Publish', ['class'=>'custom-control-label'])!!}
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('instruction.index')) }}
    </div>
{!! Form::close() !!}
@endsection

