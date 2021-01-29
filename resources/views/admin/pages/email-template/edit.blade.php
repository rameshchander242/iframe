@extends('admin.layouts.app')
@section('title','Edit Message Template')
@push('custom_styles')
<link href="{{ asset('css/admin/summernote-bs4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => ['email-template.update', $id]]) !!}
<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            {!!Form::label('email_type', 'Message Type')!!}: 
            <span class="badge badge-success">{!! config('widget.'.$email_type) !!}</span>
        </div>
        <div class="form-group">
            {!!Form::label('email_type', 'Group')!!}: 
            <?php
            $group = isset($category['name']) ? 'Category: '.$category['name'].', ' : '';
            $group .= isset($brand['name']) ? 'Brand: '.$brand['name'].', ' : '';
            $group .= isset($series['name']) ? 'Series: '.$series['name'].'' : '';
            ?>
            {!! $group !!}
        </div>
        <div class="form-group">
            {!!Form::label('subject', 'Subject')!!} <span class="text-danger">*</span>
            {!! Form::text('subject', $subject, ['class'=>'form-control', 'placeholder'=>'Subject Name', 'maxlength'=>200, 'required'=>true]) !!}
            {!! $errors->first('subject', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {!!Form::label('body', 'Email Template')!!} <span class="text-danger">*</span>
            {!! Form::textarea('body', $body, ['class'=>'form-control summernote', 'placeholder'=>'Email Body', 'rows'=>'10', 'required'=>true, 'style'=>'height:300px;']) !!}
            {!! $errors->first('body', '<p class="text-danger">:message</p>') !!}
        </div>
        @if ($email_type != 'client_email')
        <div class="form-group">
            {!!Form::label('sms_message', 'SMS Template')!!} <span class="text-danger">*</span>
            {!! Form::textarea('sms_message', $sms_message, ['class'=>'form-control', 'placeholder'=>'SMS Message', 'rows'=>'5', 'required'=>true]) !!}
            {!! $errors->first('sms_message', '<p class="text-danger">:message</p>') !!}
        </div>
        @endif
        <div class="form-group">
            {!! Form::hidden('status', '0') !!}
            {!!Form::label('', 'Status')!!}<br>
            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                {!! Form::checkbox('status', '1', $status==1, ['class'=>'custom-control-input', 'id'=>'status']) !!}
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
    $('.summernote').summernote({
      placeholder: 'Template Content',
      tabsize: 2,
      height: 280
    });
</script>
@endpush