<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="charset=utf-8" />
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<style>
		* {
		    -webkit-touch-callout: none;
		    -webkit-user-select: none;
		    -khtml-user-select: none;
		    -moz-user-select: none;
		    -ms-user-select: none;
		    user-select: none;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="col-xs-12">
			{!!  $content !!}
		</div>
	</div>
	<script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
	<script src="{{ asset('js/bootstrap.js') }}"></script>
	<script>
	$(document).ready(function() {
		$('div').removeAttr('contenteditable');
		$('table').removeAttr('width');
		$('table').css('width', 'auto');
		$('table').css('margin', 'auto');
	});
</script>
</body>
</html>
		

	