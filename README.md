# readurl
Read URL function using CURL that supports custom headers, cookies, browser agent, authentication.

You can POST/GET (and DELETE) any URL resource with this ReadURL function.

Sample usage:

```
<?php
    require_once('readurl.php');

    $useGzip = 1;

    $postData = 'q='.urlencode(trim('randomind'));
	$serverResponse = ReadURL(array(
		'url' => 'https://www.google.com/search',
		'isPost' => 0,
		'connectTimeOut' => 2,
		'timeOut' => 5,
		'postData' => $postData,
		'encoding' => $useGzip?'gzip':''
	));
	$serverResponse = $serverResponse['content'];

	print_r(substr($serverResponse, 0, 10000));
```
