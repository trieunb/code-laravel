@extends('admin.layout')

@section('title')
    Report
@stop

@section('page-header')
    Report Manager
@stop
@section('content')
    <div id="perf_div"></div>
    <? echo Lava::render('ColumnChart', 'Finances', 'perf_div') ?>
    <!-- @columnchart('columnchart', true) -->
@endsection