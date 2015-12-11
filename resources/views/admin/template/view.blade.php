@extends('admin.layout')

@section('title')
	View Template
@stop

@section('page-header')
	Template: {{ $title }}
@stop

@section('content')
	{!! $content !!}
@endsection