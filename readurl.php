<?php
	function ReadURL($in) {
		// create a new curl resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $in['url']);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if ($in['encoding'] != '')
			curl_setopt($ch, CURLOPT_ENCODING, $in['encoding']);
		if ($in['connectTimeOut'] != '')
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $in['connectTimeOut']);
		if ($in['timeOut'] != '')
			curl_setopt($ch, CURLOPT_TIMEOUT, $in['timeOut']);

		if ($in['isPost'] == 1) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $in['postData']);
		}
		elseif ($in['isDelete'] == 1) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}

		if ($in['headers'] != '')
			curl_setopt ($ch, CURLOPT_HTTPHEADER, $in['headers']);

		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

		if ($in['userAgent'] != '')
			curl_setopt($ch, CURLOPT_USERAGENT, $in['userAgent']);

		if ($in['cookie'] != '')
			curl_setopt($ch, CURLOPT_COOKIE, $in['cookie']);

		if ($in['username'] != '' && $in['password'] != '') {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
			curl_setopt($ch, CURLOPT_USERPWD, $in['username'] . ":" . $in['password']);
		}

		// grab URL and pass it to the browser
		$str = curl_exec($ch);

		if ($in['getinfo']) {
			$out['info'] = curl_getinfo($ch);
		}

		if (curl_errno($ch) != 0) {
			$out['error'] = curl_error($ch);
			curl_close($ch);
			return $out;
		}
		// close curl resource, and free up system resources
		curl_close($ch);

		// split header and content
		preg_match_all("|(HTTP\/1.*Content\-Type\: .*\r\n\r\n)+|Uis", $str, $matches);

		$out['content'] = $str;

		$out['headers'] = $matches[1];

		foreach ($out['headers'] as $header)
			$out['content'] = str_replace($header, '', $out['content']);

		$out['raw'] = $str;

		if (preg_match_all('/Set\-Cookie\: (.*)\r\n/Ui', $out['headers'][0], $matches)) {
			foreach ($matches[1] as $match) {
				$tmpArr = explode('; ', $match);
				if (count($tmpArr) > 0)
				foreach ($tmpArr as $cookie) {
					$tmpArr2 = explode('=', $cookie);
					$out['cookies'][$tmpArr2[0]] = $tmpArr2[1];
				}
			}

		}

		if (preg_match_all('/Set\-Cookie\: (.*)\r\n/Ui', $out['headers'][1], $matches)) {
			foreach ($matches[1] as $match) {
				$tmpArr = explode('; ', $match);
				if (count($tmpArr) > 0)
				foreach ($tmpArr as $cookie) {
					$tmpArr2 = explode('=', $cookie);
					$out['cookies'][$tmpArr2[0]] = $tmpArr2[1];
				}
			}
		}

		return $out;
	} // function ReadURL($in)
