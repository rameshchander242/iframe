@extends('admin.layouts.app')
@section('title','Edit Service')

@push('custom_styles')
<link rel="stylesheet" href="{{ asset('css/fontawesome-iconpicker.min.css') }}">
@endpush

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => ['service.update', $id], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('name', 'Service Name')!!} <span class="text-danger">*</span>
        {!! Form::text('name', $name, ['class'=>'form-control', 'placeholder'=>'Name', 'required'=>true]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('icon', 'Service Icon')!!} <span class="text-danger">*</span>
        <div class="input-group iconpicker-container">
            <input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input" name="icon" value="{{$icon}}" required type="text">
            <div class="input-group-append">
                <span class="input-group-text input-group-addon"><i class="fas fa-archive"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        {!!Form::label('description', 'Service Description')!!}
        {!! Form::textarea('description', $description, ['class'=>'form-control', 'placeholder'=>'Description']) !!}
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('position', 'Service Position')!!}
                {!! Form::number('position', $position, ['class'=>'form-control', 'placeholder'=>'0']) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('', 'Service Status')!!}<br>
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
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('service.index')) }}
    </div>
{!! Form::close() !!}
@endsection

@push('custom_scripts')
<script src="{{ asset('js/fontawesome-iconpicker.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.icp-auto').iconpicker();
    });
</script>
@endpush