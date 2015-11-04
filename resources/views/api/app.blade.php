<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="charset=utf-8" />
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	@yield('css')
</head>
<body>
	<div class="container">
		@yield('content')
	</div>
	<script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{  asset('js/nicEdit.js') }}"></script>
	@yield('scripts')
	
</body>
</html>