<?php
include('includes/header.php');

if(!$user->isLoggedIn()){
	Session::flash('index', "Please log in!");
	Redirect::to('index.php');
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' => true,
				'min' => 6
			),
			'password_new' => array(
				'required' => true,
				'min' => 6
			),
			'password_new_again' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'password_new'
			)
		));

		if($validation->passed()){
			if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password){
				echo 'Your current password is wrong';
			}else{
				$salt = Hash::salt(32);
				$user->update(array(
					'password' => Hash::make(Input::get('password_new'), $salt),
					'salt' => $salt
				));

				Session::flash('index', 'Your password has been changed!');
				Redirect::to('index.php');
			}
		}else{
			foreach ($validation->errors() as $error) {
				echo '<div class="alert alert-warning" role="alert">'.$error.'</div>';
			}
		}
	}
}
?>

<div class="container" style="width:40%">
	<form action="" method="post" class="form-signin" role="form">
		<h2 class="form-signin-heading">Change Password</h2>
		<input type="password" class="form-control" name="password_current" id="password_current" placeholder="Current Password">
		<input type="password" class="form-control" name="password_new" id="password_new" placeholder="New Password">
		<input type="password" class="form-control" name="password_new_again" id="password_new_again" placeholder="New Password Again">
		
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" class="btn btn-lg btn-primary btn-block" value="Change">
	</form>
</div>

<?php
include('includes/footer.php');
?>