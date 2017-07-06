
<!DOCTYPE html>
<html lang="ru">
<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
<head>
	<meta charset="utf-8">
	<title>Главная</title>
</head>
<body>
	<div id="content">
		<?php include 'application/views/'.$content_view; ?>
	</div>
	<div id="authorization" class="forms" style="display: none;">
		<h5>Login</h5>
		<div class="errors" id="authorizationErrors"></div>
		<form name="authorizationForm" id="authorizationForm" method="post" action="">
			<div class="form-control">
				<input type="text" name="username" placeholder="Username" required>
			</div>
			<div class="form-control">
				<input type="password" name="password" placeholder="Password" required>
			</div>
			<div class="form-control">
				<button type="submit">Apply</button>
			</div>
			<input type="hidden" name="login" value="true">

			<p>
				Do you still not have an account? Click <a href="/?registration">here</a>
			</p>
		</form>
	</div>

	<div id="registration" class="forms" style="display: none;">
		<h5>Registration</h5>
		<div class="errors" id="registrationErrors"></div>
		<form name="registrationForm" id="registrationForm" method="post" action="">
			<div class="form-control">
				<input type="text" name="username" placeholder="Username" required>
			</div>
			<div class="form-control">
				<input type="password" name="password" placeholder="Password" required>
			</div>
			<div class="form-control">
				<input type="text" name="email" placeholder="Email" required>
			</div>
			<div class="form-control">
				<input type="text" name="invite" placeholder="Invite code">
			</div>
			<div class="form-control">
				<button type="submit">Apply</button>
			</div>
			<input type="hidden" name="registration" value="true">
		</form>
	</div>

	<div id="addEvent" class="forms" style="display: none;">
		<div class="close"><a href="#" class="closeLink">x</a></div>
		<h5>Add event</h5>
		<form name="addEventForm" id="addEventForm" action="" method="post">
			<div class="form-control">
				<div class="form-control">
					<input type="text" name="eventName" placeholder="Event name" required>
				</div>
				<div class="form-control">
					<textarea name="eventDescription" placeholder="description"></textarea>
				</div>
				<input type="hidden" type="startDate" name="startDate" id="startDate">
				<div class="form-control">
					<button type="submit">Apply</button>
				</div>
			</div>
		</form>
	</div>
	<script src="/js/script.js"></script>
</body>
</html>