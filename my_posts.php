<?php
include('includes/header.php');
$post = new Post();
$posts = $post->getPostsOfUser($user->data()->id);
?>

<div align="center">
<?php
	if(empty($posts)){
		echo '<h2>You do not have any post <a href="add_post.php"><span class="label label-primary">Share it</span></a> now!</h2>';
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