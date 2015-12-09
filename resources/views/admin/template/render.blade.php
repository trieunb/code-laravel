<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="charset=utf-8" />
	<meta charset="UTF-8">
	<title></title>
	@unless(\Auth::user()->is('admin'))
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/fonts.css">
		<link rel="stylesheet" href="css/style.css">
	@endunless
	<style>
		body{font-family: 'dejavu sans';}
	</style>
</head>
<body>
	<div class="container">
		{!! $content !!}
	</div>
	<script src="js/jquery-2.1.4.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
		

	