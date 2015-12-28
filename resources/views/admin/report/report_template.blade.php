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
</style>
@stop

@section('title')
    Report Template
@stop

@section('page-header')
Report Template
    
@stop
@section('content')
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

<div class="tab-content">
    <div id="chart_month" class="tab-pane fade in active">
        <canvas id="report-month" style="width:100%; height:300px"></canvas>
        
    </div>
    <div id="chart_gender" class="tab-pane fade">
        <canvas id="report-bougth" style="width:100%; height:300px"></canvas>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){ 
            var options = {responsive: true};

            var ctx = document.getElementById('report-month').getContext('2d');
            var data_template = {!! json_encode($chart_month) !!};
            var tmp = new Array;
            $.each(data_template, function(key, val) {
                tmp.push(val);
            });
            var data_bougth = {!! json_encode($bought_report) !!};
            var tmp2 = new Array;
            $.each(data_bougth, function(key, val) {
                tmp2.push(val);
            });

            var data = {
                labels: ["January", "February", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"
                ],
                datasets: [
                    {
                        label: "My Second dataset",
                        fillColor: "rgba(151,187,205,0.5)",
                        strokeColor: "rgba(151,187,205,0.8)",
                        highlightFill: "rgba(151,187,205,0.75)",
                        highlightStroke: "rgba(151,187,205,1)",
                        data: tmp
                    },
                    {
                        label: "My First dataset",
                        fillColor: "rgba(220,220,220,0.5)",
                        strokeColor: "rgba(220,220,220,0.8)",
                        highlightFill: "rgba(220,220,220,0.75)",
                        highlightStroke: "rgba(220,220,220,1)",
                        data: tmp2    
                    }
                ]
            };
            var myBarChart = new Chart(ctx).Bar(data, options);
        });
        $('#year').select2({
            placeholder: "Select a Year"
        });
        $('#year').change(function() {
            var year = $(this).find('option:selected').val();
            window.location.href = '/admin/report/template?year='+year;
        });
    </script>
@endsection