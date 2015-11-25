@extends('api.app')

@section('content')
	<!-- <div id="myPanel" style=""></div> -->
	<div id="loading">
		<img class="img-responsive" src="{{ asset('images/loading.gif') }}" alt="">
	</div>
	<div class="row">
		<form id="upload">
			<div id="content" class="col-md-12" contenteditable="true">
				<input type="file" id="file" style="display: none;">
			  	@if ($section != 'availability')
		  			{!! $content !!}
	  			@else
	  				{!! Form::select('availability', $setting, $template->user->status, ['class' => 'form-control']) !!}
			  	@endif
			  			
			  </div> 
		</form>
	</div>
	<div class="col-md-12" id="buttons-edit">
		<button class="col-xs-4 btn btn-primary" id="save">Save</button>
		<button class="col-xs-4 btn btn-primary" id="apply">Apply from profile</button>
	</div>  

@stop

@section('scripts')
	<script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
	{{-- <script src="{{  asset('js/ckeditor/ckeditor.js') }}"></script> --}}
	<script src="{{  asset('js/nicEdit.js') }}"></script>
	<script>
	$(document).ready(function() {

		var isBusy = false;
		$('img').click(function(e) {
			e.preventDefault();
			$('#file').trigger('click');
		});
		$('#save').click(function(e) {
			
			e.preventDefault();
			if (isBusy) return;
			isBusy = true;
			$("#loading").show();
			var url = window.location.href;
			var token = url.split('=');

			var content = $('#content select').length == 1 ? $('select option:selected').val()
				: $('#content').html();

			content = content.replace(/\t|\n+/g, '');

			if ($('#content img').length == 1) {
				var form = $('#upload')[0];
				var file = $('#file').prop('files')[0];
				var data = new FormData(data);
				data.append('token', token[1]);
				data.append('avatar', file);
				$.ajax({
					url: " {{ route('api.template.post.edit.photo', $template->id) }}",
					type: 'POST',
					cache: false,
					contentType: false,
					processData: false,
					data : data,
					success: function(result) {
						if (result.status_code == 200) {
							$('img').attr('src', result.data)	;
						}
						
						$("#loading").hide();
					}
				}).always(function() {
					isBusy = false;
				});
			} else {
				$.ajax({
					url: url,
					data: {
						token : token[1],
						content: content
					},
					type: 'POST',
					success : function(result) {
						$("#loading").hide();
					}
				}).always(function() {
					isBusy = false;
				});
			}
		});
		$('#apply').click(function(e) {
			e.preventDefault();
			var url = window.location.href;
			var token = url.split('=');
			if (isBusy) return;
			$("#loading").show();
			isBusy = true;
			$.ajax({
				url : '/api/template/apply/{{ $template->id }}/{{ $section}}?token='+token[1],
				type : 'GET',
				success: function(result) {
					$("#loading").hide();
					if (result.status_code == 200) {
						if ($('#content select').length == 1) {
							$('#content select option[value = '+result.data+']').attr('selected');

							console.log(result.data);
						} else $('#content').html(result.data);
					}
				}
			}).always(function() {
				isBusy = false;
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