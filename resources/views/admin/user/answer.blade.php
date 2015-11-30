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
	@if (\Session::has('message'))
	    <div class="alert alert-danger">
		    <button type="button" class="close" data-dismiss="alert">×</button>
		    <strong>{{ \Session::get('message') }}</strong>
	    </div>
	@endif
	@include('partial.notifications')
	<form action="{{ route('admin.user.post.answer', $user->id) }}" method="POST" accept-charset="utf-8">
		{!! csrf_field() !!}
	
		@if (count($answers) > 0)
			@foreach ($answers as $key => $answer)
				@if ($key == 0)
					<?php $start = $answer->id ?>
				@elseif ($key == count($answers) - 1)
					<?php $end = $answer->id ?>
				@endif
				<div class="row">
					<ul>
						<li>Question: {{ $answer->content }}</li>
						<li>Answer: {{ $answer->pivot->result }}</li>
						<br>
						<input type="text" class="js-range-{{ $answer->id }}"  />
						<br>
							<input readonly type="text" name="points[{{ $answer->id}}]" class="js-display-change-{{ $answer->id }} form-control  display" value="{{ $answer->pivot->point }}"></input>	
							<?php $points[] = $answer->pivot->point ?>

				</div>
				<br>
				<hr>
			@endforeach
		@endif
		<div class="row">
			<div class="col-xs-12">
				<button type="submit" class="btn btn-primary col-xs-4">Save</button>
				<a href="{{ route('admin.dashboard') }}" class="btn btn-default col-xs-4 col-xs-offset-1">Go to back!</a>
			</div>
		</div>
	</form>
@stop

@section('script')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
	<script src="{{ asset('js/powerange.min.js') }}"></script>

	<script>
	$(document).ready(function() {
		var range = null;
		$.ajax({
			url: "{{ route('api.user.get.answers', $user->id) }}",
			type: 'GET',
			dataType: 'JSON',
			async: false,
			success: function(result) {
				if (result.status == true)
					range = result.data;
				else alert(result.message);
			}
		});

		if (range == null) return;

		var temp = 0;
		for (var i = range.start; i <= range.end; i++) {
			var elem = document.querySelector('.js-range-'+i);
			var init = new Powerange(elem, {start: range.points[temp]});
			temp++;
		}
		$('[class^=js-range-]').change(function(){
			$(this).parent('ul').children('[class^=js-display-change-]').val($(this).val());
    	});
		
	});
	</script>
@stop