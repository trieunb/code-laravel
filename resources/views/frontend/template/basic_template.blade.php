<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" >
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/fonts.css') }}" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    </head>
    <body>
        <div class="container">
            <div class="row wrapper detail">
                <div class="fw w_bg"  >
                        <div id="user" contenteditable="true">
                            {!! $template->template['info'] !!}
                        </div>    
                        <hr>
                        <div class="user_education" contenteditable="true">{!! $template->template['education'] !!}</div>    
                        <div class="user_work_histories" contenteditable="true">{!! $template->template['work_histories'] !!}</div>    
                        <div class="user_skills" contenteditable="true">{!! $template->template['skills'] !!}</div>    
                        <div class="references" contenteditable="true">{!! $template->template['references'] !!}</div>    
                        <div class="objectives" contenteditable="true">{!! $template->template['objectives'] !!}</div>    
                </div>
            </div>
        </div>
    </body>
</html>