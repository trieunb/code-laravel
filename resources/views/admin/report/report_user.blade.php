@extends('admin.layout')

@section('title')
    Report User
@stop

@section('page-header')
    Report User
@stop
@section('content')

<ul class="nav nav-pills" id="myTab">
    <li class="active"><a data-toggle="pill" href="#chart_month">Month</a></li>
    <li><a data-toggle="pill" href="#chart_gender">Gender</a></li>
</ul>
<div class="tab-content">
    <div id="chart_month" class="tab-pane fade in active">
        <canvas id="report-month" style="width:100%; height:300px"></canvas>
        <div class="title-char text-center"><h3>Registered users</h3></div>
    </div>
    <div id="chart_gender" class="tab-pane fade">
        <canvas id="report-gender" style="width:100%; height:300px"></canvas>
        <div class="title-char text-center"><h3>Registered users</h3></div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>

<script type="text/javascript">
    (function() {
         var ctx = document.getElementById('report-month').getContext('2d');
         var chart = {
            labels: {!! json_encode($lables) !!},
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: {{json_encode($count_arr)}}
                }
            ]
         };

         new Chart(ctx).Line(chart, {
            bezierCurve : false
         });
    })();
</script>

@endsection