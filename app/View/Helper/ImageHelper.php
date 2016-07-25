<?php
/*
OpenEMIS School
Open School Management Information System

This program is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 3 of the License, or any later version. This program is distributed in the hope 
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should 
have received a copy of the GNU General Public License along with this program.  If not, see 
<http://www.gnu.org/licenses/>.  For more information please email contact@openemis.org.
*/

App::uses('AppHelper', 'View/Helper');

class ImageHelper extends AppHelper {
	public $helpers = array('Html');
	
	public function get($data) {
		$src = 'data: image/%s;base64,%s';
		$ext = 'jpeg';
		return sprintf($src, $ext, base64_encode($data));
	}
	
	public function getBase64($name, $content) {
		$data = null;
		if(!empty($name) && !empty($content)) {
			$temp = explode('.', $name);
			$ext = strtolower(array_pop($temp));
			if($ext === 'jpg') {
				$ext = 'jpeg';
			}
			$data = sprintf('data: image/%s; base64,%s', $ext, base64_encode($content));
		}
		return $data;
	}
	
	public function getBase64Image($name, $content, $options=array()) {
		$src = $this->getBase64($name, $content);
		if(!empty($src)) {
			$options['src'] = $src;
			return $this->Html->tag('img', '', $options);
		}
		return null;
	}
}
