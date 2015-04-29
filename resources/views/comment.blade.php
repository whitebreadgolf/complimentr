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
	@if($type == 'image')
		<div class="col-sm-12">
			<div class="panel">
				<div class= "panel-heading">
					<p><span class="glyphicon glyphicon-picture"></span> {{ $item->name }} to {{ $item->reciever }} at {{ $item->time_created }}:</p>
					<h4>{{ $item->message }}</h4>
				</div>
				<div class="panel-body">
					<div class="col-sm-12">
						<img class="img-responsive img-rounded" src="{{ $item->image }}">
					</div>								
				</div>
				<div class="panel-footer">
	@else
		<div class="col-sm-12">
			<div class="panel">
				<div class= "panel-heading">
					<p><span class="glyphicon glyphicon-text-size"></span> {{ $item->name }} to {{ $item->reciever }} at {{ $item->time_created }}:</p>
					<h4>{{ $item->message }}</h4>
				</div>
				<div class="panel-body">
					<div class="col-sm-12">
						<h3>{{ $item->compliment }}</h3>
					</div>					
				</div>
				<div class="panel-footer">		
	@endif
			<h4>Comments:</h4>
				<div id="comments">
				@foreach($comments as $comment)
					<div class="row-fluid">
						<p><i>By {{ $comment->name}} at {{ $comment->time_created }}: </i><b>{{$comment->comment}}</b></p>
					</div>
					<br>
				@endforeach
				</div>
					
				<script type="text/handlebars" id="comment-template">
					<div class="row-fluid">
						<p><i>By @{{ name }} at @{{ time_created }}: </i><b>@{{ comment }}</b></p>
					</div>
					<br>
				</script>

				@if($type == 'image')
				<form method="post" action="/comments/image">
				@else
				<form method="post" action="/comments/compliment">
				@endif
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<input type="hidden" name="post_id" value="{{ $item->id }}">
					<div class="form-group">
						<input class="form-control" type="text" name="comment" placeholder="Comment here">
					</div>
					<div class="form-group">
						<button class="btn btn-primary" type="submit">send</button>
					</div>
				</form>
				
			</div>			
		</div>	
	</div>
	</div>
@stop

@section('scripts')
<script src="//js.pusher.com/2.2/pusher.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.2/handlebars.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.1/toastr.min.js"></script>

<script>
	//get template
	var commentTemplate = Handlebars.compile($('#comment-template').html());

	//open websocket
	var pusher = new Pusher('d9a59bcc0a5bcc2951c0');
	var channel = pusher.subscribe('comment_channel');	

	channel.bind('newcomment', function(data){

		var comment = JSON.parse(data.comment);
		var html = commentTemplate(comment);

		
		$('#comments').append(html);
		//toastr.success(song.title + ' was added!');
	});
</script>
@stop