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
            width: 1100px !important;
        }
    </style>
</head>
<body>
    <div id="loading" class="mobile">
        <img class="img-responsive" src="{{ asset('images/loading.gif') }}" alt="">
    </div>
<main class="mobile">

    <div class="fw box-title">
        <div class="container">
            <div class="row">
                <div class="fw" id="collapseExample">
                    <div class="content">
                        <div class="title">
                            <h4 class="text-center text-red">You are now in edit mode!</h4>
                        </div>
                        <div class="control">

                            <ul class="list-unstyled list-inline">
                                <li id="fix-iphone">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                       class="circle dropdown">
                                        <i class="fa fa-list-alt"></i>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dLabel">
                                        <div class="top">
                                            <span class="close">x</span>
                                            <h4>Pages</h4>

                                            <p>Choose the element you want to edit</p>
                                        </div>
                                        {!! createSectionMenu($section, $token) !!}

                                    </div>

                                </li>
                                <li>
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                       class="circle dropdown">
                                        <i class="fa fa-paint-brush"></i>
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                       class="circle dropdown">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                       class="circle dropdown">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                       class="circle dropdown">
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
            <div id="content" class="fw w_bg">
                <div class="col-xs-12">
                    {!! $template->content !!}
                </div>
            </div>
        <br>
        <br>
        <div class="fw text-center">
            <button class="btn-trans fill edit" id="edit-template" onclick="clickEditTemplate()">
                END EDIT MODE
            </button>
        </div>
    </div>

    <div class="col-md-12" id="buttons">
        <ul class="dropdown-menu" aria-labelledby="dLabel" id="choose-type">

            <div class="" aria-labelledby="dLabel">
                <div class="top">
                    <span class="close">x</span>

                    <p>Choose the element you want to edit</p>
                </div>
                <ul class="list list-unstyled">
                    <li id="manual" onClick=""><a>Type Manual</a></li>
                    <li>
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                            <select id="set-data" name="" class="">


                            </select>
                        </a>
                    </li>
                </ul>
            </div>

        </ul>

    </div>

     
</main>

