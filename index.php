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

// 定义文件的路径
define('EXT',       '.php');
define('FILE_BASE', PATH_BASE . 'index' .   EXT);
define('FILE_LOG',  PATH_LOGS .   date('Y-m-d') . '.log');

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
if ($CFG::get('application.error_reporting') === true) {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors','Off');
    error_reporting(0);
}

/*
 *---------------------------------------------------------------
 * SET DEBUG HANDLER
 *---------------------------------------------------------------
 */
// 这里要用一个通用的东西，比如工厂类?
if ($CFG::get('application.debug') === true) {
    switch ( $CFG::get('application.debug_tool') ) {
         case 'dbug':
             //include PATH_CORE_DEBUG . 'dBug/dBug.php';
             break;
         case 'kint':
             echo 'cat';
             break;
         default:
             echo 'whatever';
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
require 'ioc'.EXT;

/*
 *---------------------------------------------------------------
 * RESOLVE ALL CLASSES
 *---------------------------------------------------------------
 * $test = IoC::resolve('classname');
 */
//$test = IoC::resolve('classname');

// TODO here
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

// 在这里重写自动加载，如何把各文件夹下的都加载进来，包括类库文件，还是不用包括？
// new Default_Controller();
spl_autoload_register(function ($classname){
    $filename = PATH_APP_C.strtolower($classname).EXT;
    if(is_file($filename)){
        include $filename;
        echo 'go';
    }
});








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



/*
 *---------------------------------------------------------------
 * SHOW DEBUG INFORMATION
 *---------------------------------------------------------------
 */
//new dBug(get_defined_vars());

$constants = get_defined_constants(true);
//new dBug($constants['user']);

