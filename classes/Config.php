<?php
class Config{
	#get config string parse it and return value of it
	public static function get($path){
		if($path){#if path is not null
			$config = $GLOBALS['config'];
			$path = explode('/', $path);

			#kind of recursive but implement imperative
			foreach ($path as $bit) {
				if(isset($config[$bit])){
					$config = $config[$bit];
				}
			}

			return $config;
		}

		return false;
	}
}

?>