function onChangeRating(post_id, value){
	var post_data = "post_id="+post_id+"&value="+value;
	$.ajax({
		type : "POST",
		url : "rating_ajax.php",
		data : post_data
	});
}