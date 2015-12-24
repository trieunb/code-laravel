@extends('admin.layout')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">
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
    /* bootstrap hack: fix content width inside hidden tabs */
    .tab-content > .tab-pane,
    .pill-content > .pill-pane {
        display: block;    /* undo display:none          */
        height: 0;          /* height:0 is also invisible */ 
        overflow-y: hidden; /* no-overflow                */
    }
    .tab-content > .active,
    .pill-content > .active {
        height: auto;       /* let the content decide it  */
        overflow:hidden;
    } /* bootstrap hack end */
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

    <li id="register" @if(is_null($question_id)) class="active" @endif><a data-toggle="pill" href="#chart_month">Registered users</a></li>
    <li id="gender"><a data-toggle="pill" href="#chart_gender">Gender</a></li>
    <li id="age"><a data-toggle="pill" href="#chart_age">Age Group</a></li>
    <li><a data-toggle="pill" href="#chart_region">Region</a></li>
    <li @unless (is_null($question_id)) class="active" @endunless id="skill"><a data-toggle="pill" href="#chart_skill">Skills test</a></li>
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
       
        @if (count($count_arr) == 0)
            <h3 class="text-center">Not Found Data </h3>
        @else 
            <canvas id="report-month" style="width:100%; height:300px"></canvas>
        @endif
    </div>
    <div id="chart_gender" class="tab-pane fade">
       {!! $chart_gender !!}
    </div>
    <div id="chart_age" class="tab-pane fade">
        {!! $chart_age !!}
    </div>
    <div id="chart_region" class="tab-pane fade">
        {!! $chart_region !!}
        <div class="title-char text-center"><h3>Registered users by region</h3></div>
    </div>
    <div id="chart_skill" class="tab-pane fade <?php if( ! is_null($question_id)) echo 'in active'; ?>">
        {!! $chart_skill !!}
    </div>

</div>
@endsection
@section('script')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
  $(function() {
    'use strict';

    $('#myTab li a').on('shown.bs.tab', function(e) {

        localStorage.setItem('lastTab', $(this).attr('href'));
    });

    var lastTab = localStorage.getItem('lastTab');

    if (lastTab) {
        $('[href="'+lastTab+'"').tab('show');

    }

    $('#year').select2({
        placeholder: "Select a Year"
    });

    $('#questions').select2();

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

    $('#register').on('shown.bs.tab', function (e) {
        register_report = new Chart(ctx).Line(chart, {
            bezierCurve : false,
            scaleGridLineColor : "rgba(0,0,0,.05)",
            responsive: true
        });
    });
});
</script>
@endsection