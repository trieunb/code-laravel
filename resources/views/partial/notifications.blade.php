@if (session('success'))
    <div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>
      <i class="fa fa-check-circle fa-lg fa-fw"></i>  
    </strong>
    {{ session('success') }}
  </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>
      <i class="fa fa-check-circle fa-lg fa-fw"></i>  
    </strong>
    {{ session('error') }}
  </div>
@endif
@if (count($errors) > 0)
    <div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>
      <i class="fa fa-check-circle fa-lg fa-fw"></i>  
    </strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif