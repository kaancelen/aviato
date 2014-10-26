<?php
class Post{
	private $_db,	#db instance
			$_data;	#user profile datas

	#constructs
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	#getter for data
	public function data(){
		return $this->_data;
	}
	#looking for if post with given id belongs to user with given user_id
	public function isBelong($id, $user_id){
		if($id && $user_id){
			$sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
			$query_result = $this->_db->query($sql, array($id, $user_id)); #replace ?
			if(!$query_result->error()){ #if query_result don't have error
				if($query_result->count()){#and query result have more than 0 row
					$this->_data = $query_result->first();#assign first one to _data
					return true;
				}
			}
		}
		return false;
	}
	#Check if given id post exist
	public function exist($id){
		if($id){
			$data = $this->_db->get('posts', array('id', '=', $id));
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	#return posts of user which given with user_id
	public function getPostsOfUser($user_id){
		if($user_id){
			$data = $this->_db->get('posts', array('user_id', '=', $user_id));
			return $data->all();
		}
		return false;
	}
	#create User object in Database
	public function create($fields = array()){
		if(!$this->_db->insert('posts', $fields)){
			throw new Exception("Post#create");
		}
	}
	#update post object in DB
	public function update($id, $fields){
		if(!$this->_db->update('posts', $id, $fields)){
			throw new Exception("Post#update");
		}
	}
	#upload file to server and return new file location
	public static function uploadFile($file_temp, $file_extn, $username){
		$file_path = 'aviato_media/'.$username.'/'.substr(md5(time()), 0, 10).'.'.$file_extn;
		move_uploaded_file($file_temp, $file_path);
		return $file_path;
	}
}

?>