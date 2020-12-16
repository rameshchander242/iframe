
<!DOCTYPE html>
<html lang="en">
<head>
  @include('admin.partials.head')
  
  @stack('custom_styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  @include('admin.partials.header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admin.partials.aside')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @include('admin.partials.flashes')

    <!-- Content Header (Page header) -->
    @include('admin.partials.content-header')
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  @include('admin.partials.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('admin.partials.scripts')

@stack('custom_scripts')
</body>
</html>
