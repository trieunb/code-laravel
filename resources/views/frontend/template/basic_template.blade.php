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
         <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="header">
                    <div class="image-avatar">
                    @if ( !empty($template->avatar['origin'] ))
                        <img style="width:100%;" id="image" src="{{ asset($template->avatar['origin']) }}">
                    @else
                        <img style="width:100%;" id="image" src="{{ asset('images/avatar.jpg') }}">
                    @endif
                    <div class="text-info text-center">
                        <p class=full-name>{!! $template->firstname . ' ' . $template->lastname !!}</p>
                        <span>{{ $template->link_profile }}</span><br>
                        <span>{{ $template->email }}</span>
                    </div>
                    </div>
                </div>
                <div class="info text-center">
                    <span>{!! $template->address !!}</span><br>
                    <span>{!! $template->city . ',' . $template->state !!}</span><br>
                    <span>Tell: {!! $template->mobile_phone !!}</span>
                </div>
                <div class="content">
                    <div class="content-box">
                        <div class="header-title">
                            <span>Infomation</span>
                        </div>
                        <div class="box">
                            <span>{!! $template->infomation !!}</span>
                        </div>
                    </div>
                    <div class="content-box">
                        <div class="header-title">
                            <span>Education</span>
                        </div>
                        <div class="box">
                            <label>School Name:</label>
                            @foreach($template->user_educations as $education)
                            <span>
                                {!! $education['school_name'] !!}  
                            </span> ,
                            @endforeach
                        </div>
                    </div>
                    <div class="content-box">
                        <div class="header-title">
                            <span>Work Experience</span>
                        </div>
                        <div class="box">
                            <label>Company:</label>
                            @foreach($template->user_work_histories as $work)
                            <span>
                                {!! $work['company'] !!}
                            </span>,
                            @endforeach
                        </div>
                    </div>
                    <div class="content-box">
                        <div class="header-title">
                            <span>Skills</span>
                        </div>
                        <div class="box">
                            @foreach($template->soft_skill as $skill)
                            <span>
                                {!! $skill['question'] !!}
                            </span> ,
                            @endforeach
                            <p>
                              <label for="amount">Text Size:</label>
                            </p>
                            <div id="slider-range-min"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script type="text/javascript">
            $(function() {
                $( "#slider-range-min" ).slider({
                  range: "min",
                  value: 15,
                  min: 10,
                  max: 40,
                  slide: function( event, ui ) {
                    $( "#amount" ).val(ui.value );
                  },
                  change: function( event, ui ) {
                    $('p.full-name').css("font-size",(ui.value + 15 + "px"));
                    $('.box>span').css("font-size",(ui.value + "px"));
                    $('.header-title>span').css("font-size",(ui.value + 3 + "px"));
                    $('.text-info>span').css("font-size",(ui.value + 3 + "px"));
                    $('.info>span').css("font-size",(ui.value + 3 + "px"));
                  }
                });
                $( "#amount" ).val($( "#slider-range-min" ).slider( "value" ) );
              });
        </script>
    </body>
</html>