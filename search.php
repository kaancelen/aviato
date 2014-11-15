<?php
include('includes/header.php');
if(!$user->isLoggedIn()){
	Session::flash('index', "Please log in!");
	Redirect::to('index.php');
}
//fix keywords and convert it to array
$tags = TagUtils::tagArray(TagUtils::fixTagsForSystem(Input::get('tags')));
$post = new Post();
$posts = array();
//Foreach keyword find post in DB and add to list
foreach ($tags as $tag) {
	$foundedPosts = $post->findPostsByTag($tag);
	//add founded post to array
	foreach ($foundedPosts as $foundedPost) {
		//if not added already
		if(!in_array($foundedPost, $posts)){
			$posts[] = $foundedPost;
		}
	}
}
?>

<div align="center">
<?php
	if(empty($posts)){
		echo '<h2>We cannot find posts, can you <a href="add_post.php"><span class="label label-primary">Share it</span></a> now?</h2>';
	}else{
		$row_number = (count($posts) / 3) + 1;
		for($i = 1; $i <= $row_number; $i++){
			echo '<div class="row">';#row aç
			for($j = 1; $j <= 3; $j++){
				$post_number = ( ($i-1)*3 + $j ) - 1;
				if($post_number >= count($posts))
					break;
				$current_post = $posts[$post_number];#get next element
				echo '<div class="col-sm-6 col-md-4">';#sütun aç
					include 'includes/thumbnail.php';#take $current_post as a parameter
				echo '</div>';#sütun kapa
			}
			echo '</div>';#row kapa
		}
	}
?>

<?php
include('includes/footer.php');
?>