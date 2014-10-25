<?php
include('includes/header.php');
#if this is not my post then i shouldn't see here
$post_id = $_GET['id'];#post id
$post = new Post();#post object
$user_id = $user->data()->id;#user id
if(!$post->find($post_id, $user_id)){#if DB dont have this record than redirect to 403.php
	Redirect::to('includes/errors/403.php');
}

echo "User = ".$user->data()->username;
echo "Post = ".$post->data()->id;
?>

<p>Update Post</p>

<?php
include('includes/footer.php');
?>