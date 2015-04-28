@extends('layout')
@section('content')
	<div class="container">		
		@if (Session::has('success')) 
			<div class="alert alert-success">
			<p> {{Session::get('success')}} </p>
			</div>
		@endif
		
		@foreach ($errors->all() as $error) 
			<div class="alert alert-danger">
			<p>{{ $error }}</p>
			</div>
		@endforeach 
	</div>
	<div class="container">	
		<form method="post" action="/{{$name}}/comp">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<div class="form-group">
				<h2>{{ $compliment->compliment }}</h2 >
				<input type="hidden" name="compliment_id" value="{{ $compliment->id }}">
			</div>

			<div class="form-group">
				<label>Message</label>
				<input class="form-control" type="text" name="message">
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-default">Send Compliment</button>
				<a href="/{{$name}}/comp" class="btn btn-default">New Compliment</a>
			</div>

		</form>	
		
	</div>

@stop