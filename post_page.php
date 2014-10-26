<?php
include('includes/header.php');
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
				<h3><?php echo $post->data()->name ?> 
					<?php 
						if($user->data()->id === $post_owner->data()->id){#if this user is post owner then show edit button
							echo "<a href='update_post.php?id={$post->data()->id}'><span class='glyphicon glyphicon-edit'></span></a>";
						}
					?>
				</h3>
				<p><?php echo $post->data()->desc ?></p>
				<?php #tags
					echo '<p>';#open tag list
					foreach (TagUtils::tagArray($post->data()->tags) as $tag) {
						echo " <span class='label label-default'>{$tag}</span> "; #post tags
					}
					echo '</p>';#close tag list
				?>
				<p>Belongs to <a href="profile.php?user=<?php echo $post_owner->data()->id ?>"><span class="label label-info"><?php echo $post_owner->data()->username ?></span></a></p>
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