@extends('user.layouts.app')
@section('title','Reply to Lead')

@section('content')
    <div class="row">
        <div class="col-sm-6 card">
            <div class="row">
                <h3 class="text-info col-12 mt-2 mb-3">Customer Query</h3>
                
                <div class="col-sm-4"><label>Item</label></div>
                <div class="col-sm-8"> {{$query['item']['name']}} </div>
                
                <div class="col-sm-4"><label>Service</label></div>
                <div class="col-sm-8"> {{$query['service']['name']}} </div>
                
                <div class="col-sm-4"><label>Location</label></div>
                <div class="col-sm-8"> {{$query['location']['store_name']}} </div>
                <hr class="w-100" />

                <div class="col-sm-4"><label>Name</label></div>
                <div class="col-sm-8"> {{$query['fullname']}} </div>
                
                <div class="col-sm-4"><label>Email</label></div>
                <div class="col-sm-8"> {{$query['email']}} </div>
                
                <div class="col-sm-4"><label>Phone</label></div>
                <div class="col-sm-8"> {{$query['phone']}} </div>
                
                <div class="col-sm-4"><label>Message</label></div>
                <div class="col-sm-8"> {{$query['message']}} </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card iframe-table">
                <div class="card-body p-1">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active bg-info" data-toggle="tab" href="#email_message">Email</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bg-info" data-toggle="tab" href="#sms_message">Message</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bg-info" data-toggle="tab" href="#send_phone">Phone</a>
                        </li>
                    </ul>
                    <div class="tab-content pt-2">
                        <div id="email_message" class="col-12 tab-pane active">
                            <h3 class="text-info">Email</h3>
                            {!! Form::open(['method' => 'POST', 'route' => ['user.queries.send', $query['id']], 'files' => true,]) !!}
                            <div class="form-group">
                                {!!Form::label('name', 'Name')!!} <span class="text-danger">*</span>
                                {!! Form::text('name', $query['fullname'], ['class'=>'form-control', 'placeholder'=>'Name', 'required'=>true]) !!}
                            </div>

                            <div class="form-group">
                                {!!Form::label('email_phone', 'Email')!!} <span class="text-danger">*</span>
                                {!! Form::text('email_phone', $query['email'], ['class'=>'form-control', 'placeholder'=>'Email', 'required'=>true]) !!}
                            </div>
                            
                            <div class="form-group">
                                {!!Form::label('subject', 'Subject')!!} <span class="text-danger">*</span>
                                {!! Form::text('subject', $emailTemplate['subject'], ['class'=>'form-control', 'placeholder'=>'Subject', 'required'=>true]) !!}
                            </div>
                            
                            <div class="form-group">
                                {!!Form::label('message', 'Message')!!} <span class="text-danger">*</span>
                                {!! Form::textarea('message', $emailTemplate['body'], ['class'=>'form-control', 'placeholder'=>'Message...', 'required'=>true, 'rows'=>3]) !!}
                            </div>
                            <div class="form-group text-center">
                                {!! Form::hidden('contact_type', 'email') !!}
                                {!! Form::submit('Send Email', ['class' => 'btn btn-primary']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>

                        
                        <div id="sms_message" class="col-12 tab-pane">
                            <h3 class="text-info">Message</h3>
                            {!! Form::open(['method' => 'POST', 'route' => ['user.queries.send', $query['id']], 'files' => true,]) !!}
                            <div class="form-group">
                                {!!Form::label('email_phone', 'Phone')!!} <span class="text-danger">*</span>
                                {!! Form::text('email_phone', $query['phone'], ['class'=>'form-control', 'placeholder'=>'Phone', 'required'=>true]) !!}
                            </div>
                            
                            <div class="form-group">
                                {!!Form::label('message', 'Message')!!} <span class="text-danger">*</span>
                                {!! Form::textarea('message', $emailTemplate['sms_message'], ['class'=>'form-control', 'placeholder'=>'Message...', 'required'=>true, 'rows'=>3]) !!}
                            </div>
                            <div class="form-group text-center">
                                {!! Form::hidden('contact_type', 'sms') !!}
                                {!! Form::submit('Send SMS', ['class' => 'btn btn-primary']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>

                        
                        <div id="send_phone" class="col-12 tab-pane">
                            <h3 class="text-info">Phone</h3>
                            <div class="form-group">
                                {!!Form::label('phone', 'Phone')!!}
                                <div class="form-control">{{$query['phone']}}</div>
                            </div>
                            <div class="form-group text-center">
                                <a class="btn btn-primary text-light" href="tel:{{$query['phone']}}">
                                    <i class="fa fa-phone"></i> Call
                                </a>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h3 class="text-info text-center mt-5">All Replies</h3>
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Email/Phone</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
        @foreach ($replies as $reply)
        <tr>
            <td>{{ $reply['name'] ?? '--' }}</td>
            <td>{{ $reply['email_phone'] }}</td>
            <td>{{ $reply['message'] }}</td>
            <td>{{ $reply['created_at'] }}</td>
        </tr>
        @endforeach
    </table>
@endsection