<?php
class TagUtils{
	/**
	*	Get tags string and remove all space characters, convert spacial characters
	*	and make lower case all and return it
	*/
	public static function fixTagsForSystem($tags){
		return str_replace(" ", "", strtolower($tags));
	}
	/**
	* return tag array of given tag string
	*/
	public static function tagArray($tags){
		return explode(',', $tags);
	}
}
?>