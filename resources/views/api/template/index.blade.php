@extends('api.app')

@section('css')
	@if(isset($render))
		<style>
			body{font-family: 'dejavu sans';}
			p.full-name {
			    font-size: 28;
			    font-weight: 600;
			}
		</style>
	@endif
@stop

@section('content')
	<div class="row">
		{!! $content !!}
	</div>
@stop