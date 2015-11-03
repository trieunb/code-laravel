@extends('frontend.app')

@section('content')
	<div class="container">
		<h1>Create Template</h1>
		<form action="{{ route('frontend.template.post.create') }}" method="post" accept-charset="utf-8">
			<input type="hidden" name="token" value="{{ $token }}">
			<button type="submit">Create</button>
		</form>	
	</div>
@stop