<?php
include('includes/header.php');

if(Input::exists() && Token::check(Input::get('token'))){
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'username' => array('required' => true),
		'password' => array('required' => true)
	));
	#if validate it?
	if($validation->passed()){
		$remember = (Input::get('remember') === 'on') ? true : false;
		$login = $user->login(Input::get('username'), Input::get('password'), $remember);#login operation
		if($login){ #login success
			Session::flash('index', 'Successfully logged in');
			Redirect::to('index.php');
		}else{	#login failed
			echo '<div class="alert alert-warning" role="alert">Username or password incorrect</div>';
		}
	}else{
		foreach ($validation->errors() as $error) {
			echo '<div class="alert alert-warning" role="alert">'.$error.'</div>';
		}
	}
}
?>

<div class="container" style="width:40%">
	<form action="" method="post" class="form-signin" role="form">
		<h2 class="form-signin-heading">Log in</h2>
		<input type="text" class="form-control" name="username" id="username" placeholder="Username">
		<input type="password" class="form-control" name="password" id="password" placeholder="Password">
		<label for="remember" class="checkbox">
			<input type="checkbox" name="remember" id="remember"> Remember me
		</label>
		
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" class="btn btn-lg btn-primary btn-block" value="Log in">
	</form>
</div>

<?php
include('includes/footer.php');
?>