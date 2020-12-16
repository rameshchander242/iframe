@extends('admin.layouts.app')
@section('title','View Brand')

@section('content')
    <div class="row show-div">
        <div class="col-sm-4">{!!Form::label('name', 'Name')!!}</div>
        <div class="col-sm-8">{{ $iframe['name'] }}</div>
        <div class="col-sm-4">{!!Form::label('location', 'Location')!!}</div>
        <div class="col-sm-8">{{ $location['store_name'] }}</div>

        <div class="col-sm-4">{!!Form::label('category', 'Category')!!}</div>
        <div class="col-sm-8">{{ $category['name'] }}</div>

        <div class="col-sm-4">{!!Form::label('brand', 'Brand')!!}</div>
        <div class="col-sm-8">{{ $brand['name'] ?? '--' }}</div>

        <div class="col-sm-4">{!!Form::label('series', 'Series')!!}</div>
        <div class="col-sm-8">{{ $series['name'] ?? '--' }}</div>

        <div class="col-sm-4">{!!Form::label('item', 'Item')!!}</div>
        <div class="col-sm-8">{{ $item['name'] ?? '--' }}</div>

        <div class="col-sm-4">{!!Form::label('status', 'Status')!!}</div>
        <div class="col-sm-8">{!! view_status($status) !!}</div>

        <div class="col-sm-4">{!!Form::label('timeframe', 'Timeframe')!!}</div>
        <div class="col-sm-8">{{ $timeframe ?? '--' }}</div>

        <div class="col-sm-4">{!!Form::label('warranty', 'Warranty')!!}</div>
        <div class="col-sm-8">{{ $warranty ?? '--' }}</div>

        <div class="col-sm-4">{!!Form::label('description', 'Description')!!}</div>
        <div class="col-sm-8">{{ $description ?? '--' }}</div>
    </div>
    <hr>
    <div class="form-group text-center">
        {{ back_button(route('brand.index')) }}
    </div>
@endsection

