<?php
/**
 * Phx - A Micro RESTful PHP Framework For Beginners with one day learning curve
 *
 * @author   PHOENIX <gopher.huang@gmail.com>
 * @link    https://github.com/phoenixg/phx
 */

/*
 *---------------------------------------------------------------
 * DEFINE ALL CONSTANTS WE NEED
 *---------------------------------------------------------------
 */
// 定义文件夹的路径
define('DS',                DIRECTORY_SEPARATOR);
define('PATH_BASE',         __DIR__ . DS);
define('PATH_APP',          PATH_BASE . 'app' .       DS);
define('PATH_APP_C',        PATH_BASE . 'app' .       DS . 'mvc' .        DS . 'controllers' .    DS);
define('PATH_APP_M',        PATH_BASE . 'app' .       DS . 'mvc' .        DS . 'models' .          DS);
define('PATH_ASSETS',       PATH_BASE . 'assets' .  DS);
define('PATH_LOGS',         PATH_APP . 'logs' .       DS);
define('PATH_CORE',         PATH_BASE . 'core' .       DS);
define('PATH_CORE_LIBS',    PATH_BASE . 'core' .       DS . 'libs' .             DS);
define('PATH_CORE_HELPERS', PATH_BASE . 'core' .       DS . 'helpers' .    DS);
define('PATH_CORE_DEBUG',   PATH_BASE . 'core' .       DS . 'debugger' .      DS);

// 定义文件的路径
define('EXT',             '.php');
define('FILE_LOG', PATH_LOGS . 'mylog.log');
define('FILE_BASE',       PATH_BASE . 'index' .   EXT);

// 定义跨平台的行尾结束符
define('EOL', PHP_EOL);

/*
 *---------------------------------------------------------------
 * INCLUDE COMMON FUNCTIONS
 *---------------------------------------------------------------
 */
//require 'commons.php';

/*
 *---------------------------------------------------------------
 * INCLUDE ALL CORE LIB FILES
 *---------------------------------------------------------------
 */
$lib_files = glob(PATH_CORE_LIBS . '*' . EXT);
foreach ($lib_files as $file) {
    require $file;
    unset($file);
}
unset ($lib_files);

/*
 *---------------------------------------------------------------
 * RETRIEVE CONFIGURATION ARRAY FROM CONFIGURATION FILES
 *---------------------------------------------------------------
 */
$config_files = glob(PATH_APP . 'config' . DS . '*' . EXT);
$config = array();
foreach ($config_files as $config_file) {
    $key = substr(strrchr($config_file, DS), 1, -strlen(EXT));
    $config[$key] = include $config_file;
    unset($key);
    unset($config_file);
}
unset($config_files);

/*
 *---------------------------------------------------------------
 * INITIALIZE CONFIG CLASS
 *---------------------------------------------------------------
 * Retrieve config item via: $CFG::get('application.aaa.ddd.eee');
 * 使用了单例模式
 * 一旦实例化了配置类，我们就能做很多事了
 */
$CFG = Config::getInstance($config);
unset($config);

/*
 *---------------------------------------------------------------
 * SET DEFAULT TIMEZONE
 *---------------------------------------------------------------
 */
date_default_timezone_set($CFG::get('application.timezone'));


/*
 *---------------------------------------------------------------
 * SET ERROR REPORTING LEVEL
 *---------------------------------------------------------------
 */
if ($CFG::get('application.mode_debug')) {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    include PATH_CORE_DEBUG . 'dBug/dBug.php';
} else {
    ini_set('display_errors','Off');
    error_reporting(0);
}

/*
 *---------------------------------------------------------------
 * SET ERROR HANDLER
 *---------------------------------------------------------------
 *
 * 全部预定义的Error常量对应的数值（即$errorNo）见：http://php.net/manual/en/errorfunc.constants.php
 * error发生时自动触发，或手动触发：trigger_error("发生了一个错误")
 */
set_error_handler(function ($errorNo, $errMsg, $errFilePath, $errLine){
    $logInfo = '['.date('Y-m-d H:i:s').'] Error: '.$errMsg.', on line: '.$errLine.', in file: '.$errFilePath.EOL;
    echo $logInfo;
    error_log($logInfo, 3, FILE_LOG_ERRORS);
});

/*
 *---------------------------------------------------------------
 * SET DEFAULT EXCEPTION HANDLER
 *---------------------------------------------------------------
 *
 * 专门用于处理不能被 try...catch... 捕捉到的异常，比如，设置一个 exception_handler 把异常信息记录进log文件
 * try...catch... 里抛出的异常不会通过该函数处理，而是通过下面的自定义handler设置的
 * 使用 throw new Exception('异常信息') 手动触发该异常处理
 */
set_exception_handler(function ($e) {
    $logInfo = '['.date("Y-m-d H:i:s").'] Exception on line: '.$e->getLine().', in file: '.$e->getFile()
               .', with message: '.$e->getMessage().EOL;
    echo $logInfo;
    error_log($logInfo, 3, FILE_LOG_ERRORS);
});

/*
 *---------------------------------------------------------------
 * SET CUSTOM EXCEPTION HANDLER FOR TRY...CATCH...
 *---------------------------------------------------------------
 *
 *  try {
 *      throw new Phxexception("异常信息");
 *  } catch(Phxexception $e) {
 *      echo $e->getMsg();
 *  }
 */
class Phxexception extends Exception
{
    public function __construct($message) {
        parent::__construct($message);
    }

    public function getMsg()
    {
        $logInfo = '['.date("Y-m-d H:i:s").'] Custom Exception on line: '.$this->getLine().', in file: '.$this->getFile()
                   .', with message: '.$this->getMessage().EOL;
        error_log($logInfo, 3, FILE_LOG_ERRORS);
        return $logInfo;
    }
}


/*
 *---------------------------------------------------------------
 * INCLUDE IoC CONTAINER
 *---------------------------------------------------------------
 * Append your own IoC class in ioc.php file
 */
require 'ioc'.EXT;

/*
 *---------------------------------------------------------------
 * RESOLVE ALL CLASSES
 *---------------------------------------------------------------
 * $test = IoC::resolve('classname');
 */
//$test = IoC::resolve('classname');


/*
 *---------------------------------------------------------------
 * SET CONTROLLER AND MODEL CLASSES TO BE AUTOLOAD
 *---------------------------------------------------------------
 */
function __autoload($classname)
{
    //var_dump($classname);  eg. Default_Controller
    $fileController = PATH_APP_C . strtolower($classname) . EXT;

    if (is_file($fileController)) {
        include $fileController;
    } else {
        $fileModel = PATH_APP_M . strtolower($classname) . EXT;

        if (is_file($fileModel)) {
            include $fileModel;
        } else {
            throw new Exception('无法自动加载该类：'.$classname);
        }
    }
}

/*
 *---------------------------------------------------------------
 * ROUTE URI TO CONTROLLER/METHOD
 *---------------------------------------------------------------
 * eg. index.php?c=default&a=index [decrypted]
 * eg. index.php/default/hello/param1/value1/param2/value2
 */
require 'frontcontroller.php';
$frontController = FrontController::getInstance();
$frontController->route();




require './core/debugger/kint/Kint.class.php';
//Kint::enabled(false);

Kint::dump( 1 );
d( 2 );



/*
 *---------------------------------------------------------------
 * SHOW DEBUG INFORMATION
 *---------------------------------------------------------------
 */
//new dBug($GLOBALS);

//new dBug(get_defined_vars());

//$constants = get_defined_constants(true);
//new dBug($constants['user']);

