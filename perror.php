<?php
//设置应该报告何种错误, 开发环境建议设置为E_ALL
error_reporting(E_ALL);

ini_set("display_errors", false);

//注册错误以及异常处理(要尽可能早的注册,因为在注册之前的错误是无法捕获的)
//可以捕获致命错误(Fatal Error), 一旦出现致命错误, 程序将终止运行
register_shutdown_function(function() {
    $error = error_get_last();

    if (!empty($error)) {
        echo '<p style="color:red;">Fatal Error:: '.$error['message'].', '.$error['file'].':'.$error['line'].'</p>';
    }
});

//可以捕获E_WARNING,E_NOTICE,E_USER_ERROR,E_USER_WARNING,E_USER_NOTICE, E_RECOVERABLE_ERROR
//但无法捕获下列错误类型E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING
set_error_handler(function($code, $error, $file = null, $line = null) {
    $errortype = array (
        E_ERROR              => 'Error',
        E_WARNING            => 'Warning',
        E_PARSE              => 'Parsing Error',
        E_NOTICE             => 'Notice',
        E_CORE_ERROR         => 'Core Error',
        E_CORE_WARNING       => 'Core Warning',
        E_COMPILE_ERROR      => 'Compile Error',
        E_COMPILE_WARNING    => 'Compile Warning',
        E_USER_ERROR         => 'User Error',
        E_USER_WARNING       => 'User Warning',
        E_USER_NOTICE        => 'User Notice',
        E_STRICT             => 'Runtime Notice',
        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
    );

	if (error_reporting() & $code) {
        $type = $errortype[$code];
        echo "<p style=\"color:gray;\">{$type}:: {$error}</p>";
        //throw new Exception($error, $code);
        return true;
	}

	return true;
});

//一旦异常被捕获, 程序将停止执行
set_exception_handler(function($e) {
	if ($e)	{
		print_r($e);
        return true;
	}
});

echo '<pre>';

//$a = array(1,2,3);
print_r($a);

if ($divisor == 0) {
    trigger_error("Cannot divide by zero", E_USER_ERROR);
}

try {
    $file = fopen("filenoexists.txt", "r");
    $aa = new Nothing();
    //throw new Exception('DOH!!');
} catch (Exception $e) {
    throw $e;
}

