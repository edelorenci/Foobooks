@extends('_master')


@section('head')
	<link rel="stylesheet" href="foobooks.css" type="text/css">
@stop

@section('title')
	All your Books
@stop

@section('content')

	View as:
	<a href='/list/json' target='_blank'>JSON</a> | 
	<a href='/list/pdf' target='_blank'>PDF</a>

	<br><br>

	@if(!empty(trim($query)))
		<p>You searched for <strong>{{{ $query }}}</strong></p>

		@if(count($books) == 0)
			<p>No matches found</p>
		@endif

	@endif

	@foreach($books as $title => $book)

		<section>
			<img class='cover' src='{{ $book['cover'] }}'>

			<h2>{{ $book['title'] }}</h2>

			By {{ $book['author'] }}

			<p>
				{{ $book['published'] }}
			</p>

			<p>

			</p>

			<a href='{{ $book['purchase_link'] }}'>Purchase this book...</a>

		</section>

	@endforeach

@stop