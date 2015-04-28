@extends('layout')
@section('content')

	@if (Session::has('success')) 
		<div class="alert alert-success">
			<p> {{ Session::get('success') }} </p>
		</div>
	@endif

	@if (Auth::check())
		<div class="container">
			<p>Welcome {{ $name }}</p>
		</div>
	@endif

@stop