@extends('admin.layout')


@section('page-header')
Create Template
@stop

@section('content')
<div class="row">
    @if (\Session::has('message'))
    <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
    @endif
    <div class="col-lg-12">
        <div id="message"></div>
        <form action="{{ route('admin.template.post.create') }}" id="create-form" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="cat_id">Category</label>
                <select name="cat_id" id="cat_id" class="form-control" >
                    <option value="">Select</option>
                    <option value="1">Category</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" class="form-control" id="price" placeholder="Price">
            </div>

            <div class="form-group">
                <textarea id="content" name="content"></textarea> 
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea  name="description" class="form-control" id="description" placeholder="Description"></textarea>
            </div>
            <div class="form-group">
                <label for="version">Version</label>
                <input name="version" type="text" value="1" class="form-control" id="version" placeholder="Version">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" >
                    <option value="1" selected>Pending</option>
                    <option value="2">Publish</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.template.get.index') }}" class="btn btn-default">Cancel</a>
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


    // TinyMCE init
    tinymce.init({
      selector: "#content",
      height : 500,
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

$(function() {

    var isBusy = false;
    $('form').validate({
        rules: {
            title : {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                maxlength: 225,
            /*remote : {
                url: '{{ route("admin.template.check") }}',
                type: 'GET',
                data: {
                    title: function() {
                        return $( "#title" ).val();
                    }
                }   
            }*/
        },
        price: {
            required: true,
            number: true,
            number: true,
            min: 0
        },
        cat_id : {
            required : true
        },
        version : {
            required: true,
            maxlength: 10
        },
        status : {
            required : true
        },
        description: {
            maxlength: 1000
        }
    },
    messages : {
        title: {
            remote : 'Title exists, please change title'
        }
    },
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});
});


</script>
@endsection