@extends('frontend.app')

@section('content')
	<h3>Template</h3>
	<textarea id="editor" html="true"></textarea>
@stop

@section('script_files')
	<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
@stop
@section('scripts')
	<script>
        // CKEDITOR.replace('editor');
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.inline('editor',{
		    on: {
		        instanceReady: function() {
		            this.document.appendStyleSheet( '{{ asset("js/ckeditor/contents.css") }}' );
		        }
		    }
		} );
        $.ajax({
            url : '/{{ $template->source_convert }}',
            type : 'GET',
            success: function(result) { 
                CKEDITOR.instances.editor.setData(result);
            }
        })
        </script>
@stop