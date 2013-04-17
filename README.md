Phx
===

Phx - A Micro assembled  RESTful PHP Framework For Beginners with one day learning curve

<http://localhost/phx/default/hello>

## TODO

- 参考 CI 的优秀点：<http://codeigniter.org.cn/forums/thread-14343-1-1.html>

设计原则

- 约定优于配置原则
- KISS原则（极简主义）
- 灵活性/非灵活性
- 内置 firephp/dBug 调试
- 优秀的代码书写体验
- 优质的代码和架构
- 代码易读性
- 表达性：借鉴 laravel，方法的操纵和参数让人直接明白它要做的意思

借用其他框架中收集的优秀函数

完全遵循 PSR-2 编码规范

全英文文档的撰写

gitpage 等项目官方网站

插件用注册器模式

鸣谢 [justjavac](http://justjavac.com) 等

## 渐进增强

* 『最新文章』的url是 article/tab/last
* 『热门文章』的url是 article/tab/hot

这样，每次点击链接，页面都会刷新。即使不启用 js 页面也可以正常访问浏览。
现在往里面加入 js 代码，【增强】它的用户体验。
我还没有说完呢。问题是什么呢？
不能 history，
不能外部链接直接访问
不过看看 twitter 或者 gmail 就知道怎么解决了。
就是用js修改 segment 也就是

* 『最新文章』的url是 article/tab#last
* 『热门文章』的url是 article/tab#hot

PHP:

    class Tab extends Controller{
        function last() // 处理 article/tab/last
        function hot()  // 处理 article/tab/hot
    }

JS:

    var Tab = {
        "last": function() // 处理 article/tab#last
        "hot": function()  // 处理 article/tab#hot
    }

**框架的作用是 DRY**

    assets
    js-php conversion
    encryption
    database
        orm
        active record
        migration
        scafforlding
    bootstrap
    uri
    session
    email
    auth
    config
    string
    file
    markdown
    route

## 文档

#### 配置类
要设置一个配置项目，只需修改`app/config`下的文件。你还可以参考现有的配置文件创建自己的配置文件。

    # 获得一个配置项目的内容，这里的application就是配置文件的文件名
    Config::get('application');          // 数组形式返回application键下的所有项目
    Config::get('application.timezone'); // 如果这不是数组，就返回项目的值

    # 判断配置项目是否存在
    Config::has('application.timezone'); // 返回布尔值 true / false

#### debugger
抛弃`var_dump()`吧！Phx框架集成了两种调试器，支持任何数据类型！

如果要禁用调试器，可在配置文件`application`中，修改`debug`项目为`false`；如果要更换调试器，请修改`debug_tool`为`dbug`或`kint`。

要调试内容，请这样：

    d( $var );
    d( $var1, $var2 );

<del>
#### Kint （需开启 mb_string）
http://raveren.github.io/kint

    d( $var );

    // 同 d( $var ); die;
    dd( $var );

    d( $var1, $var2 );

    // 禁用输出
    Kint::enabled(false);

#### dBug
http://dbug.ospinto.com/

    new dBug(get_defined_vars());

    $constants = get_defined_constants(true);
    new dBug($constants['user']);
</del>

#### 日志类
日志文件都位于`app/logs`目录，根据日期每天创建一个文件。如果你想在程序中创建一条日志记录，可以：

    Log::info('This is an information message');
    Log::warn('This is a warn message');
    Log::error('This is an error message');

