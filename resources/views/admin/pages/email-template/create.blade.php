@extends('admin.layouts.app')
@section('title','Create Message Template')
@push('custom_styles')
<link href="{{ asset('css/admin/summernote-bs4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
{!! Form::open(['method' => 'POST', 'route' => ['email-template.store']]) !!}
{!! Form::hidden('user_id', Auth::id()) !!}
<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            {!!Form::label('email_type', 'Message Type')!!}  <span class="text-danger">*</span>
            {!! Form::select('email_type', $email_types, old('email_type'), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!!Form::label('category_id', 'Category')!!}: 
            {!! Form::select('category_id', $categories, old('category_id'), ['class'=>'form-control', 'id'=>'category', 'placeholder'=>'-- Select Category --']) !!}
        </div>
        
        <div class="form-group" id="brand_group">
        </div>
        <div class="form-group" id="series_group">
        </div>
        <div class="form-group" id="item_group">
        </div>
        <div class="form-group">
            {!!Form::label('subject', 'Subject')!!} <span class="text-danger">*</span>
            {!! Form::text('subject', '', ['class'=>'form-control', 'placeholder'=>'Subject Name', 'required'=>true]) !!}
            {!! $errors->first('subject', '<p class="text-danger">:message</p>') !!}
        </div>  
        <div class="form-group">
            {!!Form::label('body', 'Email Template')!!} <span class="text-danger">*</span>
            {!! Form::textarea('body', '', ['class'=>'form-control summernote', 'placeholder'=>'Email Template', 'rows'=>'5', 'required'=>true]) !!}
            {!! $errors->first('body', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('sms_message', 'SMS Template')!!} <span class="text-danger">*</span>
            {!! Form::textarea('sms_message', '', ['class'=>'form-control', 'placeholder'=>'SMS Template', 'rows'=>'5', 'required'=>true]) !!}
            {!! $errors->first('sms_message', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!! Form::hidden('status', '0') !!}
            {!!Form::label('', 'Status')!!}<br>
            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                {!! Form::checkbox('status', '1', 1, ['class'=>'custom-control-input', 'id'=>'status']) !!}
                {!!Form::label('status', 'Publish', ['class'=>'custom-control-label'])!!}
            </div>
        </div>
    </div>
    <div class="col-sm-4 border">
        <h4 class="text-center">Shortcodes</h4>
        <div class="text-center">
            (<small class="text-info">Please copy Shortcode and use in Email/SMS Template</small>)
        </div>
        <?php $shortcodes = config('widget.shortcodes'); ?>
        <table class="table table-sm">
            @foreach ($shortcodes as $s_key=>$s_info)
            <tr>
                <td>[{{$s_key}}]</td>
                <td class="small">{{$s_info}}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('email-template.index')) }}
    </div>
{!! Form::close() !!}
@endsection

@push('custom_scripts')
<script src="{{ asset('js/admin/summernote-bs4.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.summernote').summernote({
      placeholder: 'Template Content',
      tabsize: 2,
      height: 280
    });

    $('#category').change(function() {
        var _token = $('input[name="_token"]').val();
        var catId = $(this).val();
        $(this).before('<i class="ml-2 fa fa-spinner fa-spin"></i>');
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
    
    $(document).on('change', '#brand_group select', function() {
        var _token = $('input[name="_token"]').val();
        var brandId = $(this).val();
        $(this).before('<i class="ml-2 fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: "{{ route('ajax.brand-series') }}", 
            method: "POST",
            data: {'id': brandId, _token:_token},
            success: function(result){
                $("#series_group").html(result);
                $('.fa.fa-spinner.fa-spin').remove();
            }
        });
    });
});
</script>
@endpush