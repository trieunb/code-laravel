@extends('admin.layout')

@section('title')
    Report Template
@stop

@section('page-header')
Report Template
    
@stop
@section('content')
<ul class="nav nav-pills" id="myTab">
    <li class="active"><a data-toggle="pill" href="#chart_month">Month</a></li>
    <li><a data-toggle="pill" href="#chart_gender">Gender</a></li>
</ul>
<div class="tab-content">
    <div id="chart_month" class="tab-pane fade in active">
        {!! $chart_month !!}
    </div>
    <div id="chart_gender" class="tab-pane fade">
        {!! $chart_gender !!}
    </div>
</div>
<!-- {!! $chart_month !!}
{!! $chart_gender !!} -->
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){ 
            $(".nav-pills a").click(function(e){
            e.preventDefault();
            $(this).tab('show');
        });
        });
    </script>
@endsection