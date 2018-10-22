## 使用说明
* [点击访问老外的源项目](http://jeanmonod.github.io/php-svn-hook/)

* 金牛座在源项目基础上只做了简单的扩展支持，重点是提供一个思路，有兴趣的自行优化

* 关于具体使用，[请移步访问金牛座博客](http://www.blogdaren.com/post-2255.html)

* 在 SVN COMMIT 提交的时候，支持配置忽略不希望检查的目录或文件

* 找到配置文件 /path/to/config/skip.file.php 进行要忽略的目录或文件即可

* 配置SVN PRE-COMMIT的钩子脚本: /path/to/project/hooks/pre_commit, 增加如下一条指令：

```
/path/to/php /path/to/php-svn-hook/svn_pre_commit_hook.php.php $1 $2 --include=EmptyComment:Syntax >> /tmp/hook.log
```

## 效果截图
![demo1](https://github.com/blogdaren/php-svn-hook/blob/master/image/demo1.jpg)
![demo2](https://github.com/blogdaren/php-svn-hook/blob/master/image/demo2.jpg)
