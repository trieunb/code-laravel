@extends('admin.layout')

@section('style')
	<style>
		.btn-group a {
			margin-left: 10px !important;
		}
	</style>
@stop

@section('title')
	User List
@stop

@section('page-header')
	User List
@stop

@section('content')
	@if (\Session::has('message'))
		<div class="alert alert-success">
		    <button type="button" class="close" data-dismiss="alert">×</button>
		    <strong>{{ \Session::get('message') }}</strong>
	    </div>
	@endif

	<table class="table table-striped table-bordered table-hover dt-responsive nowrap" id="users-table">
		<thead>
			<th>Id</th>
			<th>Fullname</th>
            <th>Email</th>
            <th>Registered date</th>
			<th>OS</th>
			<th>Action</th>
		</thead>
	</table>
@stop

@section('script')

	<script src="{{ asset('js/action_admin.js') }}"></script>
	<script>
		var isBusy = false;

		var userDatatable = $('#users-table').DataTable({
		        processing: true,
		        serverSide: true,
		        responsive: true,
		        ajax: '{{ route("api.admin.user.get.dataTable") }}',
		        columns: [
		            {data: 'id', name: 'id'},
		            {data: 'firstname', name: 'firstname'},
                    {data: 'email', name: 'email'},
                    {data: 'created_at', name: 'created_at'},
		            {data: 'os', name: 'os', orderable: false},
		            {data: 'action', name: 'action', orderable: false, searchable: false, width: "5%"}
		        ],
		        order: [[3, 'DESC']]
		    });

        $(document).on('click', '.delete-user', function(e) {
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
                        userDatatable.ajax.reload();
                    }
                }
            }).always(function() {
                isBusy = false;
            });
        });

        $('#wrapper').on('change', '#action', function() {
			var ids = new Array;
			$('tbody input[type=checkbox]:checked').each(function() {
				ids.push($(this).val());
			});

			if (isBusy) return;
			isBusy = true;

			$.ajax({
				url: "{{ route('admin.user.post.delete') }}",
				type: 'POST',
				data: {
					token: "{{ csrf_token() }}",
					ids: ids
				},
				success: function(result) {
					if (result.status_code == 200) {
						userDatatable.ajax.reload();
					}
				}
			}).always(function() {
				isBusy = false;
			});
		});
	</script>
@stop
