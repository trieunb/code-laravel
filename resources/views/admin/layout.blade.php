<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <!-- Bootstrap CSS -->
        <link href="{{  asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{  asset('css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{  asset('assets/css/OpenSans.css') }}" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/metisMenu.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dataTables.bootstrap.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dataTables.responsive.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/sb-admin-2.css')}}">
        <link href="{{  asset('css/style.css') }}" rel="stylesheet">
        @yield('style')
    </head>
    <body>
        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{URL::to('admin/')}}">Resume Builder</a>
                </div>
                <!-- /.navbar-header -->

                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Admin
                            <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                            </li>
                            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="{{route('admin.logout')}}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li>
                                <a href="{{URL::to('/admin')}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.user.get.index') }}"><i class="fa fa-user fa-fw"></i> User Management</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-file-text fa-fw"></i> Market Place<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route('admin.template.get.create') }}">Create Template</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.template.get.index') }}">Template List</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="glyphicon glyphicon-th-list"></i> Category<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route('admin.category.get.create') }}">Create Category</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.category.get.index') }}">Category List</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-question fa-fw"></i> Question<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route('admin.question.get.create') }}">Create Question</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.question.get.index') }}">Question List</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Report<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level collapse in" aria-expanded="true">
                                    <li>
                                        <a href="{{ route('admin.report.user.month') }}">Report User</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.report.template') }}">Report Template</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">@yield('page-header')</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                @yield('content')
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- jQuery -->
        <script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
        <!-- Bootstrap JavaScript -->
        <script src="{{  asset('js/bootstrap.js') }}"></script>
        <script src="{{  asset('assets/js/metisMenu.js') }}"></script>
        <script src="{{  asset('assets/js/jquery.dataTables.js') }}"></script>
        <script src="{{  asset('assets/js/dataTables.bootstrap.js') }}"></script>
        <script src="{{  asset('assets/js/sb-admin-2.js') }}"></script>
        @yield('script')
    </body>
</html>
