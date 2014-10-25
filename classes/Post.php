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
	#find post in DB according to is or (id and user_id)
	public function find($id, $user_id = null){
		if($id){
			if(!$user_id){#find with just id field
				$data = $this->_db->get('posts', array('id', '=', $id));
				if($data->count()){
					$this->_data = $data->first();
					return true;
				}
			}else{
				$sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
				$query_result = $this->_db->query($sql, array($id, $user_id)); #replace ?
				if(!$query_result->error()){ #if query_result don't have error
					if($query_result->count()){#and query result have more than 0 row
						$this->_data = $query_result->first();#assign first one to _data
						return true;
					}
				}
			}
		}
		return false;
	}
	#create User object in Database
	public function create($fields = array()){
		if(!$this->_db->insert('posts', $fields)){
			throw new Exception("Post#create");
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