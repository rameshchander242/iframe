@extends('admin.layouts.app')
@section('title','Edit Location')

{{--@push('custom_styles')--}}
{{--@endpush--}}

@section('content')
    {!! Form::open(['method' => 'PUT', 'route' => ['location.update', $id], 'files' => true, 'id'=>'locationForm']) !!}
        <div class="form-group">
            {!!Form::label('user_id', 'Client')!!} <span class="text-danger">*</span>
            {!! Form::select('user_id', $users, $user_id, ['class'=>'form-control', 'placeholder'=>'-- Select Client --', 'required'=>true]) !!}
            {!! $errors->first('user_id', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('store_name', 'Store Name')!!} <span class="text-danger">*</span>
            {!! Form::text('store_name', $store_name, ['class'=>'form-control', 'placeholder'=>'Store Name', 'maxlength'=>200, 'required'=>true]) !!}
            {!! $errors->first('store_name', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('address_1', 'Address')!!} <span class="text-danger">*</span>
            {!! Form::text('address_1',$address_1, ['class'=>'form-control', 'placeholder'=>'Address', 'maxlength'=>200, 'required'=>true]) !!}
            {!! $errors->first('address_1', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('address_2', 'Address 2')!!}
            {!! Form::text('address_2', $address_2, ['class'=>'form-control', 'placeholder'=>'Address 2', 'maxlength'=>200]) !!}
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!!Form::label('city', 'City')!!} <span class="text-danger">*</span>
                    {!! Form::text('city', $city, ['class'=>'form-control', 'placeholder'=>'City', 'maxlength'=>100, 'required'=>true]) !!}
                    {!! $errors->first('city', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!!Form::label('phone', 'Phone')!!} <span class="text-danger">*</span>
                    {!! Form::text('phone', $phone, ['class'=>'form-control', 'placeholder'=>'Phone', 'maxlength'=>20, 'required'=>true]) !!}
                    {!! $errors->first('phone', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!!Form::label('email', 'Email')!!} <span class="text-danger">*</span>
                    {!! Form::email('email', $email, ['class'=>'form-control', 'placeholder'=>'Email', 'maxlength'=>200, 'required'=>true]) !!}
                    {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!!Form::label('additional_email', 'Additional Email')!!} <span class="small">(Add multiple emails with comma ",") </span>
                    {!! Form::text('additional_email', $additional_email, ['class'=>'form-control', 'placeholder'=>'Additional Email', 'maxlength'=>200]) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('map_url', 'Map Url')!!}
            {!! Form::url('map_url', $map_url, ['class'=>'form-control', 'placeholder'=>'Map Url', 'maxlength'=>255]) !!}
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!!Form::label('ctm_code', 'CTM Code')!!}
                    {!! Form::text('ctm_code', $ctm_code, ['class'=>'form-control', 'placeholder'=>'CTM Code', 'maxlength'=>200]) !!}
                    {!! $errors->first('ctm_code', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!!Form::label('price_sheet', 'Price Sheet')!!}
                    {!! Form::text('price_sheet', $price_sheet, ['class'=>'form-control', 'placeholder'=>'Price Sheet', 'maxlength'=>200]) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('hours', 'Hours')!!}
            @foreach ($hours_Arr as $hr_key=>$hr_val)
            <div class="row mb-3 time-schedule">
                <label class="col-sm-4">
                    {!! Form::checkbox('hours['.$hr_key.'][status]', 1, isset($hours[$hr_key]['status'])) !!}
                    &nbsp; {{$hr_val}}
                </label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">From</span>
                        </div>
                        {!! Form::select('hours['.$hr_key.'][from]', $hrs, $hours[$hr_key]['from'] ?? '', ['class'=>'form-control min_hr', 'placeholder'=>'-- Select Hour --']) !!}    
                    </div>
                    <span class="text-danger hr-alert"></span>
                </div>
                <div class="col-sm-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">To</span>
                        </div>
                        {!! Form::select('hours['.$hr_key.'][to]', $hrs, $hours[$hr_key]['to'] ?? '', ['class'=>'form-control max_hr', 'placeholder'=>'-- Select Hour --']) !!}    
                    </div>
                    <span class="text-danger hr-alert"></span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="form-group">
            {!!Form::label('description', 'Description')!!}
            {!! Form::textarea('description', $description, ['class'=>'form-control', 'placeholder'=>'Description', 'rows'=>'3']) !!}
        </div>
        <hr>
        <div class="form-group text-center">
            {!! Form::submit('Update', ['class' => 'btn btn-primary btn-lg']) !!}
            &nbsp; &nbsp;
            {{ back_button(route('location.index')) }}
        </div>
    {!! Form::close() !!}
@endsection
@push('custom_scripts')
<script>
$(document).ready(function(){
    $('.min_hr').change(function() {
        check_hr(this)
    });
    $('.max_hr').change(function() {
        check_hr(this)
    });
    $('#locationForm').submit(function() {
        var a = true;
        $('.min_hr').each(function(index, value) {
            if (!check_hr(value)) 
                a = false;
        });
        return a;
    });
    function check_hr(a) {
        var p_div = $(a).parents('.time-schedule');
        if ($('input:checked', $('.min_hr', p_div).parent().parent().prev()).length == 0) {
            return true;
        }
        if ( $('.max_hr', p_div).val() == '' || $('.min_hr', p_div).val()=='' ) {
            $('.max_hr, .min_hr', p_div).parent().next('.hr-alert').html('Please Select Hour');
            return false
        } else {
            $('.max_hr, .min_hr', p_div).parent().next('.hr-alert').html('');
            
            var jdt1=Date.parse('20 Aug 2000 ' + $('.max_hr', p_div).val());
            var jdt2=Date.parse('20 Aug 2000 ' + $('.min_hr', p_div).val());
            if ( jdt1 <= jdt2 ) {
                $('.max_hr, .min_hr', p_div).addClass('border-danger');
                $('.max_hr, .min_hr', p_div).parent().next('.hr-alert').html('Please check Time');
                return false
            } else {
                $('.max_hr, .min_hr', p_div).removeClass('border-danger');
                $('.max_hr, .min_hr', p_div).parent().next('.hr-alert').html('');
                return true;
            }
        }
    }
})
</script>
@endpush