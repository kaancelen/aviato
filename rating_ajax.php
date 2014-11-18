<?php
	require_once 'core/init.php';
	$user = new User();//get user object
	$post_id = Input::get('post_id');//get datas
	$value = Input::get('rate');
	$rating = new Rating();
	if($rating->exists($user->data()->id, $post_id)){
		//update this rating
		$rating->updateRating($user->data()->id, $post_id, $value);//update rating to DB
	}else{
		//add new rating
		$rating->addRating($user->data()->id, $post_id, $value);//add rating to DB
	}
?>