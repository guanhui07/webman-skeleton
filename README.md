# webman-skeleton

## install
```
composer create-project guanhui07/webman-skeleton
composer install
```




## aop
https://github.com/hyperf/aop-integration

app/aspect/DebugAspect.php

## command
命令行
https://www.workerman.net/plugin/1

php webman make:command test:command


php webman test:command


## crontab

app/crontab/TestCrontab.php

config/crontab.php

app/process/Task.php 获取 配置执行

https://www.workerman.net/doc/webman/components/crontab.html

## event

配置
config/plugin/yzh52521/event/app.php


https://www.workerman.net/plugin/27

触发
```php
event('test',[new TestEvent(['test'=>'event data'])]);
```


## websocket gateway
https://www.workerman.net/plugin/5

是否开启ws
config/plugin/webman/gateway-worker/app.php

config/plugin/webman/gateway-worker/process.php





