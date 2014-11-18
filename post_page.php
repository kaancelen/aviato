<?php
include('includes/header.php');
if(!$user->isLoggedIn()){
	Session::flash('index', "Please log in!");
	Redirect::to('index.php');
}
#if this is not my post then i shouldn't see here
$post_id = $_GET['id'];#post id
$post = new Post();#post object
if(!$post->exist($post_id)){#if DB dont have this record than redirect to 404.php
	Redirect::to('includes/errors/404.php');
}
$post_owner = new User($post->data()->user_id);
?>
<div class="jumbotron" align="center">
	<table>
		<tr>
			<td align="center">
				<h3><?php echo $post->data()->name ?></h3>
				<p><?php echo $post->data()->detail ?></p>
				<p><?php echo $post->data()->updated_date ?></p>
				<?php #tags
					echo '<p>';#open tag list
					foreach (TagUtils::tagArray($post->data()->tags) as $tag) {
						echo " <span class='label label-default'>{$tag}</span> "; #post tags
					}
					echo '</p>';#close tag list
				?>
				<p>Belongs to <a href="profile.php?user=<?php echo $post_owner->data()->id ?>"><span class="label label-info"><?php echo $post_owner->data()->username ?></span></a></p>
				<?php 
						if($user->data()->id === $post_owner->data()->id){#if this user is post owner then show edit button
							echo "<p><a href='update_post.php?id={$post->data()->id}'> <span class='glyphicon glyphicon-edit'></span> Edit</a></p>";
						}
				?>
				<p><a href="download.php?file=<?php echo $post->data()->media_url ?>" target="_blank"> <span class='glyphicon glyphicon-cloud-download'></span> Download</a></p>
				<?php 
					$rating_average = ($post->data()->rating_number==0?0:($post->data()->total_rating / $post->data()->rating_number)); 
					$rating_record = new Rating();
					if($rating_record->exists($user->data()->id, $post->data()->id)){//if logged in user rate this post
						echo "<span class='badge' id='user_rate'>{$rating_record->data()->rate}</span>";
					}
				?>
				<p><input id="star_rating" name="star_rating" class="rating" 
						data-size="xs" min="1" max="5" step="0.5" data-show-clear="false" data-show-caption="false"
						onchange="onChangeRating(<?php echo $post->data()->id ?>, value)" value="<?php echo $rating_average ?>"
						data-readonly="<?php echo ($user->data()->id === $post_owner->data()->id) ?>"></p>
			</td>
			<td>
				<embed src=<?php echo $post->data()->media_url ?> autostart='0' type=<?php echo $post->data()->mime_type ?> class="custom_embed_document">
			</td>
		</tr>
	</table>
</div>

<?php
include('includes/footer.php');
?>