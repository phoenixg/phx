<?php
/**
 * Phx - A Micro RESTful PHP Framework For Beginners with one day learning curve
 *
 * @author   PHOENIX <gopher.huang@gmail.com>
 * @link    https://github.com/phoenixg/phx
 *
 * ///,                //// /
 *  \    /,            /    >.
 *    \    /,      _/    /.
 *      \_    /_/      /.     It's more like an eagle than phoenix,
 *        \__/_      <      if you find a better ascii picture,
 *        /<<<  \_\_      it's welcome to  make a pull request
 *      /,)^>>_._  \
 *      (/      \\  /\\\
 *                // ````       now let's fly!
 */

/*
 *---------------------------------------------------------------
 * DEFINE ALL CONSTANTS WE NEED
 *---------------------------------------------------------------
 * 常量定义的命名约定：
 * 文件夹以PATH_* 开头，文件以FILE_* 开头，依此类推
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
define('PATH_CORE_COMMONS', PATH_BASE . 'core' .       DS . 'commons' .      DS);
define('PATH_CORE_PLUGINS', PATH_BASE . 'core' .       DS . 'plugins' .      DS);


// 定义文件的路径
define('EXT',       '.php');
define('FILE_BASE', PATH_BASE . 'index' .   EXT);
define('FILE_LOG',  PATH_LOGS .   date('Y-m-d') . '.log');

// 定义跨平台的行尾结束符
define('EOL', PHP_EOL);

/*
 *---------------------------------------------------------------
 * INCLUDE COMMON FUNCTIONS AND LET'S DO SOME SAFETY JOBS FIRST
 *---------------------------------------------------------------
 */
require PATH_CORE_COMMONS . 'commons.php';

// turn off register_globals
unregister_GLOBALS();

// turn off magic quotes
@set_magic_quotes_runtime(0);

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
if ($CFG::get('application.error_reporting') === true) {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors','Off');
    error_reporting(0);
}

/*
 *---------------------------------------------------------------
 * SET DEBUGGER
 *---------------------------------------------------------------
 */
if ($CFG::get('application.debug') === true) {
    $debug_tool = & $CFG::get('application.debug_tool');
    switch ($debug_tool) {
        case 'dbug':
            require PATH_CORE_DEBUG . 'dBug' . DS .'dBug' . EXT;
            break;
        case 'kint':
            require PATH_CORE_DEBUG . 'kint' . DS .'Kint.class' . EXT;
            break;
        default:
            break;
    }
}


// 异常和错误都使用了 error_log() 来写错误
// 只有用户自己的log才使用日志类来写

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
    error_log($logInfo, 3, FILE_LOG);
});

/*
 *---------------------------------------------------------------
 * SET EXCEPTION HANDLER FOR EXCEPTION WITHOUT TRY...CATCH...
 *---------------------------------------------------------------
 *
 * 设置一个异常处理器把异常写进log里
 * 专门用于处理 try...catch... 之外的异常
 * 可使用 throw new Exception('异常信息') 手动触发
 */
set_exception_handler(function ($e) {
    $logInfo = '['.date("Y-m-d H:i:s").'] Exception on line: '.$e->getLine().', in file: '.$e->getFile()
               .', with message: '.$e->getMessage().EOL;
    echo $logInfo;
    error_log($logInfo, 3, FILE_LOG);
});

/*
 *---------------------------------------------------------------
 * SET EXCEPTION HANDLER FOR EXCEPTION WITHIN TRY...CATCH...
 *---------------------------------------------------------------
 *
 * 设置一个用于标识的异常处理器：Phxexception
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
    // 这里不打印，是因为会在catch里进行打印
    public function getMsg()
    {
        $logInfo = '['.date("Y-m-d H:i:s").'] Phxexception on line: '.$this->getLine().', in file: '.$this->getFile()
                   .', with message: '.$this->getMessage().EOL;
        error_log($logInfo, 3, FILE_LOG);
        return $logInfo;
    }
}

/*
 *---------------------------------------------------------------
 * INCLUDE IoC CONTAINER
 *---------------------------------------------------------------
 * Append your own IoC class in ioc.php file
 */
include 'ioc'.EXT;

/*
 *---------------------------------------------------------------
 * RESOLVE ALL CLASSES
 *---------------------------------------------------------------
 * $test = IoC::resolve('classname');
 * 附带一个simpleexcel做演示？
 */
//$test = IoC::resolve('classname');

/*
 *---------------------------------------------------------------
 * SET CONTROLLER AND MODEL CLASSES TO BE AUTOLOAD
 *---------------------------------------------------------------
 * 参考：http://php.net/manual/en/function.spl-autoload-register.php
 * $classname  eg. Default_Controller
 */
spl_autoload_register(function ($classname){
    try {
        $fileFound = false;

        $filename = PATH_APP_C.strtolower($classname).EXT;
        if( is_file($filename) ){
            $fileFound = true;
            include $filename;
        }

        $filename = PATH_APP_M.strtolower($classname).EXT;
        if( is_file($filename) ){
            $fileFound = true;
            include $filename;
        }

        if($fileFound = false) {
            throw new Phxexception("无法自动加载该类：".$filename);
        }
    } catch(Phxexception $e) {
        echo $e->getMsg();
    }
});

/*
 *---------------------------------------------------------------
 * INCLUDE php-o
 *---------------------------------------------------------------
 */
if ($CFG::get('application.php-o') === true) {
    include PATH_CORE_PLUGINS . 'php-o' . DS . 'O.php';
}


/*
 *---------------------------------------------------------------
 * ROUTE URI TO CONTROLLER/METHOD
 *---------------------------------------------------------------
 * eg. index.php?c=default&a=index [decrypted]
 * eg. index.php/default/hello/param1/value1/param2/value2
 */
/*
require 'frontcontroller.php';
$frontController = FrontController::getInstance();
$frontController->route();
*/


$request = new Request();
$request->url_elements = array();

// eg. /default/index
if(isset($_SERVER['PATH_INFO'])) {
  $request->url_elements = explode('/', $_SERVER['PATH_INFO']);
}

$request->verb = $_SERVER['REQUEST_METHOD'];

switch($request->verb) {
  case 'GET':
    $request->parameters = $_GET;
    break;
  case 'POST':
  case 'PUT':
    $request->parameters = json_decode(file_get_contents('php://input'), 1);
    break;
  case 'DELETE':
  default:
    $request->parameters = array();
}

                                /*
                                $controllerName = ucfirst($this->_controller) . '_Controller';
                                $controllerHandler = new $controllerName();

                                $action = 'action_'.$this->_action;
                                if(!method_exists($controllerHandler, $action))
                                    throw new Exception('不存在方法：'.$action);

                                $controllerHandler->$action($this->_params);
                                */

// 如果是get就用原来的方式？

if($request->url_elements) {
  $controller_name = ucfirst($request->url_elements[1]) . '_Controller';
  if(class_exists($controller_name)) {
    $controller = new $controller_name();
    $action_name = 'action_' . ucfirst($request->verb);
    $response = $controller->$action_name($request);
  } else {
    header('HTTP/1.0 400 Bad Request');
    $response = "Unknown Request for " . $request->url_elements[1];
  }
} else {
  header('HTTP/1.0 400 Bad Request');
  $response = "Unknown Request";
}

echo json_encode($response);
