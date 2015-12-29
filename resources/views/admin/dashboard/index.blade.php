@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$count_user}}</div>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.user.get.index') }}">
                <div class="panel-footer">
                    <span class="pull-left">Registered Users</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$count_template}}</div>
                    </div>
                </div>
            </div>
            <a href="{{route('admin.template.get.index')}}">
                <div class="panel-footer">
                    <span class="pull-left">Templates created</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$count_resume}}</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Templates Bought</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$count_job}}</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Jobs</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Recent Registered Users
            </div>
            <div class="panel-body">
                <ul>
                    @foreach($last_users as $key => $user)
                        <li><a href="{{route('admin.user.get.detail', $user->id)}}">{{$user->present()->name() . ' - ' . $user->created_at->format('Y-m-d')}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Recent Templates Bought
            </div>
            <div class="panel-body">
                @if (count($last_resume) <= 0)
                    <span>No data available in table</span>
                @else
                <ul>
                    @foreach($last_resume as $key => $resume)
                        <li><a href="{{route('admin.resume.detail', $resume->id)}}">{{$resume->title . ' - ' . $resume->created_at->format('Y-m-d')}}</a></li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection