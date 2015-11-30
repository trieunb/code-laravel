@extends('admin.layout')


@section('page-header')
Edit Template
@stop

@section('content')
<div class="row">
    @if (\Session::has('message'))
        <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
    @endif
    <div class="col-lg-12">
        <div id="message"></div>
        <form action="{{ route('admin.template.post.edit', $template->id) }}" id="create-form" method="POST">
            {!! csrf_field() !!}
            <input type="hidden" id="template_id" name="id" value="{{ $template->id }}" placeholder="">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" value="{{ $template->title }}" id="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="cat_id">Category</label>
                <select name="cat_id" id="cat_id" class="form-control" >
                    <option value="">Select</option>
                    <option value="1" selected>Category</option>
                </select>

            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" class="form-control" value="{{ $template->price }}" id="price" placeholder="Price">
            </div>
            <div class="form-group">
                <textarea id="content" name="content">{{ $template->present()->contentPresent }}</textarea> 
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" class="form-control" value="{{ $template->description }}" id="description" placeholder="Description">
            </div>
            <div class="form-group">
                <label for="version">Version</label>
                <input name="version" type="text" value="{{ $template->version }}" class="form-control" id="version" placeholder="Version">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                {!! Form::select('status', [1 => 'Pending', 2=> 'Publish', 0 => 'Block',] , $template->status, [ 'class' =>  'form-control', 'id' => 'status'])!!}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Edit</button>
                <a href="{{ route('admin.template.get.index') }}" class="btn btn-default">Go to back</a>
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
    CKEDITOR.replace('content', {
        format_section : 'PersonalityTest;Objectives;KeyQuanlifications;WorkExperience;OtherActivities;Educations;References;Photos;Address;PhoneNumber;Email;MyProfileWebsite;MyLinkedInProfile;Name'
    });
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
        }
    });
</script>
@endsection