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
    #myTab li:last-child {
        width: 300px !important;
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

    <li id="register" @if(is_null($question_id)) class="active" @endif><a data-toggle="pill" href="#chart_month">Month</a></li>
    <li id="gender"><a data-toggle="pill" href="#chart_gender">Gender</a></li>
    <li id="age"><a data-toggle="pill" href="#chart_age">Age</a></li>
    <li><a data-toggle="pill" href="#chart_region">Region</a></li>
    <li @unless (is_null($question_id)) class="active" @endunless id="skill"><a data-toggle="pill" href="#chart_skill">User skill</a></li>
    <li id="option" class="pull-right">
        {!! Form::select('question', $list_questions, $question_id, ['class' => 'form-control', 'id' => 'questions', 'placeholder' => 'Choose Question']) !!}
    </li>

</ul>
<div class="tab-content">
    <div id="chart_month" class="tab-pane fade <?php if(is_null($question_id)) echo "in active"; ?>">
    <br>
      
        <div class="row">
            <div class="col-xs-4 pull-right">
                 {!! Form::selectYear('question', 2000, 2020, is_null($year) ?  \Carbon\Carbon::now()->year : $year, ['class' => 'form-control', 'id' => 'year']) !!}
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
        @if(count($chart_gender) == 0)
            <h3>Not Found Data</h3>
        @endif
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
        @if(count($chart_age) == 0)
            <h3>Not Found Data</h3>
        @endif
        <div class="title-char text-center"><h3>Registered users by age group</h3></div>
    </div>
    <div id="chart_region" class="tab-pane fade">
        {!! $chart_region !!}
        <div class="title-char text-center"><h3>Registered users by region</h3></div>
    </div>
    <div id="chart_skill" class="tab-pane fade <?php if( ! is_null($question_id)) echo 'in active'; ?>">

        <div class="col-xs-4">
            
        </div>
        <br>

           <div class="title-char text-center"><h3>Registered users by test skill</h3></div>
                {!! $chart_skill !!}

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

    $('#option').hide();

    $('#questions option:first-child').attr('disabled', true);

    $('#questions').change(function() {
        window.location.href = '/admin/report/user?question_id='+$(this).find('option:selected').val();
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

    var register_report;
     if ( $('#myTab li#skill').hasClass('active')) {
        $('#chart_month').removeClass('active');
        $('#option').show();
    } else {
        register_report = new Chart(ctx).Line(chart, {
        bezierCurve : false,
        scaleGridLineColor : "rgba(0,0,0,.05)",
        responsive: true
    });
    }
    $('#myTab li').click(function() {
        if ($(this).attr('id') == 'option') return;

        if ($(this).attr('id') == 'skill') { 
             $('#option').show();
        }else $('#option').hide();
    });

    var line_chart_options = {
        scaleGridLineColor : "rgba(0,0,0,.05)",
        responsive: true
    };


    var ctx_1 = document.getElementById('report-gender').getContext('2d');
    var responseGender =   {!! json_encode($chart_gender) !!};

    var data_gender = [
        {
            value: responseGender.Male,
            color:"#46BFBD",
            highlight: "#5AD3D1",
            label:  'Male'
        },
        {
            value: responseGender.Female,
            color:"#F7464A",
            highlight: "#FF5A5E",
           
            label:  'Female'
        },
        {
            value: responseGender.Other,
            color:"#FDB45C",
            highlight: "#FFC870",
            label: 'Other'
        }
    ];
    var myPieChart;
     var responseAge = {!! json_encode($chart_age) !!};

    var data_age = new Array;
    $.each(responseAge, function(key, val) {
        var object = {};
        if (key == 'Under 20 olds') {
            object.color = "#FDB45C";
            object.highlight = "#FFC870";
            object.label = 'Under 20 olds';
        } else if (key == '20-30 olds') {
            object.color = "#F7464A";
            object.highlight = "#FF5A5E";
            object.label = '20-30 olds';
        } else {
            object.color = "#46BFBD";
            object.highlight = "#5AD3D1";
            object.label = 'Above 30 olds';
        }
        object.value = val;
        data_age.push(object);
        
    });

    var ctx_2 =  document.getElementById('report-age').getContext('2d');
    var ageChart;

    $('#register').on('shown.bs.tab', function (e) {
        if (typeof(myPieChart) != 'undefined')
            myPieChart.destroy();
        console.log(chart);
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
        if (typeof(register_report) != 'undefined')
            register_report.destroy();
        myPieChart = new Chart(ctx_1).Pie(data_gender, {responsive: true});
    });

    $('#age').on('shown.bs.tab', function (e) {
        if (typeof(register_report) != 'undefined')
            register_report.destroy();
        if (typeof(myPieChart) != 'undefined') {
            myPieChart.destroy();
        }
        ageChart = new Chart(ctx_2).Pie(data_age, {responsive: true});
    });

});
</script>
@endsection