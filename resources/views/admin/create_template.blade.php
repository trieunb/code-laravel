@extends('admin.layout')
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
                <textarea name="editor"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('editor', {
        filebrowserBrowseUrl: '/browser/browse.php',
        filebrowserUploadUrl: '/uploader/upload.php'
    });
</script>
@endsection