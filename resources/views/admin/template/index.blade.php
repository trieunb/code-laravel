@extends('admin.layout')
@section('page-header')
List Templates
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="dataTable_wrapper">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column descending" style="width: 156px;" aria-sort="ascending">Title</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 191px;">price</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 174px;">Status</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 135px;">Version</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 101px;">Image</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 101px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates_market as $temp_market)  
                            <tr class="gradeA odd" role="row">
                                <td class="sorting_1">{{ $temp_market->title }}</td>
                                <td>{{ $temp_market->price }}</td>
                                <td class="">
                                {!! Form::open(['route' => ['admin.status', $temp_market->id],'id' => 'changeStatus']) !!}
                                    {!! Form::select('status', [2 => 'Pending', '1'=> 'Active', 0 => 'Block',] , $temp_market->status, [ 'class' =>  'form-control', 'id' => 'status', 'onchange' => 'this.form.submit()'])!!}
                                {!! Form::close() !!}
                                </td>
                                <td class="center">{{ $temp_market->version }}</td>
                                <td class="center"><img class="thumbnail" src="
                                    {{ ($temp_market->image) 
                                    ? asset($temp_market->image['origin']) 
                                    : asset('images/template.jpg') }}">
                                </td>
                                <td class="center">
                                    <a href="{{route('admin.template.get.edit', $temp_market->id)}}">Edit</a>/
                                    <a href="{{route('admin.template.delete', $temp_market->id)}}" onclick="return confirm('Are you sure delete this template?')">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
                </div>
                <div class="col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                        <ul class="pagination">
                            <li class="paginate_button previous disabled" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous">
                                <a href="#">Previous</a>
                            </li>
                            <li class="paginate_button active" aria-controls="dataTables-example" tabindex="0">
                                <a href="#">1</a>
                            </li>
                            <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                <a href="#">2</a>
                            </li>
                            <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                <a href="#">3</a>
                            </li>
                            <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                <a href="#">4</a>
                            </li>
                            <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                <a href="#">5</a>
                            </li>
                            <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                <a href="#">6</a>
                            </li>
                            <li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next">
                                <a href="#">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
