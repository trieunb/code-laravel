@extends('admin.layout')

@section('title')
    Report Template
@stop

@section('page-header')
    Report Template
@stop
@section('content')
<div class="bs-example">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#month">Month</a></li>
        <li><a href="#gender">Gender</a></li>
    </ul>
    <div class="tab-content">
        <div id="month" class="tab-pane fade in active">
            {!! $chart_month !!}
        </div>
        <div id="gender" class="tab-pane fade">
            {!! $chart_gender !!}
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){ 
            $("#myTab a").click(function(e){
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
@endsection