@extends('admin.layout')

@section('title')
	Question
@stop

@section('page-header')
Question List
@stop

@section('content')
	@if (\Session::has('message'))
		<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
	@endif
	<table class="table table-striped table-bordered table-hover" id="questions-table">
		<thead>
			<th>Id</th>
			<th>Question</th>
			<th>Publish</th>
			<th>Created At</th>
			<th>Updated At</th>
			<th>Action</th>
		</thead>
	</table>
@stop

@section('script')
	<script>
		$(document).ready(function() {
			var questionDataTable = $('#questions-table').DataTable({
		        processing: true,
		        serverSide: true,
		        responsive: true,
		        ajax: '{{ route("api.question.get.dataTable") }}',
		        columns: [
		            {data: 'id', name: 'id'},
		            {data: 'content', name: 'content'},
		            {data: 'publish', name: 'publish', orderable: false, searchable: false},
		            {data: 'created_at', name: 'created_at'},
		            {data: 'updated_at', name: 'updated_at'},
		            {data: 'action', name: 'action', orderable: false, searchable: false}
		        ],
		        order: [[3, 'DESC']]
		    });

		    $('body').on('hidden.modal.bs', function() {
		    	questionDataTable.ajax.reload();
		    });

		    var isBusy = false;
			$(document).on('click', '.delete-data', function(e) {
				e.preventDefault();
				
				if (isBusy) return;
				var answer = confirm('Are you sure you want to delete?');
				if ( ! answer) return;
				isBusy = true;

				$.ajax({
					url: $(this).data('src'),
					type: 'GET',
					success: function(result) {
						if (result.status == true) {
							console.log(result.status);
							questionDataTable.ajax.reload();
						}
					}
				}).always(function() {
					isBusy = false;
				});
			});
		});
		
	</script>
@stop