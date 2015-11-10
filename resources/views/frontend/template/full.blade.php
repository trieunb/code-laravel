<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Edit</title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
</head>
<style type="text/css">
	body{
		/*width: 21cm;*/
	}
	#editor {
		width: 21cm;
		margin: 0 auto;
	}
	.container{
		width: 100%;
	}
	#myPanel{
		z-index: 9999;
	}
	@page{
		size: A4;
	}
</style>
<body>
	<div id="myPanel" style=""></div>
	<div id="editor" contenteditable="true" style="with:100px!important;" >{!! $content !!}</div>    
	<script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
	{{-- <script src="{{  asset('js/ckeditor/ckeditor.js') }}"></script> --}}
	<script src="{{  asset('js/nicEdit.js') }}"></script>
	<script>
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

		bkLib.onDomLoaded(function() {
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
		    // window.innerHeight wrong
		    //var footTop = window.pageYOffset + window.innerHeight - foot.offsetHeight;
		    //footStyle.bottom = (document.body.offsetHeight - footTop) + 'px';
		  }
		}
	</script>
</body>
</html>