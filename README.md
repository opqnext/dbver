## 数据库协同版本管理工具

标签（空格分隔）： dbver manual
<font color="green">
<sub>v0.1
2016.01.06<sub>
</font>


---
## 目录

* [文档更新记录](#History)
* [环境要求](#Environment)
* [单一环境配置](#Simpleness)
* [多环境配置](#Servers)
* [生产环境升级对比](#UpDiff)
* [生产环境自动同步工具](#Sync)


---
<div id="History"></div>
## 文档更新记录

版本|作者|时间|备注
---|---|---|---
v0.1|戚银|2016.01.06|创建

<font color="gray">备注：版本变更记录看这个表，小改动请参看git commit comment</font>



---
<div id="Environment"></div>
## 环境要求

* 安装git 2.4以上版本
* php 5.5 以上版本
* php运行用户有权限访问gitlab远程仓库
* 配置账户信息有权限提交到gitlab的master分支
* dbver/data目录php运行账户有rw权限
* 配置数据库必须存在
* mysql连接账户必须有表修改权限




---
<div id="Simpleness"></div>
## 单一环境配置

修改config.example.php示例配置文件为config.php

配置git相关参数
```php
$config['GIT_BIN'] = "git";     // git命令可执行路径，如果加入到环境变量可以直接写git
$config['GIT_PATCH'] = "";      // gitlab仓库地址
$config['GIT_USER'] = "";       // git提交用户名
$config['GIT_EMAIL'] = "";      // git提交email，配置账户信息必须有git仓库master提交权限
```

配置数据信息
```php
$config['DB_HOST'] = '';   // mysql数据库地址
$config['DB_USER'] = '';   // mysql账户，必须有改表权限
$config['DB_PWD'] = '';    // 连接密码
$config['DB_PORT'] = '';   //  端口
$config['DB_NAME'] = '';   // 数据库必须已经创建
$config['DB_CHARSET'] = 'utf8';     // 字符集
```



---
<div id="Servers"></div>
## 多环境配置

修改config.server.example.php示例配置文件为config.php

配置git仓库，根据$i值不同可以配置多组仓库
```php
$config['SERVERS'][$i]["NAME"] = "项目名称";    //项目名称
$config['SERVERS'][$i]['GIT_BIN'] = "git";     // git可执行路径
$config['SERVERS'][$i]['GIT_PATCH'] = "";      // gitlab仓库地址
$config['SERVERS'][$i]['GIT_USER'] = "";       // git提交用户名
$config['SERVERS'][$i]['GIT_EMAIL'] = "";      // git提交email
$config['SERVERS'][$i]['DATA_PATH'] = ROOT_PATH."data/项目名称/";    // 仓库克隆地址，必须有rw权限
```

配置环境信息
```php
// 每个git仓库都可以配置多组环境的数据库信息
$config['SERVERS'][$i]['env'][$j]['NAME'] = "本地数据库";        // 环境名字
$config['SERVERS'][$i]['env'][$j]['DB_HOST'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_USER'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PWD'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PORT'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_NAME'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_CHARSET'] = 'utf8';
$config['SERVERS'][$i]['env'][$j]['DATA_PATH'] = 'local/';      // 在git仓库下的存储的位置
$j++;

$config['SERVERS'][$i]['env'][$j]['NAME'] = "正式环境数据库";
$config['SERVERS'][$i]['env'][$j]['DB_HOST'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_USER'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PWD'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PORT'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_NAME'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_CHARSET'] = 'utf8';
$config['SERVERS'][$i]['env'][$j]['DATA_PATH'] = 'pro/';
```



---
<div id="UpDiff"></div>
## 生产环境升级对比

在config.php中增加以下配置开启“生产环境升级对比”功能

```php
// 生产环境同步数据结构的数据库信息，配置的该帐号必须拥有创建和删除库的权限
$config['UPGRADE']['DB_HOST'] = '';
$config['UPGRADE']['DB_USER'] = '';
$config['UPGRADE']['DB_PWD'] = '';
$config['UPGRADE']['DB_PORT'] = '';
$config['UPGRADE']['DB_NAME'] = '';
$config['UPGRADE']['DB_CHARSET'] = 'utf8';

// 同步工具，dbver依赖该工具来同步数据库信息
/* 该工具接受以下参数，均为配置的同步结构的临时数据库信息：
 * --host
 * --user
 * --password
 * --dbname
 * --port
 */
$config['UPGRADE']['SYNC_BIN'] = 'dbver_up.py update';
```

该功能在第一次访问时自动调用同步工具进行同步，在一周内将不再自动同步，但是会在页面提示上次同步时间，以便你决定是否再次同步。



---
<div id="Sync"></div>
## 生产环境自动同步工具

dbver_up.py需要自己根据需求环境进行编写，脚本是用来作为同步生产环境数据库的工具，以下作为工具参考。
```
python dbver_up.py update [--host 本地数据库信息] [--user 用户名] [--password 密码] [--dbname 数据库] [--port 端口号]
dbver_up.py脚本通过获取生产环境数据库sql并导入参数设置的mysql里数据库里
