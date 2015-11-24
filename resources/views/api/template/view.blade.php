@extends('api.app')
@section('title', $template->title)
@section('content')
    <div class="row">
        <button type="submit" class="btn btn-primary pull-right" id="edit-template">Edit</button>
    </div>
    <div id="content" contenteditable="true">
        {!! $content !!}
    </div>
@stop
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#edit-template').click(function(e) {
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
                  alert('Edit template successfully');
                }
              }
            });
          });
        });
    </script>
@endsection