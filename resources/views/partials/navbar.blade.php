 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
     <!-- Left navbar links -->
     <ul class="navbar-nav">
         <li class="nav-item">
             <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
         </li>
     </ul>
     <!-- Right navbar links -->
     <ul class="navbar-nav ml-auto">
         <!-- Full Screen -->
         <li class="nav-item">
             <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                 <i class="fas fa-expand-arrows-alt"></i>
             </a>
         </li>
         <!-- Messages Dropdown Menu -->
         <li class="nav-item dropdown">
             <a class="nav-link" data-toggle="dropdown" href="#">
                 <i class="fas fa-user"></i>
             </a>
             <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                 <a href="#" class="dropdown-item">
                     <div class="media">
                         <div class="media-body">
                             <h3 class="dropdown-item-title">
                                 Hi, {{ auth()->user()->name }}
                             </h3>
                         </div>
                     </div>
                     <!-- Message End -->
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">
                     <div class="media">
                         <i class="fas fa-house-user mr-5"></i>
                         <div class="media-body">
                             <h3 class="dropdown-item-title">
                                 Manage Profile
                                 <span class="float-right text-sm text-muted"><i class="fas fa-arrow-right"></i></span>
                             </h3>
                         </div>
                     </div>
                     <!-- Message End -->
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     <div class="media">
                         <i class="fas fa-power-off mr-5"></i>
                         <div class="media-body">
                             <h3 class="dropdown-item-title">
                                 Logout
                                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                     @csrf
                                 </form>
                                 <span class="float-right text-sm text-muted"><i class="fas fa-arrow-right"></i></span>
                             </h3>
                         </div>
                     </div>
                     <!-- Message End -->
                 </a>
                 <div class="dropdown-divider"></div>

             </div>
         </li>
     </ul>
 </nav>
 <!-- /.navbar -->
