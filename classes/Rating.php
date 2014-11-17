<?php
class Rating{
	private $_db,	#db instance
			$_data;	#user profile datas

	#constructs
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	/**
	* Check if this post rating exist, if it is return true
	* and set $_data, else return false
	*/
	public function exists($user_id, $post_id){
		if($user_id && $post_id){
			$sql = "SELECT * FROM post_ratings WHERE user_id = ? AND post_id = ?";
			$query_result = $this->_db->query($sql, array($user_id, $post_id)); #replace ?
			if(!$query_result->error()){ #if query_result don't have error
				if($query_result->count()){#and query result have more than 0 row
					$this->_data = $query_result->first();#assign first one to _data
					return true;
				}
			}
		}
		return false;
	}
	/**
	* add rating data
	*/
	public function addRating($user_id, $post_id, $value){
		$post = new Post();
		$post->exist($post_id);//get post data
		//update post
		$post_fields = array(
			"total_rating" => $post->data()->total_rating += $value, //add value to total rating
			"rating_number" => $post->data()->rating_number += 1 // increase rating number 1
		);
		if(!$this->_db->update("posts", $post_id, $post_fields)){
			throw new Exception("Post_ratings#addRating#update");
		}
		//insert rating
		$post_rating_fields = array(
			"user_id" => $user_id,
			"post_id" => $post_id,
			"rate" => $value
		);
		if(!$this->_db->insert("post_ratings", $post_rating_fields)){
			throw new Exception("Post_ratings#addRating#insert");
		}
	}
	/**
	* update rating data
	*/
	public function updateRating($user_id, $post_id, $value){
		$post = new Post();
		$post->exist($post_id);//get post data
		$this->exists($user_id, $post_id);//this post_rating
		//update post
		$post_fields = array(
			"total_rating" => $post->data()->total_rating += ($value - $this->_data->rate) //calculate new total rating
		);
		if(!$this->_db->update("posts", $post_id, $post_fields)){
			throw new Exception("Post_ratings#updateRating#update");
		}
		//update rating
		$post_rating_fields = array(
			"rate" => $value
		);
		if(!$this->_db->update("post_ratings", $this->_data->id, $post_rating_fields)){
			throw new Exception("Post_ratings#addRating#insert");
		}
	}
}
?>