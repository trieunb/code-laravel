@extends('api.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }} " media="screen" title="no title"
          charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }} " media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }} " media="screen" title="no title" charset="utf-8">
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
        #manual a {
            text-indent: 6px;
        }
        #content img{
            width: 100% !important;
            height: 200px !important;
        }
        .mobile{
            overflow: hidden;
        }
    </style>
    @stop

    @section('content')
            <!-- <div id="myPanel" style=""></div> -->
    <div id="loading">
        <img class="img-responsive" src="{{ asset('images/loading.gif') }}" alt="">
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form id="upload">
                <div id="content"  class="col-md-12" contenteditable="true">

                    @if ($section != 'availability')
                        {!! $content !!}
                    @else
                        {!! Form::select('availability', $setting, $template->user->status, ['class' => 'form-control']) !!}
                    @endif

                </div>
            </form>
        </div>
    </div>
    <div class="col-md-12" id="buttons">
        <ul class="dropdown-menu" aria-labelledby="dLabel" id="choose-type">

            <div id="get-from-profile" class="" aria-labelledby="dLabel">
                <div class="top">
                    <span class="close">x</span>

                    <p>Choose the element you want to edit</p>
                </div>
                <ul class="list list-unstyled">
                    <li id="manual" onClick=""><a>Type Manual</a></li>
                   
                    @if ($template->present()->createMenuProfile($user_id, $section) != '')
                        <li> 
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                                <select name="" class="">
                                    <option value="" disabled selected>Get From Profile</option>
                                    {!!$template->present()->createMenuProfile($user_id, $section) !!}
                                </select>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

        </ul>

    </div>


@stop

