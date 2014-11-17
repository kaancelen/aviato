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
	if($current_post->user_id === $user->data()->id){ # if post owner is the logged in user
		echo "<a href='update_post.php?id={$current_post->id}'> <span class='glyphicon glyphicon-edit'></span> </a>";
	}
	$rating_average = ($current_post->rating_number==0?0:($current_post->total_rating / $current_post->rating_number));
	?>
		<p><input id='star_rating' name='star_rating' class='rating' 
			data-size='xs' min='1' max='5' step='0.5' data-show-clear='false' data-show-caption='false'
			onchange='onChangeRating(<?php echo $current_post->id ?>, value)' value='<?php echo $rating_average ?>'
			data-readonly = '<?php echo ($current_post->user_id === $user->data()->id) ?>'>
		</p>
	</p>
</div>