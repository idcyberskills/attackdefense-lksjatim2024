<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('header')
</head>

@include('alerts.error')
@include('alerts.success')
@include('nav')

<body class="hack dark">
	<div class="grid main-form">
		<form action="" method="POST" action="{{ route('login') }}">
            @csrf
			<fieldset class="form-group form-success">
				<label for="username">Username</label>
				<input id="username" name="username" type="text" placeholder="" class="form-control">
			</fieldset>
			<fieldset class="form-group form-success">
				<label for="password">Password</label>
				<input id="password" name="password" type="password" placeholder="" class="form-control">
			</fieldset>
			<br>
			<div>
				<button class="btn btn-primary btn-block btn-ghost" name="login" value="Login">Login</button>
				<div class="help-block">Only noble users are allowed to bypass access here</div>
			</div>
		</form>
	</div>
	<div class="footer">
		Valar Morghulis, ....
	</div>
</body>
</html>