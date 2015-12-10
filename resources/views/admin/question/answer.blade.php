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


@if (count($answers) > 0)
@foreach ($answers as $key => $answer)
@if ($key == 0)
<?php $start = $answer->id ?>
@elseif ($key == count($answers) - 1)
<?php $end = $answer->id ?>
@endif
<div class="row">
	<ul>
		<li>Question: {{ $answer->content }} - Point:  <span class="badge">{{ $answer->pivot->point }}</span></li>
		<br>
		
	</ul>
</div>
<br>
<hr>
@endforeach
@endif
<div class="row">
	<div class="col-xs-12">
		<a href="{{ route('admin.question.get.index') }}" class="btn btn-primary col-xs-6 col-xs-offset-3">Go to back!</a>
	</div>
</div>
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