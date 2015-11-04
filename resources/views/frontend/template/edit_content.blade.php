<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Edit</title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
</head>
<body>
	<div id="myPanel" style="width: 525px;"></div>
	<div id="editor" contenteditable="true" >{!! $content !!}</div>    
	<script src="{{  asset('js/jquery-2.1.4.js') }}"></script>
	<script src="{{  asset('js/nicEdit.js') }}"></script>
	<script>
		bkLib.onDomLoaded(function() {
          	var myNicEditor = new nicEditor();
          	myNicEditor.setPanel('myPanel');
          	myNicEditor.addInstance('editor');
	     });
	</script>
</body>
</html>