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

	<style type="text/css">

		.btn-block{
			padding-top: 50px;
			padding-bottom: 50px;
			background-color: #e95d22;
			opacity: 0.75;
			border: none;
		}
		.btn-block:hover{
			opacity: 0.25;
		}
		.panel-default{
			border:none;
		}
		.panel-img{
			height: 350px;
		}
		.panel-img-body{
			height: 300px;
		}
	</style>
	
	
	<div class="container">
	@if(Auth::check())
	<div class="col-sm-12">	
		<div class="panel">
			<div class="panel-heading">
				<h3 class="media-heading">Welcome {{ $name }}</h3>
			</div>
			<div class="panel-body">
				<div class="row-fluid">
					<div class="col-xs-12">
						
					</div>
				</div>
				<br>
				<div class="row-fluid">
					<div class="col-xs-4">
						<div class="media">
							<div class="media-left">								
								<img class="media-object" src="{{ $avatar }}">
							</div>
							<div class="media-body">								
								Complimentr isolates the good qualities of social media: nice things and cute pictures of animals. 
								Look below or press one of the buttons to the right to get started by sharing nice words or pictures.
							</div>
						</div>
					</div>
					<div class="col-xs-8">	
						<div class="container-fluid">
							<div class="col-sm-6">											
								<a href="/{{ $name }}/pic" class="btn btn-block btn-primary">							
									<h4 style="color: white"><i><span class="glyphicon glyphicon-picture"></span> Picture</i></h4>							
								</a>				
							</div>
							<div class="col-sm-6">											
								<a href="/{{ $name }}/comp" class="btn btn-block btn-primary">							
									<h4><i><span class="glyphicon glyphicon-text-size"></span> Compliment</i></h4>							
								</a>				
							</div>
						</div>					
												
					</div>
					
				</div>
			</div>				
		</div>
	</div>			
	@endif
	</div>

	<br>

	<div class="container">
	@foreach($feed as $item)
		@if($item->type == 'image')
			<div class="col-sm-4">
			<div class="panel">
				<div class= "panel-heading">
					<p><span class="glyphicon glyphicon-picture"></span> {{ $item->name }} to {{ $item->reciever }} at {{ $item->time_created }}:</p>
					<h4>{{ $item->message }}</h4>
				</div>
				<div class="panel-body ">
					<div class="row">
						<div class="col-sm-12"><img class="img-responsive img-rounded" src="{{ $item->image }}"></div>
					</div>								
				</div>
				<div class="panel-footer">
					<p><form method="get" action="/comments/image/{{ $item->id }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input type="hidden" name="post_id" value="{{ $item->id }}">
						<button class="btn btn-primary btn-xs" type="submit">Reply ({{ $item->num_comments }} comments)</button>
					</form></p>
				</div>
			</div>
			</div>
		@else
			<div class="col-sm-4">
			<div class="panel">
				<div class= "panel-heading ">
					<p><span class="glyphicon glyphicon-text-size"></span> {{ $item->name }} to {{ $item->reciever }} at {{ $item->time_created }}:</p>
					<h4>{{ $item->message }}</h4>
				</div>
				<div class="panel-body ">
					<div class="row">
						<div class="col-sm-12"><h3>{{ $item->compliment }}</h3></div>
						
					</div>			
				</div>
				<div class="panel-footer">
					<p><form method="get" action="/comments/compliment/{{ $item->id }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<input type="hidden" name="post_id" value="{{ $item->id }}">
							<button class="btn btn-primary btn-xs" type="submit">Reply ({{ $item->num_comments }} comments)</button>
					</form></p>
				</div>			
			</div>	
			</div>
		@endif
	@endforeach

	</div>


	

@stop