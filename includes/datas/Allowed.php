<?php
class Allowed{
	public static function allowedTypesForProfilePictures(){
		return array('jpg', 'jpeg', 'gif', 'png');
	}

	public static function maxFileSizeForProfilePictures(){
		return 1000000;//1mb
	}

	public static function allowedTypesForShare(){
		return array('jpg', 'jpeg', 'gif', 'png', 'txt', 'pdf', 'mp3', 'mp4');
	}

	public static function maxFileSizeForShare(){
		return 1000000;//1mb
	}
	
	public static function MB(){
		return 1000000;//megabayt
	}
}
?>