@extends('_master')

@section('title')
	Log in
@stop

@section('content')

	<h1>Log in</h1>

	{{ Form::open(array('url' => '/login')) }}

		Email<br>
		{{ Form::text('email') }}<br><br>

		Password:<br>
		{{ Form::password('password') }}<br><br>

		{{ Form::submit('Submit') }}

	{{ Form::close() }}

@stop