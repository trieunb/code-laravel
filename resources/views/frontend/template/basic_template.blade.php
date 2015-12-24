<div class="container" style="
    word-wrap: break-word; 
    margin: 0 auto;
    font-family: Arial, Helvetica, sans-serif;">
    <div class="row">
        <div class='image-avatar' style="position: relative; overflow:hidden;">
            @if ( ($user_info->avatar['origin']))
            <div lang="photo" onclick="eventChangeClick()">
                <img style="height: auto;" 
                src="{!! asset($user_info->avatar['origin']) !!}" >
            </div>
            @endif
            <div class="text-info" >
                <div class="info-basic" 
                    style="@if($user_info->avatar['origin'])position: absolute; bottom: 0px; @endif 
                    margin-bottom:10px;
                    text-align: center;
                    width: 100%;">
                <div lang="name" >
                    <p style="font-size:30px;">{{$user_info->firstname . ' ' . $user_info->lastname}}</p>
                </div>
                <div lang="profile_website">
                    <span>{{$user_info->link_profile}}</span>
                </div>
                <div lang="email">
                    email: <span>{{$user_info->email}}</span>
                </div>
                </div>
            </div>
        </div>
    <div class="text-center" 
        style="background: #9b8578;
        color: white;
        font-weight:600;
        text-align:center;">
        <div lang="address">
            <span>{{$user_info->address}}</span><br>
            <span>{{($user_info->city)
                ? $user_info->city . ', ' . $user_info->state
                : null}}
            </span>
        </div>
        <div lang="phone">
            <span>Tell: {{$user_info->mobile_phone}}</span>
        </div>
    </div>
    <div lang="infomation">
        <div class='content-box'>
            <div class="header-title"
            style="color: red;
            font-weight:600;
            padding:15px;">
                <span>Personal Infomation</span>
            </div>
            <div class="box" 
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                <ul style="list-style:none">
                    <li>
                        <label style="font-weight:600">Age:</label> {{ $age == '0' || $age == '' ? 'N/A' : $age}}
                    </li>
                    <li></h1>
                        <?php
                            $gender = '';
                            
                            if (is_null($user_info->gender)) {
                                $gender = 'N/A';
                            } else {
                                switch ($user_info->gender) {
                                    case 0:
                                        $gender = 'Male';
                                        break;
                                    case 1:
                                        $gender = 'Female';
                                        break;
                                    case 2:
                                        $gender = 'Other';
                                        break;
                                    
                                    default:
                                        $gender = 'N/A';
                                        break;
                                }
                            }
                        ?>
                        <label style="font-weight:600">Gender:</label> {{ $gender }}
                    </li>
                    <li>
                        <label style="font-weight:600">Info:</label> {{$user_info->infomation}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div lang="education">
        <div class='content-box'>
            <div class="header-title"
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>Education</span>
            </div>
            <div class="box"
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                @foreach ($user_info->user_educations as $edu)
                <label style="font-weight:600;">{{$edu['title']}}</label>
                <ul style="">
                    <li>
                        <span><label style="font-weight:600">School: </label>{{$edu['school_name']}}</span>
                    </li>
                    <li>
                        <span><label style="font-weight:600">Time: </label>{{$edu['start'] . '-' . $edu['end']}}</span>
                    </li>
                    <li>
                        <span><label style="font-weight:600">Degree: </label>{{$edu['degree']}}</span>
                    </li>
                </ul>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
    <div lang="personal_test">
         <div class='content-box'>
        <div class="header-title"
            style="color: red;
            font-weight:600;
            padding:15px;">
            <span>Career Growth Test</span>
        </div>
        <div class="box"
            style="background: #f3f3f3;
            padding: 15px;
            border-top: 3px solid #D8D8D8;
            border-bottom: 3px solid #D8D8D8;">
            @foreach($user_info->questions as $sk)
            <ul style="list-style:none">
                <li>
                    <span>{{$sk->pivot['content']}}</span>
                </li>
                <li>
                    <input type="range" min="10" max="1000" step="10" value="{{ $sk->pivot['point'] }}" data-rangeslider="" 
                    style="position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0;">
                    <div class="rangeslider rangeslider--horizontal" id="js-rangeslider-5"
                        style="height: 5px;
                        width: 100%;
                        background: #e6e6e6;
                        position: relative;">
                        <div class="rangeslider__fill"
                        style="width: {{ $sk->pivot['point']*10 }}%;
                        background: #FF0000;
                        position: absolute;
                        top: 
                        0;height: 100%;"></div>
                    </div>
                </li>
            </ul>
            <hr>
            @endforeach
        </div>
    </div>
    </div>
    <div lang="work">
        <div class=' content-box'>
            <div class="header-title"
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>Experience</span>
            </div>
            <div class="box"
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                @foreach ($user_info->user_work_histories as $histories)
                <label style="font-weight:600;">{{$histories['job_title']}}</label>
                <ul style="">
                    <li>
                        <span><label style="font-weight:600">Company: </label>{{$histories['company']}}</span>
                    </li>
                    <li>
                        <span><label style="font-weight:600">Time: </label>{{$histories['start'] . '-' . $histories['end']}}</span>
                    </li>
                    <li>
                        <span><label style="font-weight:600">Description: </label>{{$histories['job_description']}}</span>
                    </li>
                </ul>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
    <div lang="reference">
        <div class='content-box'>
            <div class="header-title"
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>References</span>
            </div>
            <div class="box"
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                @foreach ($user_info->references as $ref)
                <ul style="list-style:none">
                    <li>
                        <span><label style="font-weight:600">References: </label>{{$ref['reference'] }}</span>
                    </li>
                    <li>
                        <span><label style="font-weight:600">Content: </label>{{$ref['content']}}</span>
                    </li>
                </ul>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
    <div lang="objective">
        <div class='content-box'>
            <div class="header-title"
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>Objectives</span>
            </div>
            <div class="box"
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                @foreach ($user_info->objectives as $obj)
                <ul style="list-style:none">
                    <li>
                        <span><label style="font-weight:600">Title: </label>{{$obj['title']}}</span>
                    </li>
                    <li>
                        <span><label style="font-weight:600">Content: </label>{{$obj['content']}}</span>
                    </li>
                </ul>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
    <div lang="key_qualification">
        <div class='content-box'>
            <div class="header-title"
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>Qualifications</span>
            </div>
            <div class="box"
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                @foreach ($user_info->qualifications as $qua)
                <ul style="list-style-type:disc;">
                    <li>
                        {{$qua['content']}}
                    </li>
                </ul>
                @endforeach
            </div>
        </div>
    </div>
    <div lang="availability">
        <div class='content-box'>
            <div class="header-title" 
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>Availability</span>
            </div>
            <div class="box" 
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                <p>
                    {{$user_info->status['value']}}
                </p>
            </div>
        </div>
    </div>
</div>
</div>