<?php
session_start();
#required global varriables
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'aviato'
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800 # in second
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);

#auto require_once when we create new class object
spl_autoload_register(function($class){
	require_once 'classes/' . $class . '.php';
}); 

require_once 'functions/sanitize.php';
require_once 'functions/TagUtils.php';
require_once 'includes/datas/Country.php';
require_once 'includes/datas/Allowed.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

	if($hashCheck->count()){ #if DB has recorded hash then login that user
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}

?>