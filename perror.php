<?php

error_reporting(E_ALL);

ini_set("display_errors", false);


//注册异常处理
register_shutdown_function(function() {
    $error = error_get_last();

    if (!empty($error)) {
        echo '<p style="color:red;">shutdown:: '.$error['message'].', '.$error['file'].':'.$error['line'].'</p>';
    }
});

set_error_handler(function($code, $error, $file = null, $line = null) {
	if (error_reporting() & $code) {
		debug_print_backtrace();
        return true;
	}

	return true;
});

set_exception_handler(function($e) {
	if ($e)	{
        echo 'exception::';
		print_r($e);
        return true;
	}
});

echo '<pre>';

//print_r($a);

trigger_error("Cannot divide by zero", E_USER_ERROR);

//throw new Exception('DOH!!');



