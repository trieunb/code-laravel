@extends('api.app')

@section('content')

@include('partial.notifications')
<div class="row">
{{-- 
	<div id="myPanel" style="position:inherit;"></div> --}}
		<div id="content_editor" contenteditable="true">
			Header
		</div>
	{{-- </div> --}}
</div>
@stop



@section('scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
	/*bkLib.onDomLoaded(function() {
		var myNicEditor = new nicEditor();
		myNicEditor.setPanel('myPanel');
		myNicEditor.addInstance('content_editor');
	});*/
	
</script>
@stop