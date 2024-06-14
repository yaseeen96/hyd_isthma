  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-purple elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
          <img src="{{ asset('assets/img/logos/logo.png') }}" alt="Laravel Logo" class="brand-image"
              style="filter: invert(100%)" style="opacity: .8">
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
              </div>
              <div class="info">
                  <a href="#" class="d-block">{{ ucfirst(auth()->user()->name) }} </a>
              </div>
          </div>
          <!-- SidebarSearch Form -->
          <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                  <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                      aria-label="Search">
                  <div class="input-group-append">
                      <button class="btn btn-sidebar">
                          <i class="fas fa-search fa-fw"></i>
                      </button>
                  </div>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                  data-accordion="false">
                  <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                  <li class="nav-item">
                      <a href="{{ route('dashboard') }}"
                          class="nav-link {{ request()->route()->getName() === 'dashboard' ? 'active' : '' }}">
                          <i class="nav-icon fas fa-th"></i>
                          <p>
                              Dashboard
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('members.index') }}"
                          class="nav-link {{ in_array(request()->route()->getName(), ['members.index', 'members.create', 'members.edit']) ? 'active' : '' }}">
                          <i class="nav-icon fas fa-users"></i>
                          <p>
                              Members
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('registrations.index') }}"
                          class="nav-link {{ in_array(request()->route()->getName(), ['registrations.index', 'registrations.show']) ? 'active' : '' }}">
                          <i class="nav-icon fas fa-file-alt"></i>
                          <p>
                              Registrations
                          </p>
                      </a>
                  </li>
              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>
