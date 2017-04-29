<?php

Class Cache {

	private static $path = 'storage/cache/';

	private static function path($key) {
		$file = explode('.',$key);
		$file = end($file);
		$path = self::$path . str_replace(['.',$file],['/',''], $key);
		@mkdir($path, 0777, true);
		return $path . $file;
	}

	public static function set($key, $value, $expire = 0) {
		$path = self::path($key);
		$data = ['expire' => time() + $expire,'value' => $value];
		file_put_contents($path, serialize($data));
	}

	public static function get($key, $default = null) {
		$path = self::path($key);
		if(is_file($path) && file_exists($path)) {
			$data = unserialize(file_get_contents($path));
			if($data['expire'] == 0 || $data['expire'] >= time()) {
				return $data['value'];
			}
		}
		return $default;
	}

	public static function remove($key) {
		$path = self::path($key);
		if(is_file($path) && file_exists($path)) {
			@unlink($path);
		}
	}

	public static function has($key) {
		$path = self::path($key);
		if(is_file($path) && file_exists($path)) {
			$data = unserialize(file_get_contents($path));
			if($data['expire'] == 0 || $data['expire'] >= time()) {
				return true;
			}
		}
		return false;
	}

	public static function search($path, $string = null, $fullpath = true) {
		$path = self::path($path);
		if(is_dir($path)) {
			$files = array_diff(scandir($path),['.','..']);
			$match = [];

			if($string === null) {
				$match = $files;
			} else {
				foreach($files as $file) {
					if(stripos($file, $string) !== false) {
						if($fullpath == true) {
							$match[] = $path  . '/' . $file;	
						}else{
							$match[] = $file;
						}						
					}
				}
			}
			return $match;
		}
		return [];
	}
}
