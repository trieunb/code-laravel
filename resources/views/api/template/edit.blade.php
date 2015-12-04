@extends('api.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }} " media="screen" title="no title"
          charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }} " media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }} " media="screen" title="no title" charset="utf-8">

    @stop

    @section('content')
            <!-- <div id="myPanel" style=""></div> -->
    <div id="loading">
        <img class="img-responsive" src="{{ asset('images/loading.gif') }}" alt="">
    </div>
    <div class="row">
        <form id="upload">
            <div id="content" class="col-md-12" contenteditable="true">

                @if ($section != 'availability')
                    {!! $content !!}
                @else
                    {!! Form::select('availability', $setting, $template->user->status, ['class' => 'form-control']) !!}
                @endif

            </div>
        </form>
    </div>
    <div class="col-md-12" id="buttons">
        <ul class="dropdown-menu" aria-labelledby="dLabel" id="choose-type">

            <div id="get-from-profile" class="" aria-labelledby="dLabel">
                <div class="top">
                    <span class="close">x</span>

                    <p>Choose the element you want to edit</p>
                </div>
                <ul class="list list-unstyled">
                    <li id="manual"><a>Type Manual</a></li>
                    <li>
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                            <select name="" class="form-control">
                                <option value="" disabled selected>Get From Profile</option>
                                {!!$template->present()->createMenuProfile($user_id, $section) !!}
                            </select><!-- <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            <div class="dropdown-menu" aria-labelledby="dLabel">
                            <ul class="list list-unstyled ">
                                {!!$template->present()->createMenuProfile(\Auth::user()->id, $section) !!}
                                    </ul>
                                </div> -->
                        </a>
                    </li>
                </ul>
            </div>

        </ul>
        <!-- <div class="dropdown">
            <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Choose Type Edit
                <span class="caret"></span>
            </button>

        </div> -->

    </div>


@stop

