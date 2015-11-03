@extends('frontend.app')

@section('title')
	Create Template
@endsection

@section('content')
    <div class="container">
            <div class="row">
                <div class="header">
                    <div class="image-avatar">
                    @if ( !empty($template->avatar['origin'] ))
                        <img id="image" src="{{ asset($template->avatar['origin']) }}">
                    @else
                        <img  id="image" src="{{ asset('images/avatar.jpg') }}">
                    @endif
                    <div class="text-info text-center">
                        <p class=full-name>Fullname:</p>
                        <span>Link Profile:</span><br>
                        <span>Email:</span>
                    </div>
                    </div>
                </div>
                <div class="info text-center">
                    <span>Address:</span><br>
                    <span>City: - State: </span><br>
                    <span>Tell: </span>
                </div>
                <div class="content">
                    <div class="content-box">
                        <div class="header-title">
                            <span>Infomation</span>
                        </div>
                        <div class="box">
                            <span>Infomation ...</span>
                        </div>
                    </div>
                    <div class="content-box">
                        <div class="header-title">
                            <span>Education</span>
                        </div>
                        <div class="box">
                            <label>School Name:</label>
                          
                        </div>
                    </div>
                    <div class="content-box">
                        <div class="header-title">
                            <span>Work Experience</span>
                        </div>
                        <div class="box">
                            <label>Company:</label>
                            
                        </div>
                    </div>
                    <div class="content-box">
                        <div class="header-title">
                            <span>Skills</span>
                        </div>
                        <div class="box">
                           
                            <p>
                              <label for="amount">Text Size:</label>
                            </p>
                            <div id="slider-range-min"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	
@stop