<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Resume</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" >
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }} " media="screen" title="no title" charset="utf-8">
  <link rel="stylesheet" href="{{ asset('css/fonts.css') }} " media="screen" title="no title" charset="utf-8">
  <link rel="stylesheet" href="{{ asset('css/style.css') }} " media="screen" title="no title" charset="utf-8">
  <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.5.3/less.min.js"></script> -->
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
  <script src="{{ asset('js/bootstrap.js') }}" ></script>
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
            <button class="btn-trans semi-bold start collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Edit</button>
            <button class="btn-trans fill semi-bold end collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">End edit</button>
          </div>
          <div class="fw collapse" id="collapseExample">
            <div class="content">
              <div class="title">
                <h4 class="text-center text-red">You are now in edit mode!</h4>
              </div>
              <div class="control">

              <ul class="list-unstyled list-inline">
                    <li >
                      <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="circle dropdown">
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
                      <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="circle dropdown">
                        <i class="fa fa-paint-brush"></i>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="circle dropdown">
                        <i class="fa fa-plus"></i>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="circle dropdown">
                        <i class="fa fa-cog"></i>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="circle dropdown">
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
        <div class="fw w_bg">
          <span>Push template here</span>
        </div>
      </div>
      <div class="fw text-center">
        <button class="btn-trans fill edit">
          END EDIT MODE
        </button>
      </div>
    </div>

  </main>

  <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>

</body>
</html>