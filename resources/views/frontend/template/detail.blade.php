@extends('frontend.app')

@section('content')
<div class="fw box-title border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
              <h4>Click and Create Your Amazing Resume</h4>
            </div>
            <div class="col-md-6 text-right">
              <span>Price: Free</span>
              <button class="btn-trans semi-bold">Read more</button>
              <button class="btn-trans semi-bold">Edit</button>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row wrapper detail">
        <h2 class="f-w_n">Resume No. 1</h2>
        <div class="fw w_bg"  >
       
          <div id="editor" contenteditable="true"></div>
        </div>

    </div>
</div>

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
                var count = (result.match(/img/g) || []).length;
                if (count > 0) {
                    $('main .detail .fw').height(count*1100);
                }
            }
        });
       
        
        </script>
@stop