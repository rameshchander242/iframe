<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('user.partials.head')
  
  @stack('custom_styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  @include('user.partials.header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('user.partials.aside')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @include('user.partials.flashes')

    <!-- Content Header (Page header) -->
    @include('user.partials.content-header')
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="card">
        <div class="card-body">
          @yield('content')
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  @include('user.partials.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('user.partials.scripts')

@stack('custom_scripts')
</body>
</html>
