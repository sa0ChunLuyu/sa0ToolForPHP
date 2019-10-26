# sa0ToolForPHP

```
author : sa0ChunLuyu  
date : 2019/10/26 16:20
```

* 以下配置示例 以 `./backend` 为例。

### Nginx 配置

```
server {
    listen       80;
    server_name  sa0Tool.yo.qhdedu.com;
    root   /home/web/sa0Tool.yo.qhdedu.com;

    location /backend {
          rewrite ^/backend/(.*)$ /backend/index.php?routeInfo=$1;
    }
    
    location / {
        try_files $uri $uri/ index.php;
        index  index.html index.htm index.php;
    }

    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
    #access_log  /var/log/nginx/api.log  main;
}
```

### 引入文件示例
```php
./backend/index.php

<?php
define(APP_PATH, __DIR__);
include_once dirname(__DIR__) . '/sa0Tool/index.php';
```

### Config 文件示例
```php
./backend/config.php

<?php
$appConfig = array();
```

### 跨域
* 允许跨域 = 公共 +  控制器
```php
./backend/config.php

<?php
$appConfig = array(
// ...
    'allowOrigin' => array(
        // 公共 跨域
        'appOrigin' => array(
            'http://localhost:8080'
        ),
        // 根据 控制器 跨域
        'controllerName' => array(
            'http://localhost:8081'
        ),
    ),
// ...
);
```

### 请求类型
* 默认 接收全部类型
* 允许类型 = 控制器 > 公共
```php
./backend/config.php

<?php
$appConfig = array(
// ...
    'requestMethod' => array(
        // 公共 跨域
        'appMethod' => array(
            'GET'
        ),
        // 根据 控制器 跨域
        'controllerName' => array(
            'POST'
        ),
    ),
// ...
);
```

### 文件引入
* 文件同名 优先从 `/backend/tool` 引入
* 引入 = 公共 + 控制器
```php
./backend/config.php

<?php
$appConfig = array(
// ...
    'include' => array(
            // 公共引入
            'public' => array(
                'pdo'
            ),
            // 根据 控制器 引入
            'controllerName' => array(
                'pinyin'
            ),
    ),
// ...
);
```

### PDO 使用
* 文件引入 见 Include 部分。

#### 配置
```php
./backend/config.php

<?php
$appConfig = array(
// ...
    'db' => array(
        'host' => '127.0.0.1',
        'dbName' => 'test',
        'port' => '3306',
        'user' => 'root',
        'password' => 'xxxxxxxx',
    ),
// ...
);
```

#### 使用
```php
<?php
$insert = $this->pdo->createRow($tableName, $data = array());
$update = $this->pdo->updateRow($tableName, $whereData, $data = array());
$select = $this->pdo->readRow($tableName, $whereData, $rows = false);
$delete = $this->pdo->deleteRow($tableName, $whereData);
```
