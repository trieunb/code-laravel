@extends('admin.layout')

@section('title')
	Define Template {{ $template->title}}
@stop

@section('page-header')
	Template: {{ $template->title }}
@stop

@section('content')
	<div class="row" id="content">
		{!! $template->content !!}
	</div>
	<div class="row">
		<button class="btn btn-primary col-xs-4 col-xs-offset-1">Save</button>
		<a href="{{ route('admin.template.get.index') }}" class="btn btn-default col-xs-4 col-xs-offset-1">Cancel</a>
	</div>
	<div class="row"  id="section" style="display: none;position:absolute; z-index:99999999999999999;">

			<select class="form-control">
				<option disabled selected>Select Section</option>
				<option value="activitie">Activitie</option>
				<option value="address">Address</option>
			</select>
	</div>
@stop

@section('script')
	<script type="text/javascript" src="{{asset('assets/js/edit_section_temp.js')}}"></script>
	<script>
 	
		$('#content').mouseup(function(e) {
			e.preventDefault();
			if (window.getSelection) {
                selection = window.getSelection();
            } else if (document.selection) {
                selection = document.selection.createRange();
            }
            $('div[contenteditable="true"]').removeClass('highlight');

            if (selection.toString() == ''){
            	$('#section').hide();
            	return;
         	}
            var top = $(document).scrollTop() + 30;
            $('#section').css({top: top});
            $('#section').show();
            var clonedSlection = selection.getRangeAt(0).cloneRange().cloneContents();
            var div = document.createElement('div');
            div.appendChild(clonedSlection);
            var search = $(div).html();
           console.log($('#content').html().indexOf(search));
           console.log(div.innerHTML);
            $('#section').change(function(e) {
            	e.preventDefault();
            	
            	// console.log($('#content').html().indexOf(search));
            	div.className = $('#section option:selected').val();
            	if ($('#content').html().indexOf(search) != -1) {
            		console.log($('#content').html().indexOf(search));
            		var replace = $('#content').html().replace(new RegExp(search), div.outerHTML);
            		$('#content').html(replace);	
            		return;
            	}
            	
            	
            });
            
		});
		/*$('#content').mousedown(function(e) {
			console.log('test');
			$('#section').hide();
		});*/
		
	</script>
@endsection