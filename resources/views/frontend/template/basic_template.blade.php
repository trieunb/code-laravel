<div class="container">
    <div class="row">
        <div class="profile">
        <div class="image-avatar" 
            style="position: relative;
            overflow: hidden;max-height:400;" contenteditable="true">
                <img style="width:100%;" class="photo" 
                    src="{{ ($template->avatar) 
                    ? asset($template->avatar['origin']) 
                    : asset('images/avatar.jpg') }}">
            <div class="text-info" 
                style="position: absolute;
                bottom: 30px;
                width: 100%;
                text-align:center;">
                <p style="font-size:30px;">{{$template->firstname . ' ' . $template->lastname}}</p>
                <span>{{$template->link_profile}}</span>
                <br>
                    <span>{{$template->email}}</span>
            </div>
        </div>
        <div class="address text-center" 
            style="background: #9b8578;
            color: white;
            font-weight:600;
            text-align:center;" contenteditable="true">
            <span>{{$template->address}}</span><br>
                    <span>{{($template->city)
                    ? $template->city . ', ' . $template->state
                    : null}}</span><br>
            <span>Tell: {{$template->mobile_phone}}</span>
        </div>
        <div class="info content-box" contenteditable="true">
            <div class="header-title" 
            style="color: red;
            font-weight:600;
            padding:15px;">
                <span>Infomation</span>
            </div>
            <div class="box" 
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                <ul style="list-style:none">
                    <li>
                        <label style="font-weight:600">Age: </label>{{$age}}
                    </li>
                    <li>
                        <label style="font-weight:600">Gender: </label>{{$template->gender ? 'Male' : 'Female'}}
                    </li>
                    <li>
                        <label style="font-weight:600">Info: </label>{{$template->infomation}}
                    </li>
                </ul>
            </div>
        </div>
        </div>
        <div class="education content-box" contenteditable="true">
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
            @foreach ($template->user_educations as $edu)
                <label style="font-weight:600;">{{$edu['title']}}</label>
                <ul style="">
                    <li>
                        <label style="font-weight:600">School: </label>{{$edu['school_name']}}
                    </li>
                    <li>
                        <label style="font-weight:600">Time: </label>{{$edu['start'] . '-' . $edu['end']}}
                    </li>
                    <li>
                        <label style="font-weight:600">Degree: </label>{{$edu['degree']}}
                    </li>
                </ul>
                <hr>
            @endforeach
            </div>
        </div>
        <div class="personal_test content-box" contenteditable="true">
            <div class="header-title"
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>Skills</span>
            </div>
            <div class="box"
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                @foreach($template->soft_skill as $sk)
                <ul style="list-style:none">
                    <li>
                        <label style="font-weight:600">Name: </label>{{$sk['question']}}
                    </li>
                    <li>
                        <label style="font-weight:600">Point: </label>{{$sk['value']}}
                    </li>
                </ul>
                <hr>
                @endforeach
            </div>
        </div>
        <div class="work content-box" contenteditable="true">
            <div class="header-title"
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>Work Experience</span>
            </div>
            <div class="box"
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                @foreach ($template->user_work_histories as $histories)
                <label style="font-weight:600;">{{$histories['job_title']}}</label>
                <ul style="">
                    <li>
                        <label style="font-weight:600">Company: </label>{{$histories['company']}}
                    </li>
                    <li>
                        <label style="font-weight:600">Time: </label>{{$histories['start'] . '-' . $edu['end']}}
                    </li>
                    <li>
                        <label style="font-weight:600">Description: </label>{{$histories['job_description']}}
                    </li>
                </ul>
                <hr>
                @endforeach
            </div>
        </div>
        <div class="reference content-box" contenteditable="true">
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
                @foreach ($template->references as $ref)
                <ul style="list-style:none">
                    <li>
                        <label style="font-weight:600">References: </label>{{$ref['reference'] }}
                    </li>
                    <li>
                        <label style="font-weight:600">Content: </label>{{$ref['content']}}
                    </li>
                </ul>
                <hr>
                @endforeach
            </div>
        </div>
        <div class="objectvie content-box" contenteditable="true">
            <div class="header-title"
                style="color: red;
                font-weight:600;
                padding:15px;">
                <span>Objective</span>
            </div>
            <div class="box"
                style="background: #f3f3f3;
                padding: 15px;
                border-top: 3px solid #D8D8D8;
                border-bottom: 3px solid #D8D8D8;">
                @foreach ($template->objectives as $obj)
                <ul style="list-style:none">
                    <li>
                        <label style="font-weight:600">Title: </label>{{$obj['title']}}
                    </li>
                    <li>
                        <label style="font-weight:600">Content: </label>{{$obj['content']}}
                    </li>
                </ul>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>