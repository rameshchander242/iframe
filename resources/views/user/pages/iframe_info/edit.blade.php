@extends('user.layouts.app')
@section('title','Edit Repair Info')

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => ['user.iframe_info.update', $iframe_info['id']], 'files' => true,]) !!}
    <div class="form-group">
      <div class="row">
        <div class="col-sm-4">{!!Form::label('name', 'Name')!!}</div>
        <div class="col-sm-8">{{ $iframe_info['iframe']['name'] }}</div>
        <div class="col-sm-4">{!!Form::label('location', 'Location')!!}</div>
        <div class="col-sm-8">{{ $iframe_info['location']['store_name'] }}</div>

        <div class="col-sm-4">{!!Form::label('category', 'Category')!!}</div>
        <div class="col-sm-8">{{ $iframe_info['category']['name'] }}</div>

        <div class="col-sm-4 mb-3">{!!Form::label('brand', 'Brand')!!}</div>
        <div class="col-sm-8 mb-3">{{ $iframe_info['brand']['name'] ?? '--' }}</div>

        <div class="col-sm-4 mb-3">{!!Form::label('series', 'Series')!!}</div>
        <div class="col-sm-8 mb-3">{{ $iframe_info['series']['name'] ?? '--' }}</div>

        <div class="col-sm-4 mb-3">{!!Form::label('item', 'Item')!!}</div>
        <div class="col-sm-8 mb-3">{{ $iframe_info['item']['name'] ?? '--' }}</div>

        <div class="col-sm-4 mb-3">{!!Form::label('', 'Status')!!}</div>
        <div class="col-sm-8 mb-3">
          {!! Form::hidden('status','0') !!}
          <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
              {!! Form::checkbox('status', '1', $iframe_info['status'], ['class'=>'custom-control-input', 'id'=>'status']) !!}
              {!!Form::label('status', 'Publish', ['class'=>'custom-control-label'])!!}
          </div>
        </div>
        
        <div class="col-sm-4 mb-3">{!!Form::label('timeframe', 'Timeframe')!!}</div>
        <div class="col-sm-8 mb-3">{!! Form::text('timeframe', $iframe_info['timeframe'], ['class'=>'form-control', 'placeholder'=>'Timeframe']) !!}</div>
        
        <div class="col-sm-4 mb-3">{!!Form::label('warranty', 'Warranty')!!}</div>
        <div class="col-sm-8 mb-3">{!! Form::text('warranty', $iframe_info['warranty'], ['class'=>'form-control', 'placeholder'=>'Warranty']) !!}</div>
        
        <div class="col-sm-4">{!!Form::label('description', 'Repair Description')!!}</div>
        <div class="col-sm-8">{!! Form::textarea('description', $iframe_info['description'], ['class'=>'form-control', 'placeholder'=>'Description']) !!}</div>
      </div>
    </div>

    <hr>
    <div class="form-group text-center">
        {!! Form::hidden('id', $iframe_info['id']) !!}
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('user.iframe_info.index')) }}
    </div>
{!! Form::close() !!}
@endsection