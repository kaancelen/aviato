<?php
#Use $current_post as a variable, before include it you must create $current_post
echo '<div class="thumbnail custom_thumbnail">';#thumbnail aç
	echo "<embed src='{$current_post->media_url}' autostart='0' type='{$current_post->mime_type}' class='custom_thumbnail_media'>";#post media
	echo "<h3>{$current_post->name}</h3>";#post name
	#echo "<p>{$current_post->detail}</p>";#post detail
	echo '<p>';#open tag list
	foreach (TagUtils::tagArray($current_post->tags) as $tag) {
		echo " <span class='label label-default'>{$tag}</span> ";#post tags
	}
	echo '</p>';#close tag list
	echo "<p>";#open operations list
	echo "<a href='download.php?file={$current_post->media_url}' target='_blank'> <span class='glyphicon glyphicon-cloud-download'></span> </a>";#download icon
	echo "<a href='post_page.php?id={$current_post->id}'> <span class='glyphicon glyphicon-fullscreen'></span> </a>";#maximize
	$temp_post_owner = new User($current_post->user_id);#get post owner
	if($temp_post_owner->data()->id === $user->data()->id){ # if post owner is the logged in user
		echo "<a href='update_post.php?id={$current_post->id}'> <span class='glyphicon glyphicon-edit'></span> </a>";
	}
	echo "</p>";#close operations list
echo '</div>';#thumbnail kapa
?>