@extends('admin.layout')

@section('title')
	Question
@stop

@section('content')
	<table class="table table-striped table-bordered table-hover" id="questions-table">
		<thead>
			<th>Id</th>
			<th>Question</th>
			<th>Created At</th>
			<th>Updated At</th>
			<th>Action</th>
		</thead>
	</table>

	<div class="modal fade" id="modal-admin">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('script')
	<script>
		$(document).ready(function() {
			$('body').on('hidden.bs.modal', '.modal', function() {
				$(this).removeData('bs.modal');
			});

			var questionDataTable = $('#questions-table').DataTable({
		        processing: true,
		        serverSide: true,
		        responsive: true,
		        ajax: '{{ route("api.question.get.dataTable") }}',
		        columns: [
		            {data: 'id', name: 'id'},
		            {data: 'content', name: 'content'},
		            {data: 'created_at', name: 'created_at'},
		            {data: 'updated_at', name: 'updated_at'},
		            {data: 'action', name: 'action', orderable: false, searchable: false}
		        ]
		    });

		    $('body').on('hidden.modal.bs', function() {
		    	questionDataTable.ajax.reload();
		    });

		    var isBusy = false;
			$(document).on('click', '#delete-data', function(e) {
				e.preventDefault();
				
				if (isBusy) return;
				var answer = confirm('Are you sure you want to delete?');
				if ( ! answer) return;
				isBusy = true;

				$.ajax({
					url: $('#delete-data').data('src'),
					type: 'GET',
					success: function(result) {
						if (result.status == true) {
							console.log(result.status);
							questionDataTable.ajax.reload();
						}
					}
				});
			});
		});

	</script>
@stop