<?php
include('includes/header.php');

if(Session::exists('index')){
	echo '<div class="alert alert-success" role="alert">'.Session::flash('index').'</div>';
}
?>
<div align="center" class="jumbotron">
	<h1>Aviato!</h1>
	<?php
		if(!$user->isLoggedIn()){
			echo '<p>Welcome the share world of aviato<br>Please Register or Log in</p>';
			echo '<p><a href="register.php" class="btn btn-primary btn-lg" role="button">Register</a> <a href="login.php" class="btn btn-primary btn-lg" role="button">Log in</a> </p>';
		}else{
			echo "<h2>Hello {$user->data()->username} </h2>";
			echo '<p>Share something now!</p>';
			echo '<p><a href="#" class="btn btn-primary btn-lg" role="button">Share</a></p>';
		}
	?>
</div>

<?php
include('includes/footer.php');
?>