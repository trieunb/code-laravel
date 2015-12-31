@extends('admin.layout')

@section('title')
    View Resume
@stop

@section('page-header')
    Template: {{ $resume->title }}
@stop

@section('content')
    {!! $resume->content !!}
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('div').removeAttr('contenteditable');
        });
    </script>
    
@endsection