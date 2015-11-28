@extends('admin.layout')

@section('title')
	Answer for User
@stop

@section('style')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/powerange.min.css') }}">
@stop

@section('page-header')
	Answer for User: {{ $user->present()->name() }}
@stop

@section('content')
	
	@if (count($answers) > 0)
		@foreach ($answers as $key => $answer)
			@if ($key == 0)
				<?php $start = $answer->id ?>
			@elseif ($key == count($answers) - 1)
				<?php $end = $answer->id ?>
			@endif
			<div>
				<ul>
					<li>Question: {{ $answer->content }}</li>
					<li>Answer: {{ $answer->pivot->result }}</li>
					<br>
					<input type="text" class="js-range-{{ $answer->id }}"  />
					<br>
					<input name="point[$answer->id]" class="js-display-change-{{ $answer->id }}"></input>	
			</div>
			<br>
			<hr>
		@endforeach
	@endif
@stop

@section('script')
	<script src="{{ asset('js/powerange.min.js') }}"></script>

	<script>
	$(document).ready(function() {
		var start = "{{ $start }}";
		var end = "{{ $end }}";
		for (var i = start; i <= end; i++) {
			var elem = document.querySelector('.js-range-'+i);
			var init = new Powerange(elem, {min:0, max:100});

		}
		$('[class^=js-range-]').change(function(){
			$(this).parent('ul').children('[class^=js-display-change-]').val($(this).val());
    	});
		
	});
		
		
		
	</script>
@stop