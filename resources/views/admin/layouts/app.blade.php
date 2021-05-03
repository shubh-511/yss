<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/AdminLTE.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/skins/_all-skins.min.css')}}">
    <link href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">

    @stack('calander')
    <style type="text/css">
      .image-area{
        width: 50%;
        height: 100px;
        position: absolute;
        top:40rem;
        bottom: 0;
        left: 0;
        right: 0;
          
        margin: auto;
      }
      .file-btn {margin-top:1%}
    </style>  
    @yield('header_styles')
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        
      <header class="main-header">
            <!-- Logo -->
            <a href="{{ url('login/dashboard') }}" class="logo">
              <!-- mini logo for sidebar mini 50x50 pixels -->
              <span class="logo-mini"><b>{{ config('app.name', 'Laravel') }}</b></span>
              <!-- logo for regular state and mobile devices -->
              <span class="logo-lg"><b>{{ config('app.name', 'Laravel') }}</b></span>
            </a>
            <nav class="navbar navbar-static-top">
              <!-- Sidebar toggle button-->
              <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </a>

              <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                  
                  <li class="dropdown user user-menu">
                    <a style="padding-bottom: 35px!important;" href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <img src="{{ asset('uploads/user-unnamed.png') }}" class="user-image" alt="User Image">
                      <span class="hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- User image -->
                      <li class="user-header">
                        <img src="{{ asset('uploads/user-unnamed.png') }}" class="img-circle" alt="User Image">
                        <p>
                          {{Auth::user()->name}}
                          <small></small>
                        </p>
                      </li>
                      <!-- Menu Footer-->
                      <li class="user-footer">
                        <div class="pull-left">
                          <a href="{{url('login/profile')}}" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                          <a class="btn btn-default btn-flat" href="{{url('logout')}}">{{ __('Logout') }}</a>
                          
                        </div>
                      </li>
                        </ul>
                  </li>
                </ul>
              </div>
            </nav>
        </header>

      
        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
              <!-- Sidebar user panel -->
              <div class="user-panel">
                <div class="pull-left image">
                  <img src="{{ asset('uploads/user-unnamed.png') }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                  <p>{{Auth::user()->name}}</p>

                  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
              </div>
              <!-- sidebar menu: : style can be found in sidebar.less -->
              @if(Auth::user()->role_id == 2)
               <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                 <li>
                    <a href="{{url('login/bookings')}}"><i class="fa fa-list"></i> <span>All Bookings</span></a>
                 </li>
                
                <li>
                 <a href="{{url('login/counsellors/list/listedit',Auth::user()->id)}}">
                    <i class="fa fa-list"></i> <span>Update Listing</span>
                  </a>
                </li>
              </ul>
              @else
              <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="">
                  <a href="{{url('login/dashboard')}}">
                    <i class="fa fa-th"></i> <span>Dashboard</span>

                  </a>
                </li>
                
                <li class="treeview">
                  <a href="">
                    <i class="fa fa-user"></i> <span>Users</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/users')}}">Users</a>
                    </li>
                    <li>
                    <a href="{{url('login/counsellors')}}">Counsellors</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="treeview">
                  <a href="">
                    <i class="fa fa-tasks"></i> <span>CMS</span>
                  </a>

                  <ul class="treeview-menu">
                  <li>
                    <a href="{{url('login/settings')}}">General Settings</a>
                  </li>
                   
                  </ul>

                </li>
                <li class="treeview">
                  <a href="{{url('login/bookings')}}">
                    <i class="fa fa-ticket"></i> <span>Bookings</span>
                  </a>

                  <ul class="treeview-menu">
               
                    <li>
                    <a href="{{url('login/bookings')}}">All Bookings</a>
                    </li>
                    <li>
                    <a href="{{url('login/create-booking')}}">Create Booking</a>
                    </li>
                       
                  </ul>

                </li>
                <li class="">
                  <a href="{{url('login/listings')}}">
                    <i class="fa fa-list"></i> <span>Listings</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/tickets')}}">
                    <i class="fa fa-font-awesome"></i> <span>Tickets</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/send-notification')}}">
                    <i class="fa fa-bell"></i> <span>Notification</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/insurance')}}">
                    <i class="fa fa-hospital-o"></i> <span>Insurance</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/category')}}">
                    <i class="fa fa-hospital-o"></i> <span>Category</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/region')}}">
                    <i class="fa fa-hospital-o"></i> <span>Regions</span>
                  </a>
                </li>
                <li class="">
                  <a href="{{url('login/label')}}">
                    <i class="fa fa-globe"></i> <span>Labels</span>
                  </a>
                </li>

               
              </ul>

            @endif
              
            </section>
        <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                {{ isset($data) ? array_key_exists('title',$data) ? $data['title'] : '' : '' }}
              </h1>
              {{-- Breadcrumbs::render('home') --}}
            </section>

            <!-- Main content -->
            <section class="content">
                @include('admin.layouts.flash-message')
                @yield("content")
            </section>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->    

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.13
            </div>
            <strong>Copyright &copy; 2014-2019 <a href="#">Admin</a>.</strong> All rights reserved.
        </footer>
    </div>

     <!-- Scripts -->
    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('assets/bower_components/fastclick/lib/fastclick.js')}}"></script>
    <script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <!-- Admin App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js"></script>
    <script src="{{ asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('assets/dist/js/adminlte.min.js')}}"></script>
    <script src="{{ asset('assets/js/main.js')}}"></script>

    <script src="{{asset('js/jsRapCalendar.js')}}"></script>
    <script src="{{ asset('js/select2.js')}}"></script>
    @stack('select2')
    <script type="text/javascript">
      $( document ).ready(function(){
            $('.alert').fadeIn('slow', function(){
               $('.alert').delay(4000).fadeOut(); 
            });
        });
      function deleteUser(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/users/destroy')}}",
        type:'post',
        data:{'id':id,'_token':'{{ csrf_token() }}'},
        success: function(path){
        location.reload();
        }
        });
        } else {
        return false;
        }
      }
      function deleteCounsellor(id)
      {
        if (confirm("Are you sure you want to delete?") == true) {
        $.ajax({
        url:"{{url('login/counsellors/destroy')}}",
        type:'post',
        data:{'id':id,'_token':'{{ csrf_token() }}'},
        success: function(path){
        location.reload();
        }
        });
        } else {
        return false;
        }
      }

      </script>
    @yield('footer_scripts')
    
    

</body>
</html>