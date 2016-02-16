@extends('admin.layout')

@section('title')
	Files 
@stop

@section('content')

	@foreach ($images as $image)
		<a href="{{ url('uploads/template/'.$image['basename']) }}"><img src="{{ url('uploads/template/'.$image['basename']) }}" alt=""/></a>
	@endforeach
@stop

@section('script')
<script type="text/javascript">
	$('a[href]').on('click', function(e){
	window.opener.CKEDITOR.tools.callFunction(<?php echo $test; ?>,$(this).find('img').prop('src'))
});
</script>
@endsection