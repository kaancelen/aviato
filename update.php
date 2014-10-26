<?php
include('includes/header.php');

if(!$user->isLoggedIn()){
	Session::flash('index', "Please log in!");
	Redirect::to('index.php');
}
$userProfile = new usersProfile();
$userProfile->find($user->data()->id);#get this user profile datas

$allowed = Allowed::allowedTypesForProfilePictures(); #allowed types
$max_file_size = Allowed::maxFileSizeForProfilePictures();	//allowed max file size

#if input exists and token is ok
if(Input::exists() && Token::check(Input::get('token'))){
	#handle profile picture first quickly
	#if profile picture has and file is not empty then upload and set it
	if(isset($_FILES['profile_picture']) === true && empty($_FILES['profile_picture']['name']) !== true){ 
		$file_name = $_FILES['profile_picture']['name'];#get filename
		$file_temp = $_FILES['profile_picture']['tmp_name'];#get file temp path
		$file_size = $_FILES['profile_picture']['size'];#get file size
		$temp = explode('.', $_FILES['profile_picture']['name']);
		$file_extn = strtolower(end($temp));#get file extension

		$isFileOk = true;
		if($file_size > $max_file_size){
			$isFileOk = false;
			echo '<div class="alert alert-warning" role="alert">File is too big. Max: '.$max_file_size/Allowed::MB().'MB</div>';
		}
		if(in_array($file_extn, $allowed) === false){
			$isFileOk = false;
			echo '<div class="alert alert-warning" role="alert">Incorrect file type. Allowed: '.implode(', ', $allowed).'</div>';
		}
		if($isFileOk){
			$uploaded_file_path = UsersProfile::uploadFile($file_temp, $file_extn);#upload file
			$userProfile->update($userProfile->data()->id, array('profile_picture' => $uploaded_file_path));#update DB
			echo '<div class="alert alert-success" role="alert">Profile picture changed</div>';
		}
	}
	#now handle other variables
	$validateFields = array();
	$updateFields = array();
	if(Input::get('email')){
		$validateFields['email'] = array('required' => true, 'min' => 2, 'max' => 64, 'email_validate' => true);
	}
	if(Input::get('name')){
		$validateFields['name'] = array('max' => 128);#add validation
		$updateFields['name'] = Input::get('name');
	}
	if(Input::get('motto')){
		$validateFields['motto'] = array('max' => 255);#add validation
		$updateFields['motto'] = Input::get('motto');
	}
	if(Input::get('country')){
		$updateFields['country'] = Input::get('country');
	}
	if(Input::get('gender')){
		$updateFields['gender'] = Input::get('gender');
	}
	if(Input::get('year') && Input::get('month') && Input::get('day')){
		$date = new DateTime(Input::get('year').'-'.Input::get('month').'-'.Input::get('day'));
		$updateFields['birthday'] = $date->format('Y-m-d');
	}
	#check if values are valid
	$validate = new Validate();
	$validation = $validate->check($_POST, $validateFields);
	if($validate->passed()){
		try{
			if(Input::get('email')){#if email filled
				$user->update(array('email' => Input::get('email')));
			}
			if(count($updateFields) > 0){#if other fields filled
				$userProfile->update($userProfile->data()->id, $updateFields);
			}
			echo '<div class="alert alert-success" role="alert">Profile informations changed</div>';
		}catch(Exception $e){
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
	<form action="" method="post" class="form-signin" role="form" enctype="multipart/form-data">
		<img src='<?php echo $userProfile->data()->profile_picture ?>' alt='<?php echo $user->data()->username ?>' width="50%" height="%50">
		<input type="file" name="profile_picture" class="btn btn-default">
		<p>Allowed types (<?php echo implode(", ", $allowed)?>)<br>Max file size (<?php echo $max_file_size/Allowed::MB().'MB' ?>)</p>

		<h3 class="form-signin-heading">Change E-Mail</h3>
		<input type="text" class="form-control" name="email" id="email" placeholder="<?php echo escape($user->data()->email); ?>" value="<?php echo escape(Input::get('email')); ?>">
		
		<h3 class="form-signin-heading">Change Name</h3>
		<input type="text" class="form-control" name="name" id="name" placeholder="<?php echo escape($userProfile->data()->name); ?>" value="<?php echo escape(Input::get('name')); ?>">

		<h3 class="form-signin-heading">Change Motto</h3>
		<textarea rows="3" cols="50" class="form-control" name="motto" id="motto" placeholder="<?php echo escape($userProfile->data()->motto); ?>" value="<?php echo escape(Input::get('motto')); ?>"></textarea>

		<h3 class="form-signin-heading">Change Country</h3>
		<select class="form-control" id="country" name="country" style="width:50%">
			<option value="">Country</option>
			<?php
				$countries = Country::getCountries();
				foreach ($countries as $country) {
					echo '<option value="'.$country.'">'.$country.'</option>';
				}
			?>
		</select>

		<h3 class="form-signin-heading">Change Gender</h3>
		<select class="form-control" id="gender" name="gender" style="width:50%">
			<option value="">Select gender</option>
			<option value="MALE">MALE</option>
			<option value="FEMALE">FEMALE</option>
		</select>

		<h3 class="form-signin-heading">Change Birthday</h3>
		<select class="form-control" id="year" name="year" style="width:50%">
			<option value="">Year</option>
			<?php
				for($i = 2014; $i > 1904; $i--){
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
			?>
		</select>
		<select class="form-control" id="month" name="month" style="width:50%">
			<option value="">Month</option>
			<?php
				for($i = 1; $i < 13; $i++){
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
			?>
		</select>
		<select class="form-control" id="day" name="day" style="width:50%">
			<option value="">Day</option>
			<?php
				for($i = 1; $i < 32; $i++){
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
			?>
		</select>

		<br>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" class="btn btn-lg btn-primary btn-block" value="Change">
	</form>
</div>

<?php
include('includes/footer.php');
?>