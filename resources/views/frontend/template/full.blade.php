<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Edit</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<div id="editor" contenteditable="true">{!! $content !!}</div>    
	<script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
	<script>
		$(document).ready(function() {
			$('#editor img').click(function() {
				alert('abcd');
			});
		});
	</script>
</body>
</html>