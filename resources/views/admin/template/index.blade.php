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
                        <?php 
                            $pending = '';
                            $active = '';
                            $block = '';
                            if ($temp_market->status == 2) {
                                $pending = "selected";
                            }
                            if ($temp_market->status == 1) {
                                $active = "selected";
                            }
                            if ($temp_market->status == 0) {
                                $block = "selected";
                            }
                        ?>
                        {!! Form::open(['route' => ['admin.status', $temp_market->id], 'id' => 'changeStatus']) !!}
                            <select class="form-control" id="temp_status" name="status" onchange="this.form.submit()" >
                                <option value="2" {{ $pending }}>Pending</option>
                                <option value="1" {{ $active }}>Active</option>
                                <option value="0" {{ $block }}>Block</option>
                            </select>
                        {!! Form::close() !!}
                        </td>
                        <td class="center">{{ $temp_market->version }}</td>
                        <td class="center"><img class="thumbnail" src="{{ asset($temp_market->image['origin']) }}"></td>
                        <td class="center">
                            <a href="">Edit</a>/
                            <a href="{{route('admin.template.delete', $temp_market->id)}}" onclick="return confirm('Are You Sure delete this template?')">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>
    </div>
</div>
@endsection
@section('script')