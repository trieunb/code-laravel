<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" >
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/fonts.css') }}" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-default">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                      <a class="logo" href="#"></a>
                    </div>
      
                    Collect the nav links, forms, and other content for toggling
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-left">
                            <li class="active"><a href="#">My Templates </a></li>
                            <li><a href="#">Explore</a></li>
                            <li><a href="#">Sales</a></li>
                            <li><a href="#">Support</a></li>
      
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="av av_22">
                                        <img src="{{ asset('images/avatar.jpg') }}" alt="" />
                                    </span>
                                    Hi, keepfepang
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            @yield('content')
            
        </main>

        <footer>
            <div class="container">
                <div class="row linked">
                    <div class="col-md-3">
                        <a href="" class="text-center"><img src="{{ asset('images/logo-white.png') }}" alt=""></a>
                        <p>
                            Resume Builder is a leading cloud-based web and app development platform with millions of users worldwide. We make it simple for everyone to create a beautful professional resume. A prefect place for you to get your resume template business online.
                        </p>
                        <div class="box-social">
                            <ul class="list-unstyled list-inline">
                                <li><a href=""><i class="fa fa-facebook"></i></a></li>
                                <li><a href=""><i class="fa fa-twitter"></i></a></li>
                                <li><a href=""><i class="fa fa-google-plus"></i></a></li>
                                <li><a href=""><i class="fa fa-youtube"></i></a></li>
                                <li><a href=""><i class="fa fa-pinterest-p"></i></a></li>
                                <li><a href=""><i class="fa fa-instagram"></i></a></li>
                                <li><a href=""><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-3">
                            <h5>Product</h5>
                            <ul class="list-unstyled">
                                <li><a href="">Templates</a></li>
                                <li><a href="">Explore</a></li>
                                <li><a href="">Features</a></li>
                                <li><a href="">My Templates</a></li>
                                <li><a href="">Premium Plan</a></li>
                                <li><a href="">Online Store</a></li>
                                <li><a href="">App Market</a></li>
                                <li><a href="">Developers</a></li>
                            </ul>
                        </div>
                    <div class="col-md-3">
                        <h5>Company</h5>
                        <ul class="list-unstyled ">
                            <li><a href="">About</a></li>
                            <li><a href="">Investor Relations</a></li>
                            <li><a href="">Jobs</a></li>
                            <li><a href="">Design Assets</a></li>
                            <li><a href="">Terms of Use</a></li>
                            <li><a href="">Privacy</a></li>
                            <li><a href="">Abuse</a></li>
                            <li><a href="">Afilliates</a></li>
                            <li><a href="">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>Community</h5>
                        <ul class="list-unstyled">
                            <li><a href="">Blog</a></li>
                            <li><a href="">Stories</a></li>
                            <li><a href="">Facebook</a></li>
                            <li><a href="">Twitter</a></li>
                            <li><a href="">Google+</a></li>
                            <li><a href="">Pinterest</a></li>
                            <li><a href="">Youtube</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>Support</h5>
                        <ul class="list-unstyled list-inline">
                            <li><a href="">Support Centre</a></li>
                            <li><a href="">Training Videos</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row copyright">
                <div class="col-md-offset-1 col-md-9 text-center">
                    <p>
                    Promote your business, showcase your designs, set up an online shop or just test out new ideas, Resume Builder is a website and app builder that has everything you need to build a fully-personalized, high-quality resume. Browse our collection of beautiful website templates. You'll find loads of stunning design, ready to be customized.
                    </p>
                    <div >
                        &copy; 2015 Resume Builder.com inc
                    </div>
                </div>
            </div>
            </div>
        </footer>
        <script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" ></script>

        @yield('script_files')
        @yield('scripts')
    </body>
</html>