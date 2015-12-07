@extends('admin.layout')


@section('page-header')
Template
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
                <input type="text" name="description" class="form-control" id="description" placeholder="Description">
            </div>
            <div class="form-group">
                <label for="version">Version</label>
                <input name="version" type="text" class="form-control" id="version" placeholder="Version">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" >
                    <option value="">Select</option>
                    <option value="1">Pending</option>
                    <option value="2">Publish</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
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
   /* CKEDITOR.replace('content', {
        config.format_section = 'div;Name;Address;Phone;Email;ProfileWebsite;LinkedInProfile;References;Objectives;OtherActivities;WorkExperiences;Educations;Photos';

    });*/
$(function() {
    
    CKEDITOR.replace( 'content', {
        format_section : 'PersonalityTest;Objectives;KeyQuanlifications;WorkExperience;OtherActivities;Educations;References;Photos;Address;PhoneNumber;Email;MyProfileWebsite;MyLinkedInProfile;Name;Availability;PersonalInfomation'
    } );
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
                            return $( "#title" ).val();
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
});


</script>
@endsection