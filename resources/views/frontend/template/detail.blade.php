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
              <h1>Template</h1>
                <div id="user" contenteditable="true">
                    {!! $template->template['user'] !!}
                </div>    
                <hr>
                <div class="user_education" contenteditable="true">{!! $template->template['user_education'] !!}</div>    
                <div class="user_work_histories" contenteditable="true">{!! $template->template['user_work_histories'] !!}</div>    
                <div class="user_skills" contenteditable="true">{!! $template->template['user_skills'] !!}</div>    
                <div class="user_skills" contenteditable="true">{!! $template->template['user_skills'] !!}</div>    
                <div class="references" contenteditable="true">{!! $template->template['references'] !!}</div>    
                <div class="objectives" contenteditable="true">{!! $template->template['objectives'] !!}</div>    
                <button id="btn-save" type="submit" class="btn btn-primary">Save</button>
            
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
        $('#btn-save').click(function(e) {
            e.preventDefault();
            $.each($('.user_skills'), function(key, value) {
              console.log($(value).html());
              console.log('ok');
            });
           
        });
      /*  CKEDITOR.inline('editor',{
            on: {
                instanceReady: function() {
                    this.document.appendStyleSheet( '{{ asset("js/ckeditor/contents.css") }}' );
                }
            }
        } ); 

        CKEDITOR.inline('editor2',{
            on: {
                instanceReady: function() {
                    this.document.appendStyleSheet( '{{ asset("js/ckeditor/contents.css") }}' );
                }
            }
        } ); 
        
            CKEDITOR.instances.editor.setData('<b>abcccccccccc</b><h1>Hello</h1>');
            CKEDITOR.instances.editor2.setData('1');
     */
       /* $.ajax({
            url : '/{{ $template->source_convert }}',
            type : 'GET',
            success: function(result) { 
           
                CKEDITOR.instances.editor.setData(result);
                var count = (result.match(/img/g) || []).length;
                if (count > 0) {
                    $('main .detail .fw').height(count*1100);
                }
            }
        });*/


       /* var query = window.location.search.substring(1);
        var token = '';
        var params = query.split('&');
        for (var i = 0; i < params.length; i++) {
            var pair = params[i].split('=');
            if (pair[0] == 'token') {
                token = pair[1];
            }
        }*/
       
        /*$.ajax({
            url : "{{ route('frontend.template.post.edit') }}",
            type : 'POST',
            data : {
                token: token,
                data: CKEDITOR.instances.editor.getData() 
            },
            dataType: 'JSON',
            success: function(result) {
                console.log(result); 
            } 
        });*/
    </script>
@stop