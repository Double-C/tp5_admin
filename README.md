## 项目概述

* 产品名称：tp5_admin
* 用途：快速搭建专属的后台管理系统

> 基于ThinkPHP 5.1 + LayUi 1.0 搭建的后台管理系统
>
> 只包含最简单的后台用户、后台菜单、权限分配模块
>
> 可基于此项目进行扩展属于自己的后台系统。



## 推荐运行环境

* Nginx 1.8+
* PHP 7.0+
* Mysql 5.7+



## 部署 / 安装

1. 克隆源代码

克隆 `tp5_admin` 到本地

```shell
> git clone https://github.com/Double-C/tp5_admin.git
```

2. 安装依赖

```shell
> composer install
```

3. 生成配置文件

```shell
cp .env.example .env
```

你可以根据情况修改 `.env` 文件里内容，如数据库链接，应用名称等。

4. 生成数据表及数据

将项目根目录下的 `tp5_admin.sql` 导入到数据库中。