<?php
//设置应该报告何种错误, 开发环境建议设置为E_ALL
//参考: http://php.net/error_reporting
error_reporting(E_ALL);

//显示错误信息开关
//参考: http://php.net/display-errors
ini_set("display_errors", false);

//注册错误以及异常处理(要尽可能早的注册,因为在注册之前的错误是无法捕获的)
//可以捕获致命错误(Fatal Error), 一旦出现致命错误, 程序将终止运行
//参考: http://php.net/register_shutdown_function
register_shutdown_function(function() {
    $error = error_get_last();

    if (!empty($error)) {
		output_errorlog($error);
    }
});

//可以捕获E_WARNING,E_NOTICE,E_USER_ERROR,E_USER_WARNING,E_USER_NOTICE, E_RECOVERABLE_ERROR
//但无法捕获下列错误类型E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING
//参考: http://php.net/set_error_handler
set_error_handler(function($code, $message, $file = null, $line = null) {
	//参考: http://php.net/error_reporting
	if (error_reporting() & $code) {
		$error = array();
		$error['type'] = $code;
		$error['message'] = $message;
		$error['file'] = $file;
		$error['line'] = $line;
		output_errorlog($error);

        return true;
	}

	return true;
});

//一旦异常被捕获, 程序将停止执行
//参考: http://php.net/set_exception_handler
set_exception_handler(function($e) {
	if ($e)	{
		$error = array();
		$error['type'] = $e->getCode();
		$error['message'] = $e->getMessage();
		$error['file'] = $e->getFile();
		$error['line'] = $e->getLine();
		output_errorlog($error);
        return true;
	}
});

echo '<pre>';

//$a = array(1,2,3);
print_r($a);
//exit('I have exited!');

//参考: http://php.net/trigger_error
if ($divisor == 0) {
    trigger_error("Cannot divide by zero", E_USER_ERROR);
}

//try ... catch
try {
    $file = fopen("filenoexists.txt", "r");
    //$aa = new Nothing();
	//参考: http://php.net/manual/zh/class.exception.php
    throw new Exception('A Exception Demo');
} catch (Exception $e) {
    throw $e;
}

//错误输出处理
function output_errorlog($error) {
	//参考: http://php.net/manual/zh/errorfunc.constants.php
    $errortype = array (
		0                    => 'Exception',
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
        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error',
		E_DEPRECATED  		 => 'Runtime Info',
		E_USER_DEPRECATED	 => 'User Trigger Warning',
		E_ALL  		 		 => 'Any Error Exclude E_STRICT',
    );

	if (isset($error['type']) && in_array($error['type'], array(E_WARNING,E_NOTICE,E_USER_ERROR,E_USER_WARNING,E_USER_NOTICE,E_USER_DEPRECATED))) {
		$message = '<p style="color:gray;">['. $errortype[$error['type']].'] '.$error['message'].', '.$error['file'].' (<b>line:'.$error['line'].'</b>)</p>';
	} else {
		$message = '<p style="color:red;">['. $errortype[$error['type']].'] '.$error['message'].', '.$error['file'].' (<b>line:'.$error['line'].'</b>)</p>';
	}
	
	echo $message;
}
