<?php
	/**
	 * Tenta Obter o IP real do cliente
	 * @return string
	 */
	function get_ip_real() {
		static $ip_real = null;
		if ($ip_real !== null) {
			return $ip_real;
		}
		if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
			$ip = trim($_SERVER['HTTP_CLIENT_IP']);
			if (validar_ip($ip)) {
				$ip_real = $ip;
				return $ip;
			}
		}
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
			$ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
			if (validar_ip($ip)) {
				$ip_real = $ip;
				return $ip;
			} elseif (strpos($ip, ',') !== false) {
				$ips = explode(',', $ip);
				foreach ($ips as $ip) {
					$ip = trim($ip);
					if (validar_ip($ip)) {
						$ip_real = $ip;
						return $ip;
					}
				}
			} elseif (strpos($ip, ';') !== false) {
				$ips = explode(';', $ip);
				foreach ($ips as $ip) {
					$ip = trim($ip);
					if (validar_ip($ip)) {
						$ip_real = $ip;
						return $ip;
					}
				}
			}
		}
		if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
			$ip = trim($_SERVER['REMOTE_ADDR']);
			if (validar_ip($ip)) {
				$ip_real = $ip;
				return $ip;
			}
		}

		$ip_real = '0.0.0.0';
		return $ip_real;
	}

	/**
	 * Valida um IP v4
	 * @param string $ip: IP a ser validado
	 * @return bool
	 */
	function validar_ip($ip) {

		// IPv4
		$vetor = explode('.', $ip);
		if (count($vetor) != 4) {
			return false;
		}
		foreach ($vetor as $i) {
			if (!is_numeric($i) || $i < 0 || $i > 255) {
				return false;
			}
		}
		return true;
	}


	function textSanitizer($text){
		$text = strip_tags($text, '<p><a><br><b><i>');
		$text = addslashes($text);
		return $text;
	}


	function createThumbnail($pathToImage, $thumbWidth = 100) {
		$result = 'Failed';
		if (is_file($pathToImage)) {
			$info = pathinfo($pathToImage);

			$extension = strtolower($info['extension']);
			if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) {

				switch ($extension) {
					case 'jpg':
						$img = imagecreatefromjpeg("{$pathToImage}");
						break;
					case 'jpeg':
						$img = imagecreatefromjpeg("{$pathToImage}");
						break;
					case 'png':
						$img = imagecreatefrompng("{$pathToImage}");
						break;
					case 'gif':
						$img = imagecreatefromgif("{$pathToImage}");
						break;
					default:
						$img = imagecreatefromjpeg("{$pathToImage}");
				}
				// load image and get image size

				$width = imagesx($img);
				$height = imagesy($img);

				// calculate thumbnail size
				$new_width = $thumbWidth;
				$new_height = floor($height * ( $thumbWidth / $width ));

				// create a new temporary image
				$tmp_img = imagecreatetruecolor($new_width, $new_height);

				// copy and resize old image into new image
				imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

				//$filename = end(explode('/', $pathToImage));
				$pathToImage = $pathToImage . '.thumb.' . $extension;


				// save thumbnail into a file
				imagejpeg($tmp_img, "{$pathToImage}");
				$result = $pathToImage;
				$result = end(explode('/', $pathToImage));
			} else {
				$result = 'Failed|Not an accepted image type (JPG, PNG, GIF).';
			}
		} else {
			$result = 'Failed|Image file does not exist.';
		}
		return $result;
	}

	function parseDate($date,$inputFormat){
		$day = 0;
		$month = 0;
		$year = 0;

		switch($inputFormat){
			case 'd/m/Y': //Brazil date format (dd/mm/yyyy)
				if (preg_match('/^(\d+)\/(\d+)\/(\d+)$/', $date, $m)){
					$day = $m[1];
					$month = $m[2];
					$year = $m[3];
				}
				break;

			case 'm/d/Y': //EUA date format (mm/dd/yyyy)
				if (preg_match('/^(\d+)\/(\d+)\/(\d+)$/', $date, $m)){
					$day = $m[2];
					$month = $m[1];
					$year = $m[3];
				}
				break;

			case 'Y-m-d': //ISO date format (yyyy-mm-dd)
				if (preg_match('/^(\d+)\-(\d+)\-(\d+)$/', $date, $m)){
					$day = $m[3];
					$month = $m[2];
					$year = $m[1];
				}
				break;

			default:
				break;

		}

		if (!checkdate($month, $day, $year))return false;

		return "$year-$month-$day";
	}


	/**
	* Função para gerar senhas aleatórias
	*
	* @author&nbsp;&nbsp;&nbsp; Thiago Belem <contato@thiagobelem.net>
	*
	* @param integer $tamanho Tamanho da senha a ser gerada
	* @param boolean $maiusculas Se terá letras maiúsculas
	* @param boolean $numeros Se terá números
	* @param boolean $simbolos Se terá símbolos
	*
	* @return string A senha gerada
	*/
	function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';

		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;

		$len = strlen($caracteres);
		for ($n = 1; $n <= $tamanho; $n++) {
			$rand = mt_rand(1, $len);
			$retorno .= $caracteres[$rand-1];
		}
		return $retorno;
	}

?>