@extends('api.app')
@section('title', $template->title)
@section('css')
<style type="text/css">
  .border-section{
    border: 2px solid #bcbcbc;
  }
</style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <button type="submit" class="btn btn-primary pull-right" id="edit-template">Edit</button>
        </div>
    </div>
    <div id="content">
        {!! $content !!}
    </div>
@stop
@section('scripts')
<script type="text/javascript" src="{{asset('assets/js/edit_section_temp.js')}}"></script>
@endsection