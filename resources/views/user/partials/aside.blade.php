<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link bg-light">
      <img src="{{ asset('images/logo.png') }}" alt="Repair Lift" class="brand-image"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Client Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <i class="fa fa-user text-light fa-3x"></i>
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <?php
      $navs = [
        ['text'=>'Dashboard', 'icon'=>'tachometer-alt', 'link'=>route('dashboard')],
        ['text'=>'Repair Info', 'icon'=>'file', 'link'=>route('user.iframe_info.index')],
        ['text'=>'Price List', 'icon'=>'dollar-sign', 'link'=>route('user.iframe.index')],
        ['text'=>'Leads', 'icon'=>'envelope', 'link'=>route('user.queries.index')],
        ['text'=>'Message Templates', 'icon'=>'envelope', 'link'=>route('user.email-template.index')],
        ['text'=>'Locations', 'icon'=>'building', 'link'=>route('user.location.index')],
        ['text'=>'Profile', 'icon'=>'user', 'link'=>route('profile')],
      ]
      ?>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @foreach($navs as $nav)
            <li class="nav-item has-treeview">
              <a href="{{ $nav['link'] }}" class="nav-link">
                <i class="nav-icon fas fa-{{ $nav['icon'] }}"></i>
                <p>{{$nav['text']}} @if(isset($nav['subnav']))<i class="right fas fa-angle-left"></i>@endif</p>
              </a>
              
              @if( isset($nav['subnav']) && is_array($nav['subnav']) )
                <ul class="nav nav-treeview subnav">
                @foreach($nav['subnav'] as $subnav)
                <li class="nav-item">
                  <a href="{{ $subnav['link'] }}" class="nav-link">
                    <i class="nav-icon fas fa-{{ $subnav['icon'] }}"></i>
                    <p>{{$subnav['text']}}</p>
                  </a>
                </li>
                @endforeach
                </ul>
              @endif
            </li>
          @endforeach
          
          <li class="nav-item has-treeview">
            <a href="{{ route('logout') }}" class="nav-link text-danger font-weight-bold">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>