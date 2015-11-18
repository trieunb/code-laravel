@extends('api.app')

@section('content')
	<!-- <div id="myPanel" style=""></div> -->
	
	<div id="content" class="col-md-12" contenteditable="true">
		{!! $content !!}		
	</div> 
	<div class="col-md-12" id="buttons-edit">
		<button class="col-xs-5 btn btn-primary" id="save">Save</button>
		<button class="col-xs-5 col-xs-offset-2 btn btn-default" id="cancel">Cancel</button>	
	</div>  
	
@stop

@section('scripts')
	<script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
	{{-- <script src="{{  asset('js/ckeditor/ckeditor.js') }}"></script> --}}
	<script src="{{  asset('js/nicEdit.js') }}"></script>
	<script>
	$(document).ready(function() {
		$('#save').click(function(e) {
			e.preventDefault();
			var url = window.location.href;
			var token = url.split('=');
			var content = $('#content').html();
			content = content.replace(/\t|\n+/g, '');
			$.ajax({
				url: window.location.href,
				data: {
					token : token,
					content: content
				},
				type: 'POST',
				success : function(result) {
					if (result.status == true) {
						alert('Edit content successfully');
					}
				}
			});
		});
	});
	/*	CKEDITOR.inline('editor',{
            on: {
                instanceReady: function() {
                    this.document.appendStyleSheet( '{{ asset("js/ckeditor/contents.css") }}' );
                   	
               	 	CKEDITOR.instances['editor'].on('focus', function() {                
				       	var width = $(window).width();
	                   	if (width >= 1331) {
	                   		document.getElementsByTagName('div')[1].style.marginTop = "80px";
	                   	} else if (width <= 440) {
	                   		document.getElementsByTagName('div')[1].style.marginTop = "180px";
	                   	} 
			    	});	
			    	CKEDITOR.instances['editor'].on('blur', function() {
			    		document.getElementsByTagName('div')[1].style.marginTop = "0px";
			    	});
			    
                }
            }
        }); 
        
        CKEDITOR.instances.editor.setData("{!! $content !!}");*/

		/*bkLib.onDomLoaded(function() {
          	var myNicEditor = new nicEditor();
          	// new nicEditor({externalCSS : 'asset(css/style.css)'});
          	myNicEditor.setPanel('myPanel');
          	myNicEditor.addInstance('editor');
	     });
		myNicEditor.addEvent('focus', function(e) {
		     alert('abcde');
		  });
		var isFocused, focusedResizing;
		window.tryfix = function() {
			  var inputs = document.getElementsByTagName('input')
			  for (var i = 0; i < inputs.length; i++) {
			    input = inputs[i];
			    input.onfocus = focused;
			    input.onblur = blured;
			  }
			  window.onscroll = scrolled;
		}

		function focused(event) {
		  isFocused = true;
		  scrolled();
		}

		function blured(event) {
			  isFocused = false;
			  var headStyle = document.getElementById('hed').style;
			  if (focusedResizing) {
			    focusedResizing = false;
			    headStyle.position = 'fixed';
			    headStyle.top = 0;
			  }
		}

		function scrolled() {
		  document.title = 'test';
		  var headStyle = document.getElementById('hed').style;
		  if (isFocused) {
		    if (!focusedResizing) {
		      focusedResizing = true;
		      headStyle.position = 'absolute';
		    } 
		    headStyle.top = window.pageYOffset + 'px';
		  }
		}*/
	</script>
@stop