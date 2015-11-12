@extends('api.app')

@section('content')

@include('partial.notifications')
<div class="row">

	<div id="myPanel" style="position:inherit;"></div>
		<div id="content_editor" contenteditable="true">

		</div>
	</div>
</div>
@stop



@section('scripts')
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script>
	/*bkLib.onDomLoaded(function() {
		var myNicEditor = new nicEditor();
		myNicEditor.setPanel('myPanel');
		myNicEditor.addInstance('content_editor');
	});*/
	$(document).ready(function() {
		$('div#content_editor').mouseup(function() {
	        if (getSelectedText() != '' ) {
	        	var str = $(this).html();
	        	var anchorNode = window.getSelection().anchorNode;
	        	if (typeof(anchorNode.tagName) === 'undefined' || anchorNode.tagName == '') {

	        		var content = window.getSelection().getRangeAt(0).cloneContents();
	        		str.replace(/e+/g, 'bb');

	        		console.log(str);
	        		//console.log(anchorNode.parentNode);
	        		//$(anchorNode.parentNode).html('<div>hqh333q</div>');
	        	}else {
	        		// $(anchorNode).prepend('<div>hqh333q</div>');
	        	}
	      
	        	// console.log(anchorNode.tagName);
	        	
	        	/*var createElement = document.createElement('div');
	        	var selection = window.getSelection();
	        	var parentNodeAnchor = window.getSelection().anchorNode.parentNode;
	        	var parentNodeFocus = window.getSelection().focusNode.parentNode;
	        	parentNodeAnchor.innerHTML = '<div>';
	        	parentNodeFocus.innerHTML = '</div>';*/
	        	
	        	// window.getSelection().anchorNode.parentNode.setAttribute('contenteditable', true);
	        
	        	// $('#content_editor').removeAttr('contenteditable');

	        }
	        
	    });
	});
	

    function getSelectedText() {
        if (window.getSelection) {
        	
            return window.getSelection().toString();
        } else if (document.selection) {
            return document.selection.createRange().text;
        }
        return '';
    }
</script>
@stop