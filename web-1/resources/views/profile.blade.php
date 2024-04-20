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
		<form method="POST" action="{{ route('update_profile', Auth::user()->id) }}" autocomplete="off">
            @csrf
			<input autocomplete="false" type="hidden" />
			<fieldset class="form-group form-success">
				<label for="username">Username</label>
				<input id="username" name="username" type="text" placeholder="" class="form-control" value="{{ Auth::user()->username }}">
			</fieldset>
			<fieldset class="form-group form-success">
				<label for="password">Password</label>
				<input id="password" name="password" type="password" placeholder="" class="form-control" >
			</fieldset>
			<br>
			<div>
				<button class="btn btn-primary btn-block btn-ghost" name="register" value="Register">Update</button>
			</div>
		</form>
	</div>
	<div class="footer">
		Valar Morghulis, ....
	</div>
</body>
</html>