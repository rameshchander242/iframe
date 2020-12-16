@extends('user.layouts.app')
@section('title','Edit')

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => ['user.iframe.update', $iframe['id']], 'files' => true,]) !!}
    <div class="form-group">
      <div class="row">
        <div class="col-sm-4">{!!Form::label('name', 'Name')!!}</div>
        <div class="col-sm-8">{{ $iframe['name'] }}</div>
        <div class="col-sm-4">{!!Form::label('name', 'Locations')!!}</div>
        <div class="col-sm-8"> 
            @foreach ($iframe['user']['locations'] as $location)
                {{$location['store_name'] }}
            @endforeach
        </div>
        <div class="col-sm-4">{!!Form::label('success_page', 'Thank You Page URL')!!}</div>
        <div class="col-sm-8">{!! Form::text('success_page', $iframe['success_page'], ['class'=>'form-control', 'placeholder'=>'Thank You Page URL']) !!}</div>
      </div>
    </div>
    <div class="card iframe-table">
        <div class="card-body table-responsive p-1">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active bg-info" data-toggle="tab" href="#location_default">Default</a>
                </li>
                @if (count($iframe['user']['locations']) > 1) 
                    @foreach ($iframe['user']['locations'] as $location)
                    <li class="nav-item">
                        <a class="nav-link bg-info" data-toggle="tab" href="#location_{{$location['id']}}">{{$location['store_name']}}</a>
                    </li>
                    @endforeach
                @endif
            </ul>
            <?php $l_key = 'default'; ?>
            <div class="tab-content pt-2">
                <div id="location_default" class="col-12 tab-pane active">
                    <h3 class="text-info">Price List for All Locations</h3>
                    @include('user.pages.iframe.edit_list')
                </div>

                @if (count($iframe['user']['locations']) > 1) 
                    @foreach ($iframe['user']['locations'] as $location)
                        <div id="location_{{$location['id']}}" class="col-12 tab-pane">
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
@endsection


@push('custom_scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $( "table.sortable tbody" ).sortable({ handle: ".cursor-grab", opacity: 0.6,  });
    });
</script>
@endpush