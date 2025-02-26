<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link pb-3 mb-3">
      <img src="{{ asset('Admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ClevBuild</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('Admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name  }}</a>
        </div>
      </div> -->

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @can('accessDashboard')
            <li class="nav-item">
              <a href="{{route('dashboard')}}" class="nav-link {{(request()->is('dashboard/*') || request()->is('dashboard') ) ? 'active' : ''}}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>
          @endcan

          @can('viewCompanies')
          <li class="nav-item">
            <a href="{{route('companies')}}" class="nav-link {{(request()->is('companies/*') || request()->is('companies') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-building"></i>
              <p>Companies</p>
            </a>
          </li>
          @endcan

          @can('viewProjects')
          <li class="nav-item">
            <a href="{{route('projects')}}" class="nav-link {{(request()->is('projects/*') || request()->is('projects') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-building"></i>
              <p>Projects</p>
            </a>
          </li>
          @endcan

          @can('viewFloors')
          <!-- <li class="nav-item">
            <a href="{{route('floors')}}" class="nav-link {{(request()->is('floors/*') || request()->is('floors') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-building"></i>
              <p>Floors</p>
            </a>
          </li> -->
          @endcan

          @can('viewManagers')
          <li class="nav-item">
            <a href="{{route('managers')}}" class="nav-link {{(request()->is('managers/*') || request()->is('managers') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-solid fa-users"></i>
              <p>Managers</p>
            </a>
          </li>
          @endcan

          @can('viewMembers')
          <li class="nav-item">
            <a href="{{route('members')}}" class="nav-link {{(request()->is('members/*') || request()->is('members') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-solid fa-users"></i>
              <p>Members</p>
            </a>
          </li>
          @endcan

          @can('viewFollowers')
          <li class="nav-item">
            <a href="{{route('followers')}}" class="nav-link {{(request()->is('followers/*') || request()->is('followers') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-solid fa-users"></i>
              <p>Followers</p>
            </a>
          </li>
          @endcan

          @can('viewAccountants')
          <li class="nav-item">
            <a href="{{route('accountants')}}" class="nav-link {{(request()->is('accountants/*') || request()->is('accountants') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-solid fa-users"></i>
              <p>Accountants</p>
            </a>
          </li>
          @endcan

          @can('viewInvoices')
          <li class="nav-item">
            <a href="{{route('invoices')}}" class="nav-link {{(request()->is('invoices/*') || request()->is('invoices') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-solid fa-users"></i>
              <p>Invoices</p>
            </a>
          </li>

          
          <li class="nav-item">
            <a href="{{route('addtaskamount')}}" class="nav-link {{(request()->is('addtaskamount/*') || request()->is('addtaskamount') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-solid fa-users"></i>
              <p>Add Task Amount</p>
            </a>
          </li>
          
          @endcan

          <!-- <li class="nav-item">
            <a href="{{route('projects.completed')}}" class="nav-link {{(request()->is('completed-projects/*') || request()->is('completed-projects') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Completed Projects</p>
            </a>
          </li> -->

          @can('viewRoles')
          <li class="nav-item">
            <a href="{{route('roles')}}" class="nav-link {{(request()->is('roles/*') || request()->is('roles') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-solid fa-users"></i>
              <p>Roles & Permissions</p>
            </a>
          </li>
          @endcan
          @can('viewLayerTemplates')
            <li class="nav-item">
            <a href="{{route('layer-templates')}}" class="nav-link {{(request()->is('layer-templates/*') || request()->is('layer-templates') ) ? 'active' : ''}}">
              <!-- <a href="{{route('layers.settings')}}" class="nav-link {{(request()->is('layer-templates/*') || request()->is('layer-templates') ) ? 'active' : ''}}"> -->
                <i class="nav-icon fas fa-cog"></i>
                <p>Layer Templates</p>
              </a>
            </li>
          @endcan
          <li class="nav-item">
            <a href="{{route('logout')}}" role="button" class="nav-link {{(request()->is('logout/*') || request()->is('logout') ) ? 'active' : ''}}">
              <i class="nav-icon fa fa-sign-out" aria-hidden="true"></i>Log Out
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="{{route('cadeditor')}}" class="nav-link {{(request()->is('completed-cadviewer/*') || request()->is('cadeditor') ) ? 'active' : ''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>CAD Viewer</p>
            </a>
          </li> -->
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
