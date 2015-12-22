@extends('admin.layout')

@section('style')
<style>
    table img {
        width: 200px;
        height: 200px;
    }
    table tbody input[type="checkbox"] {
        margin-left: 9px !important;
    }
    #option-action {
        margin-bottom: 10px;
        padding-right: 0;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('fancybox/source/jquery.fancybox.css?') }}">
<link rel="stylesheet" href="{{ asset('fancybox/source/helpers/jquery.fancybox-thumbs.css') }}" type="text/css" media="screen" />
@stop

@section('page-header')
Template List
@stop

@section('content')
@if (\Session::has('message'))
    <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
@endif

<div class="col-xs-4 pull-right" id="option-action">
    <select class="form-control" id="action">
        <option selected disabled value="">Choose Option</option>
        <option value="2">Publish</option>
        <option value="1">Pending</option>
        <option value="delete">Delete</option>
    </select>
</div>

<table class="table table-striped table-bordered table-hover" id="templates-table">
    <thead>
        <th><input type="checkbox" id="check_all"></th>
        <th>Id</th>
        <th>Title</th>
        <th>Price</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Status</th>
        <th>Action</th>
    </thead>
</table>
@endsection
@section('script')
    <script src="{{ asset('fancybox/lib/jquery.mousewheel-3.0.6.pack.js') }}"></script>
    <script src="{{ asset('js/action_admin.js') }}"></script>
    <script type="text/javascript">
        var isBusy = false;
        $(document).ready(function () {

            $('#wrapper').on('change', '#action', function() {
                var ids = new Array;
                $('tbody input[type=checkbox]:checked').each(function() {
                    ids.push($(this).val());
                });
                if (isBusy) return;
                isBusy = true;
                $.ajax({
                    url: "{{ route('admin.template.post.action') }}",
                    type: 'POST',
                    data: {
                        token: "{{ csrf_token() }}",
                        action: $(this).find('option:selected').val(),
                        ids: ids
                    },
                    success: function(result) {
                        if (result.status_code == 200) {
                            TemplateDatatable.ajax.reload();
                        }
                    }
                }).always(function() {
                    isBusy = false;
                });
            });

            var TemplateDatatable = $('#templates-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route("api.template.get.dataTable") }}',
                columns: [
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'price', name: 'price'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[3, 'DESC']]
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
                            TemplateDatatable.ajax.reload();
                        }
                    }
                }).always(function() {
                    isBusy = false;
                });
            });
            
            $(document).on('click', '.status-data', function(e) {
                e.preventDefault();
                
                if (isBusy) return;
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
