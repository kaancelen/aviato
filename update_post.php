<?php
include('includes/header.php');
if(!$user->isLoggedIn()){
	Session::flash('index', "Please log in!");
	Redirect::to('index.php');
}
#if this is not my post then i shouldn't see here
$post_id = $_GET['id'];#post id
$post = new Post();#post object
$user_id = $user->data()->id;#user id
if(!$post->exist($post_id)){#if DB dont have this record than redirect to 404.php
	Redirect::to('includes/errors/404.php');
}
if(!$post->isBelong($post_id, $user_id)){#if DB have record but not belongs given user_id redirect to 403.php
	Redirect::to('includes/errors/403.php');
}

$allowed = Allowed::allowedTypesForShare(); #allowed types
$max_file_size = Allowed::maxFileSizeForShare();
#if input exists and token correct
if(Input::exists() && Token::check(Input::get('token'))){
	#handle new media quickly
	#if media uploaded then upload it and update DB
	if(isset($_FILES['media_url']) === true && empty($_FILES['media_url']['name']) !== true){
		$file_name = $_FILES['media_url']['name'];#get filename
		$file_temp = $_FILES['media_url']['tmp_name'];#get file temp path
		$file_type = mime_content_type($file_temp);#get file type
		$file_size = $_FILES['media_url']['size'];#get file size
		$temp = explode('.', $_FILES['media_url']['name']);
		$file_extn = strtolower(end($temp));#get file extension

		$isFileOk = true;
		if($file_size > $max_file_size){#if file size too big
			$isFileOk = false;
			echo '<div class="alert alert-warning" role="alert">File is too big. Max: '.$max_file_size/Allowed::MB().'MB</div>';
		}
		if(in_array($file_extn, $allowed) === false){#if file extension not allowed
			$isFileOk = false;
			echo '<div class="alert alert-warning" role="alert">Incorrect file type. Allowed: '.implode(', ', $allowed).'</div>';
		}
		if($isFileOk){#upload file, remove old file and update DB
			$media_url = Post::uploadFile($file_temp, $file_extn, $user->data()->username);#upload file
			FileUtils::removeFile($post->data()->media_url);#remove old file
			$post->update($post->data()->id, array('media_url' => $media_url, 'mime_type' => $file_type, 'updated_date' => date('Y-m-d H:i:s')));#update the table
		}
	}
	#if inputs given then validate and update DB
	#now handle other variables
	$validateFields = array();
	$updateFields = array();
	if(Input::get('name')){
		$validateFields['name'] = array('min' => 2, 'max' => 128);#add validation
		$updateFields['name'] = Input::get('name');
	}
	if(Input::get('detail')){
		$validateFields['detail'] = array('min' => 2, 'max' => 255);#add validation
		$updateFields['detail'] = Input::get('detail');
	}
	if(Input::get('tags')){
		$validateFields['tags'] = array('min' => 20, 'max' => 1023);#add validation
		$updateFields['tags'] = TagUtils::fixTagsForSystem(Input::get('tags'));
	}
	#check if values are valid
	$validate = new Validate();
	$validation = $validate->check($_POST, $validateFields);
	if($validate->passed()){
		if(count($updateFields) > 0){	#if other fields filled
			$updateFields['updated_date'] = date('Y-m-d H:i:s');
			$post->update($post->data()->id, $updateFields);
		}
		echo '<div class="alert alert-success" role="alert">Post informations changed</div>';
	}else{
		foreach ($validation->errors() as $error) {
			echo '<div class="alert alert-warning" role="alert">'.$error.'</div>';
		}
	}
}

?>

<div class="container" style="width:40%">
	<form action="" method="post" class="form-signin" role="form" enctype="multipart/form-data">
		<embed src="<?php echo $post->data()->media_url ?>" autostart='0' type="<?php echo $post->data()->mime_type ?>" class='custom_update_media'>
		<input type="file" name="media_url" class="btn btn-default" >
		<p>Allowed types (<?php echo implode(", ", $allowed)?>)<br>Max file size (<?php echo $max_file_size/Allowed::MB().'MB' ?>)</p>

		<h3>Name</h3>
		<input type="text" class="form-control" name="name" id="name" placeholder="<?php echo escape($post->data()->name); ?>" autocomplete="off">

		<h3>Description</h3>
		<input type="textArea" class="form-control" name="detail" id="detail" placeholder="<?php echo escape($post->data()->detail); ?>" autocomplete="off">

		<h3>Tags</h3>
		<input type="textArea" class="form-control" name="tags" id="tags" placeholder="<?php echo escape($post->data()->tags); ?>" autocomplete="off">

		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" class="btn btn-lg btn-primary btn-block" value="Update">
	</form>
</div>

<?php
include('includes/footer.php');
?>