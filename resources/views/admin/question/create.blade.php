@extends('admin.layout')

@section('title')
	Create Question
@stop

@section('page-header')
Create Question
@stop

@section('content')
        <form action="{{ route('admin.question.post.create') }}" id="create-form" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="content">Content</label>
                <input type="text" class="form-control" name="content" id="content" placeholder="Content">
            </div>
            <div class="checkbox">
                <label>
                  <input name="publish" type="checkbox"> Publish
                </label>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.question.get.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
@endsection
@section('script')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
<script>
  
$(function() {
    
 
    var isBusy = false;
    $('form').validate({
        rules: {
            content : {
                required : true,
                minlength: 6
            },
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