@extends('user.layout')

@section('title')
	View Template
@stop

@section('page-header')
	Template: {{ $title }}
@stop

@section('content')
	{!! $content !!}
@endsection

@section('script')
	<script>
		$(document).ready(function() {
			$('div').removeAttr('contenteditable');
		});
	</script>
	
@endsection