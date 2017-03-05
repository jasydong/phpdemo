<?php
/**
 * errorHandler PHP错误异常处理类
 *
 * @package  phpdemo
 * @author   JasyDong
 */
class errorHandler {
    //注册错误以及异常处理回调方法
    public static function register() {
        //注册错误以及异常处理
        register_shutdown_function(function() {
            $error = error_get_last();

            if (!empty($error)) {
                self::errorlog($error);
            }
        });

        //错误处理
        set_error_handler(function($code, $message, $file = null, $line = null) {
            if (error_reporting() & $code) {
                $error = array();
                $error['type'] = $code;
                $error['message'] = $message;
                $error['file'] = $file;
                $error['line'] = $line;
                self::errorlog($error);

                return true;
            }

            return true;
        });

        //异常处理
        set_exception_handler(function($e) {
            if ($e)	{
                $error = array();
                $error['type'] = $e->getCode();
                $error['message'] = $e->getMessage();
                $error['file'] = $e->getFile();
                $error['line'] = $e->getLine();
                self::errorlog($error);
                return true;
            }
        });
    }

    //错误输出处理
    public static function errorlog($error) {
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
}

//设置应该报告何种错误, 开发环境建议设置为E_ALL
//参考: http://php.net/error_reporting
error_reporting(E_ALL);

//显示错误信息开关
//参考: http://php.net/display-errors
ini_set("display_errors", false);

//注册错误以及异常处理
errorHandler::register();

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