@section('scripts')
    <script src="{{  asset('js/nicEdit.js') }}"></script>
    <script src="{{  asset('js/main.js') }}"></script>

    <script>
    $(document).ready(function() {

        $('.photo').attr('onclick', 'eventChangeClick()');
    });
    function test() {
        var selection = '';
        var temp = null;
        var eventListener = /iPhone|iPad|iPod/i.test(navigator.userAgent)
            ? 'touchend'
            : 'mouseup';
        document.getElementById('content').addEventListener('touchstart', function () {

        });
        document.getElementById('content').addEventListener('touchmove', function () {

        });
        if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
             
                if (window.getSelection) {
                    selection = window.getSelection();
                } else if (document.selection) {
                    selection = document.selection.createRange();
                }
                selection.toString() !== '';
                 var section = '{{ $section }}';
                 if (section === 'photo' || section == 'availability') return;
                var parrentNode = window.getSelection().anchorNode.parentNode;
                var currentHTMLSection = $('#content div').html();

                if ($(parrentNode).html() == $('#content div').html()) {
                    selection = window.getSelection().getRangeAt(0).toString();
                }


               
                var user_id = '{{ $user_id }}';
                var token = window.location.href.split('?')[1];

                if (selection.toString() !== '' && selection !== ' ') {

                    $('#buttons').show();
                    $('#buttons .dropdown-menu').show();
                    var top = $(document).scrollTop() - 30;
                    $('#buttons').css({'top': top, position : 'absolute', width: '70%'});
                    var clonedSlection = window.getSelection().getRangeAt(0).cloneRange().cloneContents();
                    var span = document.createElement('span');
                    span.appendChild(clonedSlection);
                    var replace = span.innerHTML;
                    $(document).off('click', '#manual').on('click', '#manual', function () {
                    
                        var answer = confirm('This option will delete your selected text!');
                        if ( ! answer) return;
                        if ($(parrentNode).html() == $('#content div.'+section).html()) {
                            
                            var temp = $(parrentNode).html().replace(new RegExp(replace, "g"), '');

                            $('#content div.'+section).html(temp);
                        } else {
                             var replaceHTML = $(parrentNode).html().replace(new RegExp(replace, "g"), "");
                            parrentNode.innerHTML = replaceHTML;
                        }
                        $('#buttons').hide();
                     
                    });
                    $(document).off('change', 'select').on('change', 'select', function () {
                        $('#buttons').hide();
                       
                        if ($(parrentNode).html() == $('#content div').html()) {
                            if ($('#content div').html().indexOf(replace) != -1) {
                                var answer = confirm('This option will delete your text style!');

                                if (!answer) return;
                                
                                temp = '<div lang="' + section + '" contenteditable="true">'
                                        + $('#content div').html().replace(new RegExp(replace, "g"), $('select option:selected').val())
                                        + '</div>';
                                $('#content').html(temp);
                            }
                        } else {
                            var check = 0;
                            if ($(parrentNode).html().indexOf(replace) != -1) {
                                var replaceContent = $(parrentNode).html().replace(new RegExp(replace, "g"), $('select option:selected').val());
                                parrentNode.innerHTML = replaceContent;
                                check = 1;
                            } 
                            if (parrentNode.innerHTML == replace) {
                                replaceContent = $(parrentNode).html().replace(replace, $('select option:selected').val());
                                 parrentNode.innerHTML = replaceContent;
                            } else {
                                var replaceContent = $('#content div').html().replace(replace, $('select option:selected').val());
                                $('#content div').html(replaceContent);
                            }
                        }
                        $(this).find('option').removeAttr('disabled');
                        $(this).find('option').removeAttr('selected'); 
                        $('select option:eq(0)').attr('selected');
                        $('select option:eq(0)').attr('disabled', true);
                    });
                }
        } else {
            document.getElementById('content').addEventListener(eventListener, function () {
                if (window.getSelection) {
                    selection = window.getSelection();
                } else if (document.selection) {
                    selection = document.selection.createRange();
                }
                selection.toString() !== '';
                 var section = '{{ $section }}';
                 if (section === 'photo' || section == 'availability') return;
                var parrentNode = window.getSelection().anchorNode.parentNode;
                var currentHTMLSection = $('#content div').html();

                if ($(parrentNode).html() == $('#content div').html()) {
                    selection = window.getSelection().getRangeAt(0).toString();
                }


               
                var user_id = '{{ $user_id }}';
                var token = window.location.href.split('?')[1];

                if (selection.toString() !== '' && selection !== ' ') {

                    $('#buttons').show();
                    $('#buttons .dropdown-menu').show();
                    var top = $(document).scrollTop() - 30;
                    $('#buttons').css({'top': top, position : 'absolute', width: '70%'});
                    var clonedSlection = window.getSelection().getRangeAt(0).cloneRange().cloneContents();
                    var span = document.createElement('span');
                    span.appendChild(clonedSlection);
                    var replace = span.innerHTML;
                    $(document).off('click', '#manual').on('click', '#manual', function () {
                    
                        var answer = confirm('This option will delete your selected text!');
                        if ( ! answer) return;
                        if ($(parrentNode).html() == $('#content div.'+section).html()) {
                            
                            var temp = $(parrentNode).html().replace(new RegExp(replace, "g"), '');

                            $('#content div.'+section).html(temp);
                        } else {
                             var replaceHTML = $(parrentNode).html().replace(new RegExp(replace, "g"), "");
                            parrentNode.innerHTML = replaceHTML;
                        }
                        $('#buttons').hide();
                     
                    });
                    $(document).off('change', 'select').on('change', 'select', function () {
                        $('#buttons').hide();
                        
                        if ($(parrentNode).html() == $('#content div').html()) {
                            console.log('1');
                            if ($('#content div').html().indexOf(replace) != -1) {
                                var answer = confirm('This option will delete your text style!');

                                if (!answer) return;
                                
                                temp = '<div lang="' + section + '" contenteditable="true">'
                                        + $('#content div').html().replace(new RegExp(replace, "g"), $('select option:selected').val())
                                        + '</div>';
                                $('#content').html(temp);
                            }
                        } else {
                           /* var check = 0;
                            if ($(parrentNode).html().indexOf(replace) != -1) {
                                var replaceContent = $(parrentNode).html().replace(new RegExp(replace, "g"), $('select option:selected').val());
                                parrentNode.innerHTML = replaceContent;
                                check = 1;
                            } */
                            if (parrentNode.innerHTML == replace) {
                                replaceContent = $(parrentNode).html().replace(replace, $('select option:selected').val());
                                 parrentNode.innerHTML = replaceContent;
                            } else {
                                var replaceContent = $('#content div').html().replace(replace, $('select option:selected').val());
                                $('#content div').html(replaceContent);
                            }
                        }
                        $(this).find('option').removeAttr('disabled');
                        $(this).find('option').removeAttr('selected'); 
                        $('select option:eq(0)').attr('selected');
                        $('select option:eq(0)').attr('disabled', true);
                    });
                }
            });
        }
    }
    test();
        function changeAvatar() {

        }
        var isBusy = false;
        var app = {};
        function eventChangeClick() {
            alert('Change Photo');
            Android.changeAvatar();
        }
        function clickSave() {
            if (isBusy) return;
            isBusy = true;
            $("#loading").show();
            var url = window.location.href;
            var token = url.split('=');

            var content = $('#content select').length == 1 ? $('select option:selected').val()
                    : $('#content').html();

            content = content.replace(/\t|\n+/g, '');

            $.ajax({
                url: url,
                data: {
                    token: token[1],
                    content: content
                },
                type: 'POST',
                success: function (result) {
                    if (result.status_code == 200) {
                        alert('Edit content Successfully!');
                    }
                    $("#loading").hide();
                }
            }).always(function () {
                isBusy = false;
            });

            // });
        }
        function clickApply() {
            var answer = confirm('If you choose this action, it will may change style of section!');

            if (!answer) return;

            var url = window.location.href;
            var token = url.split('=');
            if (isBusy) return;
            $("#loading").show();
            isBusy = true;
            $.ajax({
                url: '/api/template/apply/{{ $template->id }}/{{ $section}}?token=' + token[1],
                type: 'GET',
                success: function (result) {
                    $("#loading").hide();
                    if (result.status_code == 200) {
                        if ($('#content select').length == 1) {
                            $('#content select li[value = ' + result.data + ']').attr('selected');


                        } else $('#content').html(result.data);

                        alert('Apply data from Profile successfully!');
                    }
                }
            }).always(function () {
                isBusy = false;
            });
        }

        function getFromProfile() {
            var section = "{{ $section }}";
            $.ajax({
                url: "{{ route('api.template.get.fromprofile', [$template->id, $section]) }}",
                data: {
                    token: document.location.href.split('?token=')[1],
                    content: '<div contenteditable="true" class="' + section + '">' + $('.' + section).html() + '</div>'
                },
                type: "POST",
                success: function (result) {

                    alert('Successfully!');
                }
            });
        }
        
         $(document).ready(function() {
            $('.close').click(function() {
                $('#buttons').hide();
            });
         });
    </script>
@stop