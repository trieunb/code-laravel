@extends('api.app')
@section('title', $template->title)
@section('css')
<style type="text/css">
  .border-section{
    border: 2px solid #bcbcbc;
  }
    .mobile{
            overflow: hidden;
        }
</style>
@endsection
@section('content')
    <div id="content">
        {!! $content !!}
    </div>
@stop
@section('scripts')
<script>
	$(document).ready(function() {
		$('div').removeAttr('contenteditable');
		$('table').removeAttr('width');
		$('table').css('width', 'auto');
		$('table').css('margin', 'auto');
	});
</script>
@endsection