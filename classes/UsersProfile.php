<?php
class UsersProfile{

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
	#create user profile object in DB for first time
	#it should use when first time user create
	public function init($user_id){
		$fields = array(
			'user_id' => $user_id,
			'profile_picture' => 'images/profile_pictures/default.jpg'
		);
		if(!$this->_db->insert('users_profile', $fields)){
			throw new Exception("UsersProfile#init");
		}
	}
	#assign usersProfile datas to $_data
	#if find row return true, else return false
	public function find($user_id = null){
		if($user_id){
			$data = $this->_db->get('users_profile', array('user_id', '=', $user_id));
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	#update db with given field
	public function update($id, $fields = null){
		if(!$this->_db->update('users_profile', $id, $fields)){
			throw new Exception("UsersProfile#update");
		}
	}
	#upload file to server and return new file location
	public static function uploadFile($file_temp, $file_extn){
		$user = new User();
		$file_path = 'images/profile_pictures/'.substr(md5($user->data()->username), 0, 10).'.'.$file_extn;
		move_uploaded_file($file_temp, $file_path);
		return $file_path;
	}
}

?>