<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/js/edit_section_temp.js')}}"></script>
<script>

    function eventChangeClick() {
        alert('Change Photo');
        Android.changeAvatar();
    }
    $('div[lang=photo]').attr('onclick', 'eventChangeClick()');
    var tmp = '';
    var eventListener = '';
    var fixIOS = 'mouseup';
    if( /iPhone|iPad|iPod/i.test(navigator.userAgent) ) {
        eventListener = 'touchend';
        fixIOS = 'touchmove';
    } else eventListener = 'mouseup';
        document.getElementById('content').addEventListener('touchstart', function () {

        });
        // document.getElementById('content').addEventListener(fixIOS, function () {


           
            function test() {
    

                document.getElementById('content').addEventListener(eventListener, function () {
                    var touches = event.changedTouches;
                    if (window.getSelection) {
                        selection = window.getSelection();
                    } else if (document.selection) {
                        selection = document.selection.createRange();
                    }
                    $('div[contenteditable="true"]').removeClass('highlight');

                    selection.toString() !== '';
                    var clonedSlection = '';
                    try {
                        clonedSlection = selection.getRangeAt(0).cloneRange().cloneContents();
                    } catch(err) {
                        return;
                    }
                    
                    var span = document.createElement('span');
                    span.appendChild(clonedSlection);

                    var replace = span.innerHTML;

                    var parrentNode = window.getSelection().anchorNode.parentNode;

                    var currentHTMLSection = $('#content div').html();

                    if ($(parrentNode).html() == $('#content div').html()) {
                        selection = window.getSelection().getRangeAt(0).toString();
                    }
                    var section = $(parrentNode).attr('lang');

                    var sections = ['name', 'address', 'phone',
                        'email', 'profile_website', 'linkedin',
                        'reference', 'objective', 'activitie', 'skill',
                        'work', 'education', 'photo', 'personal_test',
                        'key_qualification', 'availability', 'infomation'
                    ]

                    var top = $(document).scrollTop() - 30;
                    $('#buttons').css({'top': top, position : 'absolute', width: '70%'});
                    if (sections.indexOf(section) == -1) {

                     
                      $.each( $(parrentNode).parents(), function(key, val) {
                        
                         var context = val;
                         var tmp = $(context).attr('lang');

                         if (sections.indexOf(tmp) != -1) {
                            section = tmp;
                         }
                      });
                      if (sections.indexOf(section) == -1)
                        return;
                    }
                    if (section == 'availability') return;
                    $('div[lang='+section+']').addClass('highlight');
                    var user_id = "{{ $user->id }}";
                    var token = document.location.href.split('?');
                    tmp = selection.toString();
                   
                    if (selection.toString() === '' || selection.toString() === " ") return;
                    $(document).off('click', '#manual').on('click', '#manual', function () {
                        var answer = confirm('This option will delete your selected text!');
                        if ( ! answer) return;
                          var temp = $(parrentNode).html().replace(new RegExp(replace, "g"), '');
                        if ($(parrentNode).html() == $('#content div.'+section).html()) {

                            $('#content div[lang='+section+']').html(temp);
                        } else {
                            parrentNode.innerHTML = temp;
                        }
                        $('#buttons').hide();

                        $('div[lang='+section+']').removeClass('highlight');
                    });
                    $(document).off('change', 'select').on('change', 'select', function () {
                        $('#buttons').hide();

                        if (tmp == '' || tmp == ' ' || tmp == null) return;
                        if ($(parrentNode).html() == $('#content div[lang'+section+']').html()) {
                                if ( $(parrentNode).html().indexOf(replace) == -1) {
                                    alert('Not found selected text!');
                                    $(parrentNode).html(tmpHTML);
                                    return;
                                }

                                var answer = confirm('If you choose this action, it will may change style of section!');
                                if ( ! answer) return;
                                var temp = $(parrentNode).html().replace(new RegExp(replace, "g"), $('select option:selected').val());

                                $('#content div[lang='+section+']').html(temp);
                        } else {
                            // if (parrentNode.innerHTML.indexOf(replace) != -1) {
                            //     var replaceContent = $(parrentNode).html().replace(new RegExp(replace, "g"), $('select option:selected').val());
                            //     parrentNode.innerHTML = replaceContent;
                            // }
                            if (parrentNode.innerHTML == replace) {
                                var replaceContent = $(parrentNode).html().replace(replace, $('select option:selected').val());
                                parrentNode.innerHTML = replaceContent;
                            } else {
                                var replaceContent = $('#content div[lang='+section+']').html().replace(replace, $('select option:selected').val());
                                $('#content div[lang='+section+']').html(replaceContent);
                            }

                        }
                        $('div[lang='+section+']').removeClass('highlight');
                    });

                    $.ajax({
                        url: "/api/user/" + user_id + "/" + section + "?" + token[1],
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (result) {
                            $('#buttons').show();
                            $('#buttons .dropdown-menu').show();
                            var html = '';

                            html += '<option disabled selected>Get From Profile</option>';
                            if (typeof(result.data) !== 'object') {
                            
                                var f = section.charAt(0)
                                        .toUpperCase();
                                f = f + section.substr(1);
                                html += '<optgroup label="' + f + '">';
                                html += '<option>'+ result.data + '</option>';
                                html += '</optgroup>';
                            } else {
                                $.each(result.data, function (key, val) {
                                    if (val.length == 0) {
                                        $('#choose-type ul > li:last-child').html('');
                                    }
                                    switch (key) {

                                        case 'education':
                                            $.each(val, function (k, obj) {
                                                html += '<optgroup label="Education ' + k + '">';
                                                html += '<option>Title:' + obj.title + '</option>';
                                                html += '<option>School:' + obj.school_name + '</option>';
                                                html += '<option>Start:' + obj.start + '</option>';
                                                html += '<option>End:' + obj.end + '</option>';
                                                html += '<option>Degree:' + obj.degree + '</option>';
                                                html += '<option>Result:' + obj.result + '</option>';
                                                html += '</optgroup>';
                                            });
                                            break;
                                        case 'work':
                                            $.each(val, function (k, obj) {
                                                html += '<optgroup label="Experience ' + k + '">';
                                                html += '<option>Company:' + obj.company + '</option>';
                                                html += '<option>SubTitle:' + obj.sub_title + '</option>';
                                                html += '<option>Start:' + obj.start + '</option>';
                                                html += '<option>End:' + obj.end + '</option>';
                                                html += '<option>Job title:' + obj.job_title + '</option>';
                                                html += '<option>Job description:' + obj.job_description + '</option>';
                                                html += '</optgroup>';
                                            });
                                            break;
                                        case 'reference':
                                            $.each(val, function (k, obj) {
                                                html += '<optgroup label="Reference ' + k + '">';
                                                html += '<option>Reference:' + obj.reference + '</option>';
                                                html += '<option>Content:' + obj.content + '</option>';
                                                html += '</optgroup>';
                                            });

                                            break;
                                        case 'key_qualification':
                                        $.each(val, function (k, obj) {
                                            html += '<optgroup label="Qualification ' + k + '">';
                                            html += '<option>Content:' + obj.content + '</option>';
                                            html += '</optgroup>';
                                        });
                                            break;
                                        case 'objective':
                                        $.each(val, function (k, obj) {
                                            html += '<optgroup label="Objective ' + k + '">';
                                            html += '<option>Title:' + obj.title + '</option>';
                                            html += '<option>Content:' + obj.content + '</option>';
                                            html += '</optgroup>';
                                        });
                                            break;
                                        default:
                                            break;
                                    }

                                });
                            }

                            $('#set-data').html(html);


                        }
                    });
                
                });
            }
            test();
        // });
    
    
 $(document).ready(function() {
    $('.close').click(function() {
        $('#buttons').hide();
    });
    
 });
</script>
</body>
</html>
