# php-svn-hook-skip

====简要说明====

1. 在SVN COMMIT 提交的时候，支持忽略不希望检查的目录或文件

2. 时间仓促，只是进行了简单的扩展支持，重点是提供一个思路，有兴趣的自己研究源码继续自行优化

3. 使用很简单:

(1) 找到配置文件 /path/to/config/skip.file.php 进行要忽略的目录或文件即可

(2) 配置SVN PRE-COMMIT的钩子脚本: /path/to/svn_house/project/hooks/pre_commit, 增加如下一条指令：

    /path/to/php /path/to/php-svn-hook/svn_pre_commit_hook.php $1 $2 --include=EmptyComment:Syntax  >> /tmp/php_svn_hook.log

