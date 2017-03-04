<?php 

class Form {

	private static function setAttribute($attributes) {
		$string = '';
		foreach($attributes as $key => $attribute) {
			$string .= $key.'="' . $attribute . '" ';				
		}
		return $string;
	}	

	static function text($name, &$value, $attribute = []) {

		$att = [
			'id' 	=> $name,
			'name' 	=> $name,
			'value' => $value,
			'class' => 'form-control'
		];
		$att = array_merge($att, $attribute);

		return '<input ' . self::setAttribute($att) . '/>';
	}

	static function select($name, $options, &$selected = null, $attribute = []) {
		$att = [
			'id' 	=> $name,
			'name' 	=> $name,
			'class' => 'form-control'
		];
		$att = array_merge($att, $attribute);

		$opts = '';
		foreach($options as $value => $display) {
			if($value == $selected) {
				$opts .= '<option value="'.$value.'" selected="selected">'.$display.'</option>';
			}else{
				$opts .= '<option value="'.$value.'">'.$display.'</option>';	
			}
		}

		return '<select '.self::setAttribute($att).'>'.$opts.'</select>';
	}

	static function textarea($name, &$value, $attribute = []) {
		$att = [
			'id' 	=> $name,
			'name' 	=> $name,
			'class' => 'form-control'
		];
		$att = array_merge($att, $attribute);

		return '<textarea ' . self::setAttribute($att) . '>'.$value.'</textarea>';
	}
}

class BS {

	private static function setAttribute($attributes) {
		$string = '';
		foreach($attributes as $key => $attribute) {
			$string .= $key.'="' . $attribute . '" ';				
		}
		return $string;
	}

	static function table(&$data, $header = [], $attribute = []){
		$att = array_merge($attribute, ['class' => 'table table-condensed table-hover table-bordered']);
		$att = self::setAttribute($att);

		if(empty($header)) {
			$head = '';
		} else{
			$head = '<thead><tr>';
			foreach($header as $h) {
				$head .= '<th>'.$h.'</th>';
			}
			$head .= '</tr></thead>';
		}

		if(empty($data)) {
			$content = '';
		} else {
			$content = '<tbody>';
			foreach($data as $arr) {
				$content .= '<tr>';
				if(is_array($arr)) {
					foreach($arr as $a) {
						$content .= '<td>'.$a.'</td>';
					}	
				}else{
					$content .= '<td>'.$arr.'</td>';
				}
				
				$content .= '</tr>';
			}
			$content .= '</tbody>';
		}

		return str_replace(['@attribute','@head','@content'], [$att, $head, $content], '<table @attribute>@head@content</table>');
	}

	static function nav($name = null, $menus = []) {
		$html = '<nav class="navbar navbar-inverse"><div class="navbar-header"><a class="navbar-brand" href="#">'.$name.'</a></div><div class="collapse navbar-collapse">@menu</div></nav>';

		$menu = '<div class="collapse navbar-collapse"><ul class="nav navbar-nav">';
		foreach($menus as $name => $url) {
			$menu .= '<li><a href="'.$url.'">'.$name.'</a></li>';
		}
		$menu .= '</ul></div>';
		return str_replace('@menu', $menu, $html);
	}
}
