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
		<div class="panel">
			<div class="panel-heading">Send A Picture</div>
			<div class="panel-body">
				<form method="post" action="/{{$name}}/pic">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<div class="row">
						<div class="col-sm-5">
							<img src="{{ $image }}" class="img-rounded">
							<input type="hidden" name="imageUrl" value="{{ $image }}">
						</div>

						<div class="col-sm-offset-2 col-sm-5">
							<label>Message</label>
							<input class="form-control" type="text" name="message">
						</div>
					</div>
					<br>
					<div class="row">
						<button type="submit" class="btn btn-default">Send Picture</button>
						<a href="/{{$name}}/pic" class="btn btn-default">New Picture</a>
					</div>			
				</form>
			</div>
		</div>
		
	</div>

@stop