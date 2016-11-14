<?php

Class Str {
	static function slug($string) {
		return strtolower(str_replace(['----','---','--'],'-', preg_replace('/[^a-zA-Z0-9_]/', '-', $string)));
	}
}

class View {

	public static function make($path, $vars = []) {
		
		if( ! is_dir('data/views')) {
			@mkdir('data/views');
		}

		$path = str_replace(['.blade.php','.php'], [], $path);

		if(file_exists($path . '.blade.php')) {
			$path = $path . '.blade.php';
		} elseif(file_exists($path . '.php')) {
			$path = $path . '.php';
		}

		$hash = Str::slug($path);
		$_path = __DIR__ . '/data/views/' . $hash . '.php';

		if(file_exists($_path)) {
			@unlink($_path);
		}

		$o = [
			'{{--',
			'--}}',
			'{{',
			'}}',
			'{!',
			'!}',
			'@foreach',
			'@for',
			'@if',
			'@else',
			'@elseif',
			'@endif',
			'@endforeach',
			'@include'
		];

		$d = [
			'<!--',
			'-->',
			'<?php echo ',
			'?>',
			'<?php ',
			'?>',
			'<?php foreach',
			'<?php for',
			'<?php if',
			'<?php else',
			'<?php elseif',
			'<?php endif; ?>',
			'<?php endforeach; ?>',
			'<?php include '
		];

		$view = file_get_contents(__DIR__ . '/' . $path);
		$xview = explode("\n", $view);

		$nview = '';
		foreach($xview as $eview) {

			$nview .= str_replace($o, $d, $eview);

			if(self::hasEnd($eview)) {
				$nview .= " :?>\n";
			} else {
				$nview .= "\n";
			}
		}

		file_put_contents($_path, $nview);
		echo Flight::render($_path, $vars);
	}

	private static function hasEnd($line) {

		$o = [
			'@foreach',
			'@for',
			'@if',
			'@else',
			'@elseif',
			'@include'
		];

		foreach($o as $eo) {
			if(stripos($line, $eo) !== false) {
				return true;
			}	
		} 
		return false;
		
	}
}
