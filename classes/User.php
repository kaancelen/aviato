<?php
class User{
	private $_db,	#db object
			$_data,	#User object data
			$_sessionName,#Session id
			$_cookieName,#cookie id
			$_isLoggedIn;#logged in flag

	#constructer
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		#if the session exists
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);

				if($this->find($user)){
					$this->_isLoggedIn = true;
				}else{
					//process log out
				}
			}
		} else {
			$this->find($user);
		}
	}
	#create User object in Database
	public function create($fields = array()){
		if(!$this->_db->insert('users', $fields)){
			throw new Exception("User#create");
		}
		#create media directory
		$mediaDir = 'aviato_media/'.$fields['username'];
		if(!file_exists($mediaDir)){
			mkdir($mediaDir, 0777, true);//create directory with 0777 permissions
		}
	}
	#update user object in DB
	public function update($fields = array(), $id = null){
		#if admin update then give user id, if current user then leave it null
		if(!$id && $this->isLoggedIn()){
			$id = $this->data()->id;
		}

		if(!$this->_db->update('users', $id, $fields)){
			throw new Exception("User#update");
		}
	}
	#find user in DB according to username or id
	public function find($user = null){
		if($user){
			$fields = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->get('users', array($fields, '=', $user));
			
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	#login operation
	public function login($username = null, $password = null, $remember = false){
		if(!$username && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->id);
		}else{
			$user = $this->find($username);
			if($user){
				if($this->data()->password === Hash::make($password, $this->data()->salt)){
					Session::put($this->_sessionName, $this->data()->id);
					if($remember){ # if remember me checked
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
						if(!$hashCheck->count()){ #if session not found in DB insert new session
							$this->_db->insert('users_session', array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						}else{	#if session found in DB set hash to local var
							$hash = $hashCheck->first()->hash;
						}
						#add cookie
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				}
			}
		}
		return false;
	}
	#if user exists
	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}
	#if user is admin?
	public function hasPermission($key){
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group));
		if($group->count()){
			$permissions = json_decode($group->first()->permissions, true);
			if($permissions[$key] == true){
				return true;
			}
		}
		return false;
	}
	#logout feature
	public function logout(){
		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}
	#return last find operations data
	public function data(){
		return $this->_data;
	}
	#is logged in
	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}
}

?>