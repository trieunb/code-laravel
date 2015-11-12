@extends('api.app')

@section('content')

<div class="row">
  
  	<div id="myPanel" style="position:inherit;"></div>
  		<div  contenteditable="true" id="profiles">
  			Contact...
  		</div>
  		<div  contenteditable="true" id="objectives">
  			Objectives...
  		</div>
  		<div  contenteditable="true" id="works">
  			Work...
  		</div>
  		<div  contenteditable="true" id="educations">
  			Education...
  		</div>
  		<div  contenteditable="true" id="references">
  			References...
  		</div>
  		<div  contenteditable="true" id="photos">
  			Photo...
  		</div>
</div>
@stop



@section('scripts')
{{-- <script src="{{ asset('ckeditor/ckeditor.js') }}"></script> --}}
<script src="{{ asset('js/nicEdit.js') }}"></script>
<script>
	bkLib.onDomLoaded(function() {
		var myNicEditor = new nicEditor();
		myNicEditor.setPanel('myPanel');
		myNicEditor.addInstance('profiles');
		myNicEditor.addInstance('objectives');
		myNicEditor.addInstance('works');
		myNicEditor.addInstance('educations');
		myNicEditor.addInstance('references');
		myNicEditor.addInstance('photos');
	});
	
</script>
@stop