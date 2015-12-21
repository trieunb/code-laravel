@extends('admin.layout')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">
<style>
    .ui-datepicker-calendar {
        display: none;
    }
    #ui-datepicker-div {
        width: 24%;
    }
    @media screen and (max-width: 640px) {
        #ui-datepicker-div {
            width: 29%;
        }
    }
    @media screen and (max-width: 440px) {
        #ui-datepicker-div {
            width: 29%;
        }
    }
    #note-report {
        margin-left: -25px;
    }
</style>
@stop

@section('title')
Report User
@stop

@section('page-header')
Report User
@stop
@section('content')


<ul class="nav nav-pills" id="myTab">

    <li id="register" class="active"><a data-toggle="pill" href="#chart_month">Month</a></li>
    <li id="gender"><a data-toggle="pill" href="#chart_gender">Gender</a></li>
    <li id="age"><a data-toggle="pill" href="#chart_age">Age</a></li>
    <li><a data-toggle="pill" href="#chart_textskill">Region</a></li>
</ul>
<div class="tab-content">
    <div id="chart_month" class="tab-pane fade in active">
    <br>
        <div class="row">
            <ul id="note-report">
                <li><span id="template"></span> Template created monthly</li>
                <li><span id="bought"></span> Template bougth monthly</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-xs-4 pull-right">
                 {!! Form::selectYear('year', 2000, 2020, is_null($year) ?  \Carbon\Carbon::now()->year : $year, ['class' => 'form-control', 'id' => 'year']) !!}
            </div>
        </div>
        <canvas id="report-month" style="width:100%; height:300px"></canvas>
        @if (count($count_arr) == 0)


        <h3 class="text-center">Not Found Data </h3>
        @endif
        <div class="title-char text-center"><h3>Registered users</h3></div>
    </div>
    <div id="chart_gender" class="tab-pane fade">
        <div class="row">
            <ul id="note-report" class="note-report-user note-age">
                <li><span></span> Male</li>
                <li><span></span> Female</li>
                <li><span></span> Other</li>
            </ul>
        </div>
        <canvas id="report-gender" style="width:100%; height:300px"></canvas>
        <div class="title-char text-center"><h3>Registered users by gender</h3></div>
    </div>
    <div id="chart_age" class="tab-pane fade">
        <div class="row">
            <ul id="note-report" class="note-report-user">
                <li><span></span> Under 20 olds</li>
                <li><span></span> 20-30 olds</li>
                <li><span></span> Above 30 olds</li>
            </ul>
        </div>
        <canvas id="report-age" style="width:100%; height:300px"></canvas>
        <div class="title-char text-center"><h3>Registered users by age group</h3></div>
    </div>
    <div id="chart_region" class="tab-pane fade">
        {!! $chart_region !!}
        <div class="title-char text-center"><h3>Registered users by region</h3></div>
    </div>
    <div id="chart_region" class="tab-pane fade">
        
        <div class="title-char text-center"><h3>Registered users by test skill</h3></div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
<script type="text/javascript">
  $(function() {
    'use strict';

    $('#year').change(function() {
        var year = $(this).find('option:selected').val();
        window.location.href = '/admin/report/user?year='+year;
    });

    var options = {responsive: true};
    var ctx = document.getElementById('report-month').getContext('2d');
    var chart = {
        labels: {!! json_encode($lables) !!},
        datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgb(79, 110, 175)",
            pointColor: "rgb(79, 110, 175)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: {{json_encode($count_arr)}}
        }
        ]
    };
    var register_report = new Chart(ctx).Line(chart, {
        bezierCurve : false,
        scaleGridLineColor : "rgba(0,0,0,.05)",
        responsive: true
    });

    


    var line_chart_options = {
        scaleGridLineColor : "rgba(0,0,0,.05)",
        responsive: true
    };
    var data = {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.2)",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.2)",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [28, 48, 40, 19, 86, 27, 90]
        }
        ]
    };

    var ctx_1 = document.getElementById('report-gender').getContext('2d');
    var responseGender =   {!! $chart_gender !!};

    var data_gender = [
    {
        value: responseGender[0].count,
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: responseGender[0].gender_user
    },
    {
        value: responseGender[1].count,
        color:"#46BFBD",
        highlight: "#5AD3D1",
        label: responseGender[1].gender_user
    },
    {
        value: responseGender[2].count,
        color:"#FDB45C",
        highlight: "#FFC870",
        label: responseGender[2].gender_user
    }
    ];
    var myPieChart;

    var responseAge = {!! $chart_age !!};

    var data_age = [
        {
            value: responseAge[0].count,
            color:"#F7464A",
            highlight: "#FF5A5E",
            label: responseAge[0].group_age
        },
        {
            value: responseAge[1].count,
            color:"#46BFBD",
            highlight: "#5AD3D1",
            label: responseAge[1].group_age
        },
        {
            value: responseAge[2].count,
            color:"#FDB45C",
            highlight: "#FFC870",
            label: responseAge[2].group_age
        }
    ];

    var ctx_2 =  document.getElementById('report-age').getContext('2d');
    var ageChart;

    $('#register').on('shown.bs.tab', function (e) {
        myPieChart.destroy();
        register_report = new Chart(ctx).Line(chart, {
            bezierCurve : false,
            scaleGridLineColor : "rgba(0,0,0,.05)",
            responsive: true
        });
    });

    $('#gender').on('shown.bs.tab', function (e) {
        if (typeof(ageChart) != 'undefined') {
            ageChart.destroy();
        }
        register_report.destroy();
        myPieChart = new Chart(ctx_1).Pie(data_gender, {responsive: true});
    });
    $('#age').on('shown.bs.tab', function (e) {
        register_report.destroy();
        if (typeof(myPieChart) != 'undefined') {
            myPieChart.destroy();
        }
        ageChart = new Chart(ctx_2).Pie(data_age, {responsive: true});
    });

});
</script>
@endsection