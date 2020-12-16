@extends('user.layouts.app')
@section('title','Price List')

@section('content')
<div class="iframe-table ">
    <ul class="nav nav-tabs border-info">
        @foreach($iframes as $key=>$iframe)
        <li class="nav-item">
        <a class="nav-link bg-info {{$key==0 ?'active':''}}" data-toggle="tab" href="#iframe_{{$iframe['id']}}">{{$iframe['name']}}</a>
        </li>
        @endforeach
    </ul>
    <div class="tab-content pt-2 border border-info">
    @foreach($iframes as $key=>$iframe)
    <div id="iframe_{{$iframe['id']}}" class="col-12 tab-pane {{$key==0 ?'active':''}}">
        {!! Form::open(['method' => 'PUT', 'route' => ['user.iframe.update', $iframe['id']], 'files' => true,]) !!}
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">{!!Form::label('name', 'Name')!!}</div>
                    <div class="col-sm-8">{{ $iframe['name'] }}</div>

                    <div class="col-sm-4">{!!Form::label('embed', 'Iframe Embed')!!}</div>
                    <div class="col-sm-8">{!! modal_button( $iframe['id'] ) !!}</div>

                    <div class="col-sm-4">{!!Form::label('location', 'Locations')!!}</div>
                    <div class="col-sm-8"> 
                        @foreach ($iframe['user']['locations'] as $location)
                            {{$location['store_name'] }}
                        @endforeach
                    </div>
                    <div class="col-sm-4">{!!Form::label('success_page', 'Thank You Page URL')!!}</div>
                    <div class="col-sm-8">{!! Form::text('success_page', $iframe['success_page'], ['class'=>'form-control', 'placeholder'=>'Thank You Page URL']) !!}</div>
                </div>
            </div>
            <div class="card border border-info">
                <div class="card-body table-responsive p-1">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active bg-info" data-toggle="tab" href="#location_{{$iframe['id']}}_default">Default</a>
                        </li>
                        @if (count($iframe['user']['locations']) > 1) 
                            @foreach ($iframe['user']['locations'] as $location)
                            <li class="nav-item">
                                <a class="nav-link bg-info" data-toggle="tab" href="#location_{{$iframe['id']}}_{{$location['id']}}">{{$location['store_name']}}</a>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                    <?php $l_key = 'default'; ?>
                    <div class="tab-content pt-2">
                        <div id="location_{{$iframe['id']}}_default" class="col-12 tab-pane active">
                            <h3 class="text-info">Price List for All Locations</h3>
                            @include('user.pages.iframe.edit_list')
                        </div>

                        @if (count($iframe['user']['locations']) > 1) 
                            @foreach ($iframe['user']['locations'] as $location)
                                <div id="location_{{$iframe['id']}}_{{$location['id']}}" class="col-12 tab-pane">
                                    <h3 class="text-info">Price List for {{$location['store_name']}}</h3>
                                    <?php $l_key = $location['id']; ?>
                                    @include('user.pages.iframe.edit_list')
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
            
            </div>

            <hr>
            <div class="form-group text-center">
                {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
                &nbsp; &nbsp;
                {{ back_button(route('user.iframe.index')) }}
            </div>
        {!! Form::close() !!}
    </div>
    @endforeach
    </div>
</div>
@endsection


@push('custom_scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $( "table.sortable tbody" ).sortable({ handle: ".cursor-grab", opacity: 0.6,  });
    });
</script>
@endpush