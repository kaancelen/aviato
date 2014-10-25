<?php
include('includes/header.php');

if(!$username = Input::get('user')){
	Redirect::to('index.php');
}else{
	$user = new User($username);#get user from db
	$userProfile = new UsersProfile();
	$userProfile->find($user->data()->id);#get user profile info from db
	if(!$user->exists()){
		Redirect::to(404);//if user not exists
	}else{
		$data = $user->data();
	}
}
?>
<div align="center">
	<img class="media-object" src='<?php echo $userProfile->data()->profile_picture ?>' alt='<?php echo $data->username ?>' width="25%">
</div>

<div class="jumbotron" align="center">
	<h1><?php echo $data->username ?></h1>
	<p>E-Mail :<?php echo $data->email ?></p>
	<p>Name : <?php echo $userProfile->data()->name ?></p>
	<p>Gender :<?php echo $userProfile->data()->gender ?></p>
	<p>Birthday :<?php echo $userProfile->data()->birthday ?></p>
	<p>Country :<?php echo $userProfile->data()->country ?></p>
	<p>Desc :<?php echo $userProfile->data()->motto ?></p>
</div>

<?php
include('includes/footer.php');
?>