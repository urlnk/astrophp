# ♍ Virgophp 2018.9.27

包 wuding/virgophp

源码库 https://github.com/urlnk/astrophp

相比较上一时期，自己写的包 wuding/equiv-route 支持路由适配器方式



## 安装配置

```bash
# 安装
composer install

# 更新包
composer update wuding/topdb
```

app/config.php

```php
# 数据库文件
# https://github.com/devops-env/env
'database2' => array(
    'db_name' => ROOT_DIR . '/dev/sync/db/sqlite/search.sqlite3',
),
```



## 需要开启的扩展

|            | 文件 | 应用模块 | 控制器      | 动作方法 |
| ---------- | ---- | -------- | ----------- | -------- |
| pdo_sqlite |      | index    | Index       | index    |
| pdo_mysql  |      | _module  | _Controller | s        |
|            |      |          |             |          |