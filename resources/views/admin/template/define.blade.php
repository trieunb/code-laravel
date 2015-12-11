@extends('app.layout')

@section('title')
	Define Template {{ $template->title}}
@stop

@section('page-header')
	Template: {{ $template->title }}
@stop

@section
	<div class="row" id="content">
		{!! $template->content !!}
	</div>
	<div class="row">
		<button class="btn btn-primary col-xs-4 col-xs-offset-1">Save</button>
		<a href="{{ route('admin.template.get.index') }}" class="btn btn-default col-xs-4 col-xs-offset-1">Cancel</a>
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

            selection.toString() !== '';
            var clonedSlection = selection.getRangeAt(0).cloneRange().cloneContents();
		});
		 
	</script>
@endsection