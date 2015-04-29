<!DOCTYPE html>
<html>
<head>
	<title>Complimentr</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<style type="text/css">
		body{
			background: rgba(233, 93, 34, .1);
		}	
		.navbar{
			background-color: #e95d22;	
			background: rgba(233, 93, 34, .75);
			border: none;	
		}
		.navbar-nav li a{
			color: white;
			border: none;
		}
		.navbar-header a{
			color: white;
			border: none;
		}
		.navbar-nav li a:hover{
			background-color:rgba(233, 93, 34, .76);
			
		}
		.navbar-nav li .btn-log{			
			margin-top: 10px ;
			margin-right: 10px;
			margin-bottom: 10px;
			margin-left: 10px;
			padding-top: 5px;
			padding-bottom: 5px;
			opacity: 0.75;
		}
		.panel{
			border: none;
		}
		.panel-heading{
			background-color:  white;			
			color: rgba(233, 93, 34, .75);
			border: 1px solid rgba(233, 93, 34, .1);
		}
		.panel-footer{
			background-color: white;
			color: black;
			border: none;
		}
		.img-responsive{
			width: 100%;
		}
		
	</style>
</head>
<body>

	<header class="navbar navbar-static-top bs-docs-nav" >				
		<div class="container-fluid navbar-container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar glyphicon glyphicon-th-list" style="color:white"></span>
					
				</button>
				<a class="navbar-brand" href="/home">Complimentr</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-left">
					<li><a class="active" href="/home">Global Feed<span class="sr-only">(current)</span></a></li>
					@if(Auth::check())
					<li><a href="/{{ $name }}/sent">My Sent Feed</a></li>
					<li><a href="/{{ $name }}/recieved">My Recieved Feed</a></li>
					@endif
				</ul>
				<ul class="nav navbar-nav navbar-right">	
					
					@if(Auth::check())																
						<li><a class="btn btn-primary btn-log" href="/logout/facebook">Logout</a></li>
					@else
						<li><a class="btn btn-primary btn-log"data-toggle="modal" data-target="#myModal" >Login</a></li>
					@endif
					
				</ul>
			</div>
		</div>			
	</header>


	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">	
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

					<div class="container-fluid">
					<div class="row">
						<div class="col-sm-3">
							<h3>Login with Facebook</h3>
							<br>	
							<a class="btn btn-primary valign" href="/login/facebook">login with facebook</a>								
						</div>
						<div class="col-sm-4">
							<form method="post" action="/login">
								<h3>Login</h3>
								<input type="hidden" name="_token" value="{{ csrf_token() }}" />
								<div class="form-group">
									<label>Email:</label><input class="form-control" type="email" name="email">
								</div>
								<div class="form-group">
									<label>Password:</label><input class="form-control" type="password" name="password">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-default">Login</button>
								</div>
							</form>						
						</div>
						<div class="col-sm-4">
							<form method="post" action="/create">
								<h3>Create Account</h3>
								<input type="hidden" name="_token" value="{{ csrf_token() }}" />
								<div class="form-group">
									<label>Name:</label><input class="form-control" type="text" name="name">
								</div>
								<div class="form-group">
									<label>Email:</label><input class="form-control" type="email" name="email">
								</div>
								<div class="form-group">
									<label>Password:</label><input class="form-control" type="password" name="password">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-default">Create Account</button>
								</div>
							</form>
						</div>
					</div>
					</div>
				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			    </div>
			</div>
		</div>
	</div>

@yield('content')

@yield('footer')

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

@yield('scripts')

</body>
</html>