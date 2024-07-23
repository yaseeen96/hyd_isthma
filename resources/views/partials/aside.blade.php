  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-purple elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link flex">
          <img src="{{ asset('assets/img/logos/logo.png') }}" alt="Laravel Logo" class="brand-image"
              style="filter: invert(100%)" style="opacity: .8">

      </a>
      <!-- Sidebar -->
      <div class="sidebar">
          <!-- SidebarSearch Form -->
          <div class="row py-2 px-1">
              <div class="col-lg-12">
                  <span class="text-white">Hi, {{ ucfirst(auth()->user()->name) }}</span>
              </div>
          </div>
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
                  @if (auth()->user()->can('Create Members') ||
                          auth()->user()->can('View Members') ||
                          auth()->user()->can('Edit Members') ||
                          auth()->user()->can('Delete Members') ||
                          auth()->user()->id == 1)
                      <li class="nav-item">
                          <a href="{{ route('members.index') }}"
                              class="nav-link {{ in_array(request()->route()->getName(), ['members.index', 'members.create', 'members.edit']) ? 'active' : '' }}">
                              <i class="nav-icon fas fa-users"></i>
                              <p>
                                  Members
                              </p>
                          </a>
                      </li>
                  @endif
                  @if (auth()->user()->can('Create Registrations') ||
                          auth()->user()->can('View Registrations') ||
                          auth()->user()->can('Edit Registrations') ||
                          auth()->user()->can('Delete Registrations') ||
                          auth()->user()->id == 1)
                      <li class="nav-item">
                          <a href="{{ route('registrations.index') }}"
                              class="nav-link {{ in_array(request()->route()->getName(), ['registrations.index', 'registrations.show']) ? 'active' : '' }}">
                              <i class="nav-icon fas fa-file-alt"></i>
                              <p>
                                  Registrations
                              </p>
                          </a>
                      </li>
                  @endif
                  <li class="nav-header">REPORTS</li>
                  @if (auth()->user()->can('View TravelReport') || auth()->user()->id == 1)
                      <li
                          class="nav-item  {{ in_array(request()->route()->getName(), ['arrival-report', 'departure-report']) ? 'menu-open' : '' }}">
                          <a href="#"
                              class="nav-link {{ in_array(request()->route()->getName(), ['arrival-report', 'departure-report']) ? 'active' : '' }}">
                              <i class="nav-icon fas fa-paper-plane"></i>
                              <p>
                                  Travel Report
                                  <i class="fas fa-angle-left right"></i>
                              </p>
                          </a>
                          <ul class="nav nav-treeview">
                              <li class="nav-item">
                                  <a href="{{ route('arrival-report') }}"
                                      class="nav-link {{ request()->route()->getName() === 'arrival-report' ? 'active' : '' }}">
                                      <i class="nav-icon fas fa-plane-arrival"></i>
                                      <p>
                                          Arrival
                                      </p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('departure-report') }}"
                                      class="nav-link {{ request()->route()->getName() === 'departure-report' ? 'active' : '' }}">
                                      <i class="nav-icon fas fa-plane-departure"></i>
                                      <p>
                                          Departure
                                      </p>
                                  </a>
                              </li>
                          </ul>
                      </li>
                  @endif
                  @if (auth()->user()->can('View HealthReport') || auth()->user()->id == 1)
                      <li class="nav-item">
                          <a href="{{ route('health-report') }}"
                              class="nav-link {{ request()->route()->getName() === 'health-report' ? 'active' : '' }}">
                              <i class="nav-icon fas fa-book-medical"></i>
                              <p>
                                  Health Report
                              </p>
                          </a>
                      </li>
                  @endif
                  @if (auth()->user()->can('View TourReport') || auth()->user()->id == 1)
                      <li class="nav-item">
                          <a href="{{ route('tour-report') }}"
                              class="nav-link {{ request()->route()->getName() === 'tour-report' ? 'active' : '' }}">
                              <i class="nav-icon fas fa-shopping-bag"></i>
                              <p>
                                  Tour Report
                              </p>
                          </a>
                      </li>
                  @endif
                  @if (auth()->user()->can('View Notificaitons') ||
                          auth()->user()->can('View Permissions') ||
                          auth()->user()->can('View Users') ||
                          auth()->user()->id == 1)
                      <li class="nav-header">SETTINGS</li>
                  @endif
                  @if (auth()->user()->can('Create Notifications') ||
                          auth()->user()->can('View Notifications') ||
                          auth()->user()->can('Edit Notifications') ||
                          auth()->user()->can('Delete Notifications') ||
                          auth()->user()->id == 1)
                      <li class="nav-item">
                          <a href="{{ route('notifications.index') }}"
                              class="nav-link {{ request()->route()->getName() === 'notifications.index' ? 'active' : '' }}">
                              <i class="nav-icon fas fa-bell"></i>
                              <p>
                                  Notifications
                              </p>
                          </a>
                      </li>
                  @endif
                  @if (auth()->user()->can('Create Permissions') ||
                          auth()->user()->can('View Permissions') ||
                          auth()->user()->can('Edit Permissions') ||
                          auth()->user()->can('Delete Permissions') ||
                          auth()->user()->id == 1)
                      <li class="nav-item">
                          <a href="{{ route('permissions.index') }}"
                              class="nav-link {{ in_array(request()->route()->getName(), ['permissions.index', 'permissions.edit', 'permissions.create']) ? 'active' : '' }}">
                              <i class="nav-icon fas fa-ban"></i>
                              <p>
                                  Permissions
                              </p>
                          </a>
                      </li>
                  @endif
                  @if (auth()->user()->can('Create Users') ||
                          auth()->user()->can('View Users') ||
                          auth()->user()->can('Edit Users') ||
                          auth()->user()->can('Delete Users') ||
                          auth()->user()->id == 1)
                      <li class="nav-item">
                          <a href="{{ route('user.index') }}"
                              class="nav-link {{ in_array(request()->route()->getName(), ['user.index', 'user.edit', 'user.create']) ? 'active' : '' }}">
                              <i class="nav-icon fas fa-users"></i>
                              <p>
                                  Users
                              </p>
                          </a>
                      </li>
                  @endif
              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>