@section('scripts')
    <script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
    {{-- <script src="{{  asset('js/ckeditor/ckeditor.js') }}"></script> --}}
    <script src="{{  asset('js/nicEdit.js') }}"></script>
    <script src="{{  asset('js/main.js') }}"></script>

    <script>
        function test() {
            var selection = '';
            var temp = null;

            document.getElementById('content').addEventListener('touchstart', function () {

            });
            document.getElementById('content').addEventListener('touchmove', function () {

            });
            document.getElementById('content').addEventListener('touchend', function () {
                if (window.getSelection) {
                    selection = window.getSelection();
                } else if (document.selection) {
                    selection = document.selection.createRange();
                }
                selection.toString() !== '';
                var parrentNode = window.getSelection().anchorNode.parentNode;
                var currentHTMLSection = $('#content div').html();

                if ($(parrentNode).html() == $('#content div').html()) {
                    selection = window.getSelection().getRangeAt(0).toString();
                }

                var section = '{{ $section }}';
                var user_id = '{{ \Auth::user()->id }}';
                var token = window.location.href.split('?')[1];

                if (selection.toString() !== '' && selection !== ' ') {

                    $('#buttons').show();
                    $('#buttons .dropdown-menu').show();

                    // var html = '<div class="dropdown"><button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown trigger<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dLabel"><li id="manual">Type Manual</li><li id="get-profile">Get from profile</li></ul></div>';
                    // $('#buttons').html(html);

                    $(document).off('click', '#manual').on('click', '#manual', function () {
                        var answer = confirm('This option will delete your selected text!');

                        if (!answer) return;
                        parrentNode.innerHTML = '';
                        $('#buttons').hide();
                    });
                    $(document).off('change', 'select').on('change', 'select', function () {
                        $('#buttons').hide();
                        if ($(parrentNode).html() == $('#content div').html()) {
                            if (currentHTMLSection.indexOf(selection) != 0) {
                                var answer = confirm('This option will delete your text style!');

                                if (!answer) return;
                               
                                temp = '<div class="' + section + '" contenteditable="true">'
                                        + $('#content div').text().replace(new RegExp(selection, "g"), $('select option:selected').val())
                                        + '</div>';
                                $('#content').html(temp);
                            }
                        } else {
                            parrentNode.innerHTML = $('select option:selected').val();
                        }
                    });

                    /*$.ajax({
                     url: "/api/user/"+user_id+"/"+section+'?'+token,
                     type: 'GET',
                     dataType: 'JSON',
                     success: function(result) {
                     var html = '<div class="dropdown"><button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown trigger<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dLabel"><li id="manual">Type Manual</li><li id="get-profile">Get from profile</li></ul></div>';
                     $('#buttons').html(html);

                     $('#manual').click(function() {
                     parrentNode.innerHTML = '';
                     $('#buttons').html('');
                     });
                     $('#get-profile').click(function() {
                     if (typeof(result.data) !== 'object') {
                     parrentNode.innerHTML = result.data;
                     }else {
                     var html = '';
                     $.each(result.data, function(key, val) {
                     html += '<select id="get-from-profile" class="form-control">';
                     html += '<option disabled>List Item</option>';
                     switch(key) {
                     case 'education':
                     $.each(val, function(k, obj) {
                     html += '<optgroup label="Item '+k+'">';
                     html += '<option>Title:'+obj.title+'</option>';
                     html += '<option>School:'+obj.school_name+'</option>';
                     html += '<option>Start:'+obj.start+'</option>';
                     html += '<option>End:'+obj.end+'</option>';
                     html += '<option>Degree:'+obj.degree+'</option>';
                     html += '<option>Result:'+obj.result+'</option>';
                     html += '</optgroup>';
                     });
                     break;
                     case 'work':
                     $.each(val, function(k, obj) {
                     html += '<optgroup label="Item '+k+'">';
                     html += '<option>Company:'+obj.company+'</option>';
                     html += '<option>SubTitle:'+obj.sub_title+'</option>';
                     html += '<option>Start:'+obj.start+'</option>';
                     html += '<option>End:'+obj.end+'</option>';
                     html += '<option>Job title:'+obj.job_title+'</option>';
                     html += '<option>Job description:'+obj.job_description+'</option>';
                     html += '</optgroup>';
                     });
                     break;
                     case 'reference':
                     $.each(val, function(k, obj) {
                     html += '<optgroup label="Item '+k+'">';
                     html += '<option>Reference:'+obj.reference+'</option>';
                     html += '<option>Content:'+obj.content+'</option>';
                     html += '</optgroup>';
                     });

                     break;
                     case 'key_qualification':
                     html += '<optgroup label="Item '+k+'">';
                     html += '<option>Content:'+obj.content+'</option>';
                     html += '</optgroup>';
                     break;
                     case 'objective':
                     html += '<optgroup label="Item '+k+'">';
                     html += '<option>Title:'+obj.title+'</option>';
                     html += '<option>Content:'+obj.content+'</option>';
                     html += '</optgroup>';
                     break;
                     default:
                     break;
                     }

                     html += '</select>';
                     });
                     $('#buttons').html(html);
                     $('#get-from-profile').click(function() {
                     if ($(parrentNode).html() == $('#content div').html()){
                     if (currentHTMLSection.indexOf(selection) != 0) {
                     temp = '<div class="'+section+'" contenteditable="true">'
                     +currentHTMLSection.replace(new RegExp(selection, "g"), $('#get-from-profile option:selected').val())
                     +'</div>';
                     $('#content').html(temp);
                     }
                     }else {
                     parrentNode.innerHTML =  $('#get-from-profile option:selected').val();
                     }

                     });
                     }

                     });

                     }
                     });*/
                }
            });
        }
        test();
        function editPhoto() {

        }
        var isBusy = false;
        /*$('img').click(function(e) {
         e.preventDefault();
         $('#file').trigger('click');
         });*/

        function clickSave() {
            // $('#save').click(function(e) {
            // e.preventDefault();
            if (isBusy) return;
            isBusy = true;
            $("#loading").show();
            var url = window.location.href;
            var token = url.split('=');

            var content = $('#content select').length == 1 ? $('select li:selected').val()
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
        // clickSave();
        // clickApply();
        /*  CKEDITOR.inline('editor',{
         on: {
         instanceReady: function() {
         this.document.appendStyleSheet( '{{ asset("js/ckeditor/contents.css") }}' );

         CKEDITOR.instances['editor'].on('focus', function() {
         var width = $(window).width();
         if (width >= 1331) {
         document.getElementsByTagName('div')[1].style.marginTop = "80px";
         } else if (width <= 440) {
         document.getElementsByTagName('div')[1].style.marginTop = "180px";
         }
         });
         CKEDITOR.instances['editor'].on('blur', function() {
         document.getElementsByTagName('div')[1].style.marginTop = "0px";
         });

         }
         }
         });

         CKEDITOR.instances.editor.setData("{!! $content !!}");*/

        /*bkLib.onDomLoaded(function() {
         var myNicEditor = new nicEditor();
         // new nicEditor({externalCSS : 'asset(css/style.css)'});
         myNicEditor.setPanel('myPanel');
         myNicEditor.addInstance('editor');
         });
         myNicEditor.addEvent('focus', function(e) {
         alert('abcde');
         });
         var isFocused, focusedResizing;
         window.tryfix = function() {
         var inputs = document.getElementsByTagName('input')
         for (var i = 0; i < inputs.length; i++) {
         input = inputs[i];
         input.onfocus = focused;
         input.onblur = blured;
         }
         window.onscroll = scrolled;
         }

         function focused(event) {
         isFocused = true;
         scrolled();
         }

         function blured(event) {
         isFocused = false;
         var headStyle = document.getElementById('hed').style;
         if (focusedResizing) {
         focusedResizing = false;
         headStyle.position = 'fixed';
         headStyle.top = 0;
         }
         }

         function scrolled() {
         document.title = 'test';
         var headStyle = document.getElementById('hed').style;
         if (isFocused) {
         if (!focusedResizing) {
         focusedResizing = true;
         headStyle.position = 'absolute';
         }
         headStyle.top = window.pageYOffset + 'px';
         }
         }*/
         $(document).ready(function() {
            $('.close').click(function() {
                $('#buttons').hide();
            });
         });
    </script>
@stop