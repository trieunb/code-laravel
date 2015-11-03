@extends('frontend.app')

@section('title')
Create Template
@endsection

@section('content')

<div class="container">
	@include('partial.notifications')
	<div class="row">
		<form action="{{ route('frontend.template.post.create') }}" id="create-form" method="POST">

			<div class="form-group">
				<label for="title">Title</label>
				<input type="text" class="form-control" id="title" placeholder="Title">
			</div>
			<div class="form-group">
				<label for="price">Price</label>
				<input type="text" class="form-control" id="price" placeholder="Price">
			</div>
			
			<div id="myPanel" style="position:inherit;"></div>
			<div id="content_editor">
				<div class="header" id="header">
					<div class="image-avatar">
						@if ( !empty($template->avatar['origin'] ))
						<img id="image" src="{{ asset($template->avatar['origin']) }}">
						@else
						<img  id="image" src="{{ asset('images/avatar.jpg') }}">
						@endif
						<div class="text-info text-center">
							<p class=full-name>Fullname:</p>
							<span>Link Profile:</span><br>
							<span>Email:</span>
						</div>
					</div>
				</div>
				<div class="info text-center" id="info">
					<span>Address:</span><br>
					<span>City: - State: </span><br>
					<span>Tell: </span>
				</div>
				<div class="content" id="content">
					<div class="content-box">
						<div class="header-title">
							<span>Infomation</span>
						</div>
						<div class="box">
							<span>Infomation ...</span>
						</div>
					</div>
					<div class="content-box">
						<div class="header-title">
							<span>Education</span>
						</div>
						<div class="box">
							<label>School Name:</label>

						</div>
					</div>
					<div class="content-box">
						<div class="header-title">
							<span>Work Experience</span>
						</div>
						<div class="box">
							<label>Company:</label>

						</div>
					</div>
					<div class="content-box">
						<div class="header-title">
							<span>Skills</span>
						</div>
						<div class="box">

							<p>
								<label for="amount">Text Size:</label>
							</p>
							<div id="slider-range-min"></div>
						</div>
					</div>
				</div>
			</div>
			<br>
			<button type="submit" class="btn btn-primary">Create</button>
		</form>
	</div>
</div>
@stop

@section('script_files')
<script src="{{  asset('js/nicEdit.js') }}"></script>
@stop

@section('scripts')
<script>
	bkLib.onDomLoaded(function() {
		var myNicEditor = new nicEditor();
		myNicEditor.setPanel('myPanel');
		myNicEditor.addInstance('header');
		myNicEditor.addInstance('content');
		myNicEditor.addInstance('info');
	});
	$(document).ready(function() {
		$('#create-form').submit(function(e) {
			e.preventDefault();
			var template = '';
			template += $('#content_editor').html();
			// template = template.replace(/\t|\n+/g, '');

			$.ajax({
				url: $(this).attr('action'),
				type: 'POST',
				data: {
					title : $('#title').val(),
					price : $('#price').val(),
					template_full : template,
					token : '{{ $token }}',
				},
				success: function(result) {
					console.log(result);
				}
			});
		});
	});
</script>
@stop