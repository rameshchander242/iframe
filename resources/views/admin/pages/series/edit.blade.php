@extends('admin.layouts.app')
@section('title','Edit Series')

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => ['series.update', $id], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('name', 'Series Name')!!} <span class="text-danger">*</span>
        {!! Form::text('name', $name, ['class'=>'form-control', 'placeholder'=>'Name', 'maxlength'=>200, 'required'=>true]) !!}
        {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('image', 'Series Image')!!}
        {!! Form::file('image', ['class' => 'form-control', 'accept'=>'.png,.jpg,.jpeg']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('category_id', 'Category')!!} <span class="text-danger">*</span>
        {!! Form::select('category_id', $categories, $category_id, ['class'=>'form-control', 'placeholder'=>'-- Select Category --', 'required'=>true, 'id'=>'category']) !!}
        {!! $errors->first('category_id', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group" id="brand_group">
        {!!Form::label('brand_id', 'Brand')!!}
        {!! Form::select('brand_id', $brands, $brand_id, ['class'=>'form-control', 'placeholder'=>'-- Select Brand --']) !!}
        {!! $errors->first('brand_id', '<p class="text-danger">:message</p>') !!}
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
        {!!Form::label('description', 'Series Description')!!}
        {!! Form::textarea('description', $description, ['class'=>'form-control', 'placeholder'=>'Description']) !!}
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('position', 'Series Position')!!}
                {!! Form::number('position', $position, ['class'=>'form-control', 'placeholder'=>'0', 'max'=>9999]) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!!Form::label('', 'Series Status')!!}<br>
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
        {{ back_button(route('series.index')) }}
    </div>
{!! Form::close() !!}
@endsection

@push('custom_scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#category').change(function() {
        var _token = $('input[name="_token"]').val();
        var catId = $(this).val();
        $(this).prev('label').append('<i class="ml-2 fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: "{{ route('ajax.category-brands') }}", 
            method: "POST",
            data: {'id': catId, _token:_token},
            success: function(result){
                $("#brand_group").html(result);
                $('.fa.fa-spinner.fa-spin').remove();
            }
        });
    });
});
</script>
@endpush