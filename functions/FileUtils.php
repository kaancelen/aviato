<?php
class FileUtils{
	#remove file in given file_path
	#if cannot remove return false;
	public static function removeFile($file_path){
		if(!is_dir($file_path))# if not directory it is file remove it
			return unlink($file_path);
		else 	#if directory return false
			return false;
	}
}
?>