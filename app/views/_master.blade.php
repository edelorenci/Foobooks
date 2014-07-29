<!doctype html>
<html>
<head>

	<title>@yield('title','Foobooks')</title>

	<link href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/flatly/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="styles/foobooks.css" type="text/css">

	@yield('head')

</head>

<body>

	@if(Session::get('flash_message'))
		<div class='flash-message'>{{ Session::get('flash_message') }}</div>
	@endif

	<a href='/'><img class='logo' src='<?php echo URL::asset('/images/logo@2x.png'); ?>' alt='Foobooks Logo'></a>

	@yield('content')

	@yield('body')

</body>

</html>