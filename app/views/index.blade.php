@extends('_master')

@section('title')
	Welcome to Foobooks
@stop

@section('content')

	<h1>Welcome to Foobooks</h1>

	@if(Auth::check())
		<a href='/logout'>Log out {{ Auth::user()->email; }}</a>
	@else 
		<a href='/signup'>Sign up</a> or <a href='/login'>Log in</a>
	@endif


	<br><br>

	<a href='/list'>View all Books</a> | 
	<a href='/add'>+ Add New book</a>

	<br><br>

	{{ Form::open(array('url' => '/list', 'method' => 'GET')) }}

		{{ Form::label('query','Search for a book:') }} &nbsp;
		{{ Form::text('query') }} &nbsp;
		{{ Form::submit('Search!') }}

	{{ Form::close() }}


@stop
