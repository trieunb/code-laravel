@extends('admin.layout')

@section('style')
<style>
    table img {
        width: 200px;
        height: 200px;
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
<table class="table table-striped table-bordered table-hover" id="templates-table">
    <thead>
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
    <script src="{{ asset('fancybox/source/jquery.fancybox.pack.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fancybox/source/helpers/jquery.fancybox-buttons.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fancybox/source/helpers/jquery.fancybox-media.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fancybox/source/helpers/jquery.fancybox-thumbs.js') }}"></script>

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
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[3, 'DESC']]
            });
            /* $('#templates-table tbody').on( 'click', 'td', function () {
                    $('#templates-table a').removeClass('fancybox');
                    $.each($(this).find('a'), function(key, val) {
                        $(val).addClass('fancybox');
                    });
            } );
             $('.fancybox').fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                beforeShow: function () {
                    this.width = 1000;
                    this.height = 1200;
                }
            });*/
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
