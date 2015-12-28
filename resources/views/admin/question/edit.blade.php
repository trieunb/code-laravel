
@extends('admin.layout')

@section('title')
    Create Question
@stop

@section('page-header')
Edit Question
@stop
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('css/toastr.css')}}">
@endsection
@section('content')
<div id="ajax-message"></div>
<form method="post" enctype="multipart/form-data" role="form" id="form-update" action="{{ route('api.question.post.editAdmin') }}">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" name="id" value="{{$question->id}}">

<div class="form-group">
    <label for="content" class="control-label">Content</label>
    <div>
        <textarea rows="3" name="content" class="form-control" id="content">{{ old('content') ? old('content') : $question->content }}</textarea>
    </div>
</div>
<div class="form-group">
    <div class="">
          <div class="checkbox">
            <label>
              <input id="publish" type="checkbox" name="publish" @if ($question->publish == 1) checked @endif> Publish
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <div>
        <a href="{{ route('admin.question.get.index') }}" class="btn btn-default">Cancel</a>
        <button type="submit" class="btn btn-primary update">Save changes</button>
    </div>
</div>
</form>
@endsection
@section('script')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
<script src="{{ asset('js/toastr.js') }}"></script>
<script>
    jQuery.validator.addMethod("noSpace", function(value, element) { 
      return this.optional(element) || value.replace(/\s/g,"") || value.trim();
    }, "No space please and don't leave it empty");

 var isBusy = false;
$('#form-update').validate({

    rules: {
        content: {
            required: true,
            noSpace: true,
            maxlength: 255
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
 });
</script>
@endsection