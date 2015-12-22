@extends('admin.layout')

@section('title')
Create Category
@stop

@section('page-header')
Create Category
@stop

@section('content')
 @if (\Session::has('message'))
    <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
    @endif

<form class="form-horizontal">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" name="name" class="form-control" id="name" placeholder="Name">
		</div>
	</div>

	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Description</label>
		<div class="col-sm-10">
			<input type="text" name="description" class="form-control" id="description" placeholder="Description">
		</div>
	</div>
	<div class="form-group">
		<label for="keyword" class="col-sm-2 control-label">Keyword</label>
		<div class="col-sm-10">
			<input type="text" name="keyword" class="form-control" id="description" placeholder="Keyword">
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Parent Id</label>
		<div class="col-sm-10">
			{!! Form::select('parent_id', $list_category, null, ['class' => 'form-control', 'placeholder' => 'Parent']) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary">Create</button>
			<a class="btn btn-default" href="{{ route('admin.category.get.index') }}">Cancel</a>
		</div>
	</div>
</form>
@stop

@section('script')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
<script>
	$(document).ready(function() {
		$('form').validate({
			rules: {
				name: {
					required: true,
					maxlength: 30,
					remote: {
						url: "{{ route('admin.category.post.checkname') }}",
						type: 'POST',
						data: {
							token: "{{ csrf_token() }}",
							name: function(){
								return $('#name').val();
							}
						}
					}
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