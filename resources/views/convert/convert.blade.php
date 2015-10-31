<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Convert File</title>
        <!-- Bootstrap CSS -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    </head>
    <body>
        <h3>Template : {{ $template->source_convert }}</h3>
                <textarea id="editor" contentEditable="true"></textarea>
        
        <!-- jQuery -->
        <script src="//code.jquery.com/jquery.js"></script>
        <!-- Bootstrap JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
        <script>
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.inline( 'editor' );
        $.ajax({
            url : '/{{ $template->source_convert }}',
            type : 'GET',
            success: function(result) { 
                CKEDITOR.instances.editor.setData(result);
            }
        })
        </script>
    </body>
</html>