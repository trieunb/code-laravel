<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <!-- Bootstrap CSS -->
        <link href="{{  asset('css/bootstrap.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    </head>
    <body>
        @yield('content')
        <!-- jQuery -->
        <script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
        <!-- Bootstrap JavaScript -->
        <script src="{{  asset('js/bootstrap.js') }}"></script>
    </body>
</html>