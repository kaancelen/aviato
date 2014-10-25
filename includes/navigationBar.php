<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="index.php">Aviato</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<?php 
			if($user->isLoggedIn()){//if logged in
		?>
		<ul class="nav navbar-nav">
			<li><a href="#">New Added</a></li>
			<li><a href="#">Most Rated</a></li>
			<?php
				if($user->hasPermission('admin')){
					echo "<li><a href='#'>Users</a></li>";
				}
			?>
		</ul>
		<form class="navbar-form navbar-left" role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
		<?php
			} //end if isLogged in
		?>
		<ul class="nav navbar-nav navbar-right">
			<?php
				if(!$user->isLoggedIn()){ #if not logged in
					echo "<li><a href='register.php'>Register</a></li>";
					echo "<li><a href='login.php'>Log in</a></li>";
				}else{ #if logged in
					echo "<li class='dropdown'>";
					echo	"<a href='#'' class='dropdown-toggle' data-toggle='dropdown'>{$user->data()->username} <span class='caret'></span></a>";
					echo	"<ul class='dropdown-menu' role='menu'>";
					echo		"<li><a href='profile.php?user={$user->data()->username}'>Profile</a></li>";
					echo		"<li><a href='update.php'>Settings</a></li>";
					echo		"<li><a href='changepassword.php'>Change Password</a></li>";
					echo		"<li class='divider'></li>";
					echo		"<li><a href='logout.php'>Logout</a></li>";
					echo	"</ul>";
					echo "</li>";
				}
			?>
			
		</ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>