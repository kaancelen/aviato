<?php
include('includes/header.php');

#if POST or GET exists AND Token exist
if(Input::exists() && Token::check(Input::get('token'))){
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'username' => array(
			'required' => true,
			'min' => 2,
			'max' => 20,
			'unique' => 'users'
		),
		'password' => array(
			'required' => true,
			'min' => 6,
		),
		'password_again' => array(
			'required' => true,
			'matches' => 'password'
		),
		'email' => array(
			'required' => true,
			'min' => 2,
			'max' => 64,
			'email_validate' => true
		)
	));
	#if inputs validate?
	if($validation->passed()){
		$user = new User();
		try{
			$salt = Hash::salt(32);
			$user->create(array( #create user
				'username' => Input::get('username'),
				'password' => Hash::make(Input::get('password'), $salt),
				'salt' => $salt,
				'email' => Input::get('email'),
				'joined' => date('Y-m-d H:i:s'),
				'group' => 1
			));
			$user->find(Input::get('username'));#find user from db
			$userProfile = new UsersProfile();
			$userProfile->init($user->data()->id);#create users_profile row for user

			Session::flash('index', "Hello ".Input::get('username').", You have been registered and can now log in!");
			Redirect::to('index.php');
		} catch(Exception $e){
			die($e->getMessage());
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
		<h2 class="form-signin-heading">Register</h2>
		<input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
		<input type="password" class="form-control" name="password" id="password" placeholder="Password">
		<input type="password" class="form-control" name="password_again" id="password_again" placeholder="Password Again">
		<input type="text" class="form-control" name="email" id="email" placeholder="E-Mail" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off">
		
		
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" class="btn btn-lg btn-primary btn-block" value="Register">
	</form>
</div>
<?php
include('includes/footer.php');
?>