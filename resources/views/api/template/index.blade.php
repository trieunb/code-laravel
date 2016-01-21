<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resume</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }} " media="screen" title="no title"
          charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }} " media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }} " media="screen" title="no title" charset="utf-8">
    <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <style>
        #buttons select {
            display: block;
            width: 150px;
            background-image: none;
            outline: none;
            border: none;
            background: initial;
            -webkit-appearance: none;
            -moz-appearance: none;
            text-indent: 1px;
            text-overflow: '';
        }
        #manual {
            cursor:pointer;
        }
         #manual -child a {
            text-indent: 6px;
        }
        .mobile{
            overflow: hidden;
        }
        .container {
            width: 800px !important;
        }
    </style>
</head>
<body>
  
<main class="mobile">
    <div class="container">
            <div id="content" class="fw w_bg">
                <div class="col-xs-12">
                    {!! $content !!}
                </div>
            </div>
    </div>
</main>
</body>
</html>
