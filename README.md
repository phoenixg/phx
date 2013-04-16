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



