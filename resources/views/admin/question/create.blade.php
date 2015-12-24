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
        <textarea rows="3" class="form-control" name="content" id="content" placeholder="Content">{{ old('content') }}</textarea>
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
    
    jQuery.validator.addMethod("noSpace", function(value, element) { 
      return this.optional(element) || value.replace(/\s/g,"") || value.trim();
    }, "No space please and don't leave it empty");

    var isBusy = false;
    $('form').validate({
        rules: {
            content : {
                required : true,
                noSpace: true
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