@extends('admin.layouts.app')
@section('title', 'Profile')
@section('breadcrumb')
  <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
          <div class="nav-tabs-custom col-8 ml-5">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#basic" role="tab" aria-controls="home" aria-selected="true">Basic</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#security" role="tab" aria-controls="profile" aria-selected="false">Security</a>
              </li>
            </ul>
            <div class="tab-content p-2">
              <div class="tab-pane fade show active" id="basic">
                
                @include('admin.pages.profile.basic')
                
              </div>

              <div class="tab-pane fade" id="security">

                 @include('admin.pages.profile.security')

              </div>
            </div>
          </div>
  <script>
    
     var ajax_url = "{{ route('admin-security') }}";

     var upload_url = "{{ route('admin-upload-image') }}";

     var image_url = "{{ asset('admin/images') }}/";

     

  </script>

  @stop