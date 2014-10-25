<?php
#Cookie operations
class Cookie{
	#if given named cookie exists
	public static function exists($name){
		return (isset($_COOKIE[$name])) ? true : false;
	}
	#get given named cookie
	public static function get($name){
		return $_COOKIE[$name];
	}
	#add cookie
	public static function put($name, $value, $expiry){
		if(setcookie($name, $value, time() + $expiry, '/')){
			return true;
		}
		return false;
	}
	#delete cookie
	public static function delete($name){
		self::put($name, '', time() - 1);
	}
}
?>