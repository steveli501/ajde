<?php

$code = $_SERVER['REDIRECT_STATUS'];

function desc() {
	switch ($_SERVER['REDIRECT_STATUS']) {
		case 400: return "Bad Request";
		case 401: return "Unauthorized";
		case 403: return "Forbidden";
		case 404: return "Not Found";
		case 500: return "Internal Server Error";
		case 501: return "Not Implemented";
		case 502: return "Bad Gateway";
		case 503: return "Service Unavailable";
		case 504: return "Bad Timeout";
	}
}

?>
<!DOCTYPE html> 
<html> 
<head> 
	<title>Server error</title>
</head> 
<body> 
	<h1>ERROR <?php echo $code; ?> - <?php echo desc(); ?></h1>
	<h3>Unfortunately, something went wront.</h3>
	<hr/>
	<p><a href="http://code.google.com/p/ajde">Ajde open framework</a>
</body> 
</html> 