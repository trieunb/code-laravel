@extends('admin.layout')
@section('page-header')
Template List
@stop
@section('content')
@if (\Session::has('message'))
    <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
@endif
<table class="table table-striped table-bordered table-hover" id="templates-table">
    <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Price</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Action</th>
    </thead>
</table>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            var TemplateDatatable = $('#templates-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route("api.template.get.dataTable") }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'price', name: 'price'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[0, 'DESC']]
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
                            TemplateDatatable.ajax.reload();
                        }
                    }
                }).always(function() {
                    isBusy = false;
                });
            });

        });
    </script>
@endsection
