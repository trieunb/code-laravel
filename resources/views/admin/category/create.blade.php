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

@include('partial.notifications')
<form class="form-horizontal" method="post" action="{{ route('admin.category.post.create') }}">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}">
		</div>
	</div>

	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Description</label>
		<div class="col-sm-10">
			<textarea name="description" class="form-control" id="description" placeholder="Description">{{ old('description') }}</textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="keyword" class="col-sm-2 control-label">Keyword</label>
		<div class="col-sm-10">
			<textarea name="keyword" class="form-control" id="description" placeholder="Keyword">{{ old('keyword') }}</textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Parent Id</label>
		<div class="col-sm-10">
			{!! Form::select('parent_id', $list_category, old('parent_id') , ['class' => 'form-control', 'placeholder' => 'Parent']) !!}
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
@endsection