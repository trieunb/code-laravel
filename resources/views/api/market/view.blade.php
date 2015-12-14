<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="charset=utf-8" />
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/fonts.css">
	<link rel="stylesheet" href="css/style.css">
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
		{!!  $content !!}
	</div>
	<script src="js/jquery-2.1.4.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
	$(document).ready(function() {
		$('div').removeAttr('contenteditable');
	});
</script>
</body>
</html>
		

	