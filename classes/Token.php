<?php
class Token{
	#generate random md5 hash and put it to session token
	public static function generate(){
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}
	#check if token exist
	public static function check($token){
		$tokenName = Config::get('session/token_name');

		if(Session::exists($tokenName) && $token === Session::get($tokenName)){
			Session::delete($tokenName);
			return true;
		}

		return false;
	}
}

?>