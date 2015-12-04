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
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.5.3/less.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
</head>
<body>

<main class="mobile">
    <div class="fw box-title">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4>Click and Create Your Amazing Resume</h4>
                </div>
                <div class="col-md-6 text-right edit">
                    <span>Price: Free</span>
                    <button class="btn-trans semi-bold">Read more</button>

                </div>
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
        <div class="row wrapper detail">
            <div id="content" class="fw w_bg">
                {!! $template->content !!}
            </div>
        </div>
        <div class="fw text-center">
            <button class="btn-trans fill edit" id="edit-template">
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
                    <li id="manual"><a>Type Manual</a></li>
                    <li>
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                            <select id="set-data" name="" class="form-control">


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
    var tmp = '';
    document.getElementById('content').addEventListener('touchstart', function () {

    });
    document.getElementById('content').addEventListener('touchmove', function () {


        var touches = event.changedTouches;

        document.getElementById('content').addEventListener('mouseup', function () {
        if (window.getSelection) {
            selection = window.getSelection();
        } else if (document.selection) {
            selection = document.selection.createRange();
        }
        selection.toString() !== '';
        var clonedSlection = selection.getRangeAt(0).cloneRange().cloneContents();
        var span = document.createElement('span');
        span.appendChild(clonedSlection);
        var replace = span.innerHTML;
        var parrentNode = window.getSelection().anchorNode.parentNode;
        var currentHTMLSection = $('#content div').html();

        if ($(parrentNode).html() == $('#content div').html()) {
            selection = window.getSelection().getRangeAt(0).toString();
        }
        var section = $(parrentNode).attr('class');
        var sections = ['name', 'address', 'phone',
            'email', 'profile_website', 'linkedin',
            'reference', 'objective', 'activitie',
            'work', 'education', 'photo', 'personal_test',
            'key_qualification', 'availability', 'infomation'
        ]

        var top = $(document).scrollTop() - 30;
        $('#buttons').css({'top': top, position : 'absolute', width: '70%'});
        if (sections.indexOf(section) == -1) {
          var context = $(parrentNode).parents()[0];
          var section = $(context).attr('class');
          
          if (sections.indexOf(section) == -1)
            return;
        }
        var user_id = "{{ \Auth::user()->id }}";
        var token = document.location.href.split('?');
        tmp = selection.toString();
        if (selection.toString() === '' || selection.toString() === " ") return;
        $(document).off('click', '#manual').on('click', '#manual', function () {
            var answer = confirm('This option will delete your text style!');

            if (!answer) return;
            parrentNode.innerHTML = '';
            $('#buttons').hide();
        });
        $(document).off('change', 'select').on('change', 'select', function () {
                $('#buttons').hide();
                if (tmp == '' || tmp == ' ' || tmp == null) return;
                if ($(parrentNode).html() == $('#content div.'+section).html()) {
                        if ( $(parrentNode).html().indexOf(replace) == -1) {
                            alert('Not found selected text!');
                            $(parrentNode).html(tmpHTML);
                            return;
                        }

                        var answer = confirm('This option will delete your selected text!');
                        if ( ! answer) return;
                        var temp = $(parrentNode).html().replace(new RegExp(replace, "g"), $('select option:selected').val());

                        $('#content div.'+section).html(temp);
                } else {
                    parrentNode.innerHTML = $('select option:selected').val();
                }
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
                    // parrentNode.innerHTML = result.data;
                    var f = section.charAt(0)
                            .toUpperCase();
                    f = f + section.substr(1);
                    html += '<optgroup label="' + f + '">';
                    html += '<option>' + result.data + '</option>';
                    html += '</optgroup>';
                } else {
                    $.each(result.data, function (key, val) {

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
                                    html += '<optgroup label="Work ' + k + '">';
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
                                html += '<optgroup label="Qualification ' + k + '">';
                                html += '<option>Content:' + obj.content + '</option>';
                                html += '</optgroup>';
                                break;
                            case 'objective':
                                html += '<optgroup label="Objective ' + k + '">';
                                html += '<option>Title:' + obj.title + '</option>';
                                html += '<option>Content:' + obj.content + '</option>';
                                html += '</optgroup>';
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
            });
    
 $(document).ready(function() {
            $('.close').click(function() {
                $('#buttons').hide();
            });
         });
</script>
</body>
</html>
