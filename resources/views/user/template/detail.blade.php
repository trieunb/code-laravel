@extends('user.layout')


@section('page-header')
Detail Template
@stop

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div id="message"></div>
        <form action="{{ route('user.template.post.edit') }}" id="create-form" method="POST">
            <input type="hidden" id="template_id" name="id" value="{{ $template->id }}" placeholder="">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" value="{{ $template->title }}" disabled id="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="cat_id">Category</label>
                <select disabled name="cat_id" id="cat_id" class="form-control" >
                    <option value="">Select</option>
                    <option value="1">Category</option>
                </select>

            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" value="{{ $template->price }}" id="price" disabled placeholder="Price">
            </div>

            <div class="form-group">
                <textarea id="content" name="content" disabled>{{ $template->content }}</textarea> 
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input disabled type="text" class="form-control" value="{{ $template->description }}" id="description" placeholder="Description">
            </div>
            <div class="form-group">
                <label for="version">Version</label>
                <input disabled name="version" type="text" value="{{ $template->version }}" class="form-control" id="version" placeholder="Version">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select disabled name="status" id="status" class="form-control" >
                    <option value="">Select</option>
                    <option value="0">Block</option>
                    <option value="1">Pending</option>
                    <option value="2">Publish</option>
                </select>
            </div>
            <div class="form-group">
                <a href="{{ route('user.template.get.index') }}" class="btn btn-default">Go to back</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
<script>
    CKEDITOR.replace('content');
    var isBusy = false;
    $('form').validate({
        rules: {
            title : {
                required: true,
                remote : {
                    url: '{{ route("admin.template.check") }}',
                    type: 'GET',
                    data: {
                        title: function() {
                            return $("#title" ).val();
                        },
                        id : function(){
                            return $('#template_id').val();   
                        }
                    }   
                }
            },
            cat_id : {
                required : true
            },
            version : {
                required: true
            },
            status : {
                required : true
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
        },
        submitHandler : function(form) {
            var content = CKEDITOR.instances.content.getData();

            $.ajax({
                url: $('#create-form').attr('action'),
                type: 'POST',
                data: {
                    id : $('#template_id').val(),
                    title : $('#title').val(),
                    price : $('#price').val(),
                    content : content,
                    cat_id : $('#cat_id').val(),
                    description : $('#description').val(),
                    version : $('#version').val(),
                    status : $('#status').val(),
                    _token : $('input[name=_token]').val()
                },
                success : function(result) {
                    var alert = result.status == true ? 'success' : 'danger';
                    var message = '<div class="alert alert-'+alert+'"><button type="button" class="close" data-dismiss="alert">×</button><strong>Create template successfully</strong></div>';
                    $('#message').html(message);
                }
            }).always(function() {
                isBusy = false;
            });
        }
    });
</script>
@endsection