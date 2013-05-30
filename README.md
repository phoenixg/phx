Phx
===

Phx - A Tiny assembled  RESTful PHP Framework For Beginners with one day learning curve

灵感来源：
[laravel](http://laravel.com/),
[CodeIgniter](http://ellislab.com/codeigniter),
[Toper](http://my.oschina.net/mingtingling/blog?catalog=263852)

特别鸣谢：[justjavac](http://justjavac.com)

初始路径：<http://localhost/phx/default/hello>

## 框架的使命（Mission）
- 学习：如果你从未接触过框架，或只想用最少的代码去做一个很小的项目，那么这个框架会帮助你轻松上手，了解PHP框架的一些基本知识
- 自我学习：通过编写一个开发框架，可以很好地锻炼自己，学会和巩固一些设计模式及其他的基础知识
- 集成了一些易用的工具，如果你发现自己无可救药地爱上了这些工具，那么你可能会想在自己的其他项目中使用它们

<!--
## TODO
- 参考博客里的优秀代码和知识点，用在框架上
- 下一步要做的是实现rest，还是模板引擎？还是不要用模板引擎？
- 下一步数据库层,orm和dao,ar的实现怎么做？ 装配？
- 下一步先做什么？
- 不提供前端框架？
- 文件操作类、加密类、邮件类等
- 完全遵循 PSR-2 编码规范
- 默认主页：安装成功的首页显示凤凰的字符图，它说"成功安装了，删掉我，开始写代码"之类的话

## 设计导向（只是导向，不一定完美实现了）
- 约定优于配置原则
- KISS原则（极简主义）
- 灵活性/非灵活性的权衡
- 优秀的代码书写体验，借鉴laravel的语义性，方法的操纵和参数让人直接明白它要做的意思
- 优质的代码和架构
- 优秀的代码易读性
-->

## 文档 （Documentation）

All you need to know about this framework is inside this page and `default_controller.php` in which sample codes are provided.

#### 安装 （INSTALLATION）

    # 环境要求：需要 PHP 5.3 及以上版本，确认开启了mb_string, curl扩展
    # Requirements: >=PHP 5.3 with `mb_string`,`curl` extension enabled
    sudo apt-get install php5-curl

<!--
首先：
    php composer.phar install
-->

#### 配置类 （CONFIGURATION）
    # 要设置一个配置项目，只需修改`app/config`下的文件。你还可以参考现有的配置文件创建自己的配置文件
    # To configure an item, just modify files under `app/config`. You can also create your own configuration files under this folder

    # 获得一个配置项目的内容，这里的application就是配置文件的文件名
    # To retrieve a configuration item: (here `application` is configuration file name)
    Config::get('application');          // 数组形式返回application键下的所有项目
    Config::get('application.timezone'); // 如果这不是数组，就返回项目的值

    # 判断配置项目是否存在
    # To determine whether a configuration item exists
    Config::has('application.timezone'); // 返回布尔值 true / false

#### debugger
    抛弃`var_dump()`吧！Phx框架装配了两种调试器：
    （[Kint](http://raveren.github.io/kint) 和 [dBug](http://dbug.ospinto.com/)），支持任何数据类型！

    如果要禁用调试器，可在配置文件`application`中，修改`debug`项目为`false`；
    如果要更换调试器，请修改`debug_tool`为`dbug`或`kint`。

    # 要调试内容，请这样：
    d( $var );
    d( $var1, $var2 );

<!--
#### Kint （需开启 mb_string）
    d( $var );

    // 同 d( $var ); die;
    dd( $var );

    d( $var1, $var2 );

    // 禁用输出
    Kint::enabled(false);

#### dBug
    new dBug(get_defined_vars());

    $constants = get_defined_constants(true);
    new dBug($constants['user']);
-->

#### 服务器端日志类
    # 日志文件都位于`app/logs`目录（请确保该目录可写），根据日期每天创建一个文件。
    # 如果你想在程序中创建一条日志记录，可以：
    Log::info('This is an information message');
    Log::warn('This is a warn message');
    Log::error('This is an error message');

#### 浏览器端日志类
    # 抛弃`console.log`吧！Phx框架装配了浏览器端日志/调试类（[Log](http://adamschwartz.co/log/ )），用法：
    log(123)
    log('这是 *斜体字*')
    log('这是 _加粗字_')
    log('这是 `代码体`')
    log('这是 [c="color: red"]红色字[c]')

#### 支持对象语法
Phx框架装配了[php-o](https://github.com/jsebrech/php-o)，默认在`application.php-o`配置项中启用，它为PHP提供了对象语法操纵字符串等数据的能力，用法：

##### s() 用于处理字符串
    echo s("abc")->len();// 3
    echo s("abc")->substr(2); // c
    echo s("abcde")->pos("c"); // 2
    echo s("abcde")->explode("c"); // Array ( [0] => ab [1] => de )
    $s = s("abc"); echo $s[2]; // c

其他：`ipos()` , `rpos()` , ` ipos()` , ` trim()`, `ltrim()` , `rtrim()`, `pad()`, `len()`, `in_array()`
, `tolower()` , `toupper()` , `substr()`, `replace()` , `ireplace()`, `preg_match()`, `preg_match_all()`, `preg_replace()`

    # 还支持Javascript形式的函数
    echo s("abc")->toUpperCase(); // ABC

其他：`charAt()`, `indexOf()`, `lastIndexOf()`, `match()`, `replace()`, `split()`, `substr()`, `substring()`, `toLowerCase()`, `toUpperCase()`, `trim()`, `trimLeft()`, `trimRight()`, `valueOf()`

##### a() 用于处理数组
包括：`count()`, `has()` (而不是 `in_array()`), `search()`, `shift()`, `unshift()`, `key_exists()`, `implode()`, `keys()`, `values()`, `pop()`, `push()`, `slice()`, `splice()`, `merge()`, `map()`, `reduce()`, `sum()`, `begin()` (而不是 `reset()`), `next()`, `current()`, `each()` 和 `end()`

##### c() 连缀的支持
    echo c(s("abcde"))->explode("b")->implode("c"); // accde ， 等价于 cs()


#### 附带的常用组件
    jQuery 位于 `phx/assets/core/js` 下，版本是`1.9.1`， 可自行替换升级，引用参考`index.php`中定义的各种路径常量。


