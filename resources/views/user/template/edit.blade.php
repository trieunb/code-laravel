@extends('user.layout')


@section('page-header')
Edit Template
@stop

@section('content')
@include('partial.notifications')
<div class="row">
    @if (\Session::has('message'))
        <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
    @endif
    <div class="col-lg-12">
        <div id="message"></div>
        <form action="{{ route('user.template.post.edit', $template->id) }}" id="create-form" method="POST">
            {!! csrf_field() !!}
            <input type="hidden" id="template_id" name="id" value="{{ $template->id }}" placeholder="">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" value=" {{ old('title') != null ? old('title') : $template->title }}" id="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="cat_id">Category</label>
                {!! Form::select('cat_id', $list_category, old('cat_id') != null ? old('cat_id') : $template->cat_id, ['class' => 'form-control', 'id' => 'categories', 'placeholder' => 'Choose Category']) !!}
            </div>
            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="text" name="price" class="form-control" value="{{ old('price') ? old('price') : $template->price }}" id="price" placeholder="$">
            </div>
            <div class="form-group">
                <textarea id="content" name="content">{{ old('content') ? old('content') :  $template->present()->contentPresent }}</textarea> 
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" id="description" placeholder="Description">{{ old('description') ? old('description') : $template->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="version">Version</label>
                <input name="version" type="text" value="{{ old('version') ? old('version') : $template->version }}" class="form-control" id="version" placeholder="Version">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                {!! Form::select('status', [1 => 'Pending', 2=> 'Publish', 0 => 'Block',] , old('status') ? old('status') : $template->status, [ 'class' =>  'form-control', 'id' => 'status'])!!}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('user.template.get.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#categories option:first-child').attr('disabled', true);
    });
     function elFinderBrowser (callback, value, meta) {
        tinymce.activeEditor.windowManager.open({
            file: "{{ asset('tinymce/plugins/elfinder/elfinder.html') }}",// use an absolute path!
            title: 'elFinder 2.1',
            width: 900, 
            height: 450,
            resizable: 'yes'
        }, {
            oninsert: function (file, fm) {
                var url, reg, info;

                // URL normalization
                url = file.url;
                reg = /\/[^/]+?\/\.\.\//;
                while(url.match(reg)) {
                    url = url.replace(reg, '/');
                }
                
                // Make file info
                info = file.name + ' (' + fm.formatSize(file.size) + ')';

                // Provide file and text for the link dialog
                if (meta.filetype == 'file') {
                    callback(url, {text: info, title: info});
                }

                // Provide image and alt text for the image dialog
                if (meta.filetype == 'image') {
                    callback(url, {alt: info});
                }

                // Provide alternative source and posted for the media dialog
                if (meta.filetype == 'media') {
                    callback(url);
                }
            }
        });
        return false;
    }

   tinymce.init({
      selector: "#content",
      height : 500,
        // plugins: "table,code, image, link, media",
        relative_urls: false,
        remove_script_host: false,
        style_formats: [
        { title: 'Activitie', block: 'div', attributes: {lang: 'activitie'} , styles: { color: '#0000' } },
        { title: 'Address', block: 'div', attributes:{lang: 'address'}, styles: { color: '#00000' } },
        { title: 'Availability', block: 'div', attributes: {lang: 'availability'}, styles: { color: '#0000' } },
        { title: 'Education', block: 'div', attributes: {lang: 'education'}, styles: { color: '#0000' } },
        { title: 'Email', block: 'div', attributes: {lang: 'email'}, styles: { color: '#0000' } },
        { title: 'Infomation', block: 'div', attributes: {lang: 'infomation'}, styles: { color: '#0000' } },
        { title: 'Qualification', block: 'div', attributes: {lang: 'key_qualification'}, styles: { color: '#0000' } },
        { title: 'Linkedin', block: 'div', attributes: {lang: 'linkedin'}, styles: { color: '#0000' } },
        { title: 'Mobile Phone number', block: 'div', attributes: {lang: 'phone'}, styles: { color: '#0000' } },
        { title: 'Name', block: 'div', attributes: {lang: 'name'}, styles: { color: '#000000' } },
        { title: 'Objective', block: 'div', attributes: {lang: 'objective'}, styles: { color: '#0000' } },
        { title: 'Personal Test', block: 'div', attributes: {lang: 'personal_test'}, styles: { color: '#0000' } },
        { title: 'Photo', block: 'div', attributes: {lang: 'photo'}, styles: { color: '#0000' } },
        { title: 'Profile Website', block: 'div', attributes: {lang: 'profile_website'}, styles: { color: '#0000' } },
        { title: 'Reference', block: 'div', attributes: {lang: 'reference'}, styles: { color: '#0000' } },
        { title: 'Skill', block: 'div', attributes:{lang: 'skill'}, styles: { color: '#0000' } },
        { title: 'Work Experience', block: 'div', attributes:{lang: 'work'}, styles: { color: '#0000' } }
        ],
        // visualblocks_default_state: true,
        // end_container_on_empty_block: true,
        plugins: [
        " image,preview,hr,code,table,colorpicker,textcolor"
        ],
        toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect colorpicker|cut copy paste  | bullist numlist | outdent indent blockquote | undo redo | image code |  preview | forecolor backcolor |table | hr removeformat  | ltr rtl ",

        menubar :false,
        file_picker_callback : elFinderBrowser,

    });

</script>
@endsection