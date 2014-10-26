<?php
include('includes/header.php');
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}

#if input exists and token is ok
if(Input::exists() && Token::check(Input::get('token'))){
	#First validate other fields validate and upload file last
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'name' => array(
			'required' => true,
			'min' => 2,
			'max' => 127
		),
		'desc' => array(
			'required' => true,
			'min' => 2,
			'max' => 255
		),
		'tags' => array(
			'required' => true,
			'min' => 20,
			'max' => 1023
		)
	));
	#vaidation flag
	$isValidFlag = true;
	#Get file features and validate it, if not isValidFlag will be false
	if(isset($_FILES['media_url']) === true && empty($_FILES['media_url']['name']) !== true){
		$allowed = Allowed::allowedTypesForShare(); #allowed types
		$max_file_size = Allowed::maxFileSizeForShare();
		$file_name = $_FILES['media_url']['name'];#get filename
		$file_temp = $_FILES['media_url']['tmp_name'];#get file temp path
		$file_type = mime_content_type($file_temp);#get file type
		$file_size = $_FILES['media_url']['size'];#get file size
		$temp = explode('.', $_FILES['media_url']['name']);
		$file_extn = strtolower(end($temp));#get file extension

		if($file_size > $max_file_size){#if file size too big
			$isValidFlag = false;
			echo '<div class="alert alert-warning" role="alert">File is too big. Max: '.$max_file_size/Allowed::MB().'MB</div>';
		}
		if(in_array($file_extn, $allowed) === false){#if file extension not allowed
			$isValidFlag = false;
			echo '<div class="alert alert-warning" role="alert">Incorrect file type. Allowed: '.implode(', ', $allowed).'</div>';
		}
	}else{	#if file not selected
		$isValidFlag = false;
		echo '<div class="alert alert-warning" role="alert">Please choose a file</div>';
	}
	#validate other inputs
	if(!$validate->passed()){#if other inputs couldn't passed validation
		$isValidFlag = false;
		foreach ($validation->errors() as $error) {
			echo '<div class="alert alert-warning" role="alert">'.$error.'</div>';
		}
	}
	#if can pass validation then upload file and create Post object
	if($isValidFlag){
		//upload file
		$media_url = Post::uploadFile($file_temp, $file_extn, $user->data()->username);
		#Create Post object
		$post = new Post();
		//create row in DB
		$post->create(array(
			'user_id' => $user->data()->id,
			'name' => Input::get('name'),
			'desc' => Input::get('desc'),
			'media_url' => $media_url,
			'mime_type' => $file_type,
			'tags' => TagUtils::fixTagsForSystem(Input::get('tags'))
		));
		//Redirect to index
		Session::flash('index', '"'.Input::get('name').'" post created!');
		Redirect::to('index.php');
	}
}
?>

<div class="container" style="width:40%">
	<form action="" method="post" class="form-signin" role="form" enctype="multipart/form-data">
		<input type="file" name="media_url" class="btn btn-default">

		<h3>Name</h3>
		<input type="text" class="form-control" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>" autocomplete="off">

		<h3>Description</h3>
		<input type="textArea" class="form-control" name="desc" id="desc" value="<?php echo escape(Input::get('desc')); ?>" autocomplete="off">

		<h3>Tags</h3>
		<input type="textArea" class="form-control" name="tags" id="tags" value="<?php echo escape(TagUtils::fixTagsForSystem(Input::get('tags'))); ?>" autocomplete="off">

		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" class="btn btn-lg btn-primary btn-block" value="Share it!">
	</form>
</div>

<?php
include('includes/footer.php');
?>