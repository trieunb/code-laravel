@extends('admin.layout')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/froala_editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/froala_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/code_view.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/colors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/emoticons.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/image_manager.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/image.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/line_breaker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/table.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/char_counter.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/video.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/fullscreen.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('froala_editor/css/plugins/file.min.css') }}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('admin.create') }}" id="create-form" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" placeholder="Price">
            </div>
            <div class="form-group">
                <div id="edit"></div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')

<script src="{{ asset('froala_editor/js/froala_editor.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/image.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/image_manager.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/fullscreen.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/line_breaker.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/inline_style.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/font_size.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/font_family.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/file.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/entities.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/emoticons.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/colors.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/code_view.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/link.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/lists.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/paragraph_format.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/paragraph_style.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/quote.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/save.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/table.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/url.min.js') }}"></script>
<script src="{{ asset('froala_editor/js/plugins/video.min.js') }}"></script>
<script>
    $(function() {
        $('#edit').froalaEditor({
            inlineMode: true
        })

        $('#edit div:last-child').remove();
    });
</script>
@endsection