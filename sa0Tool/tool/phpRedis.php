<?php

class phpRedis
{
    final function getRedis()
    {
        $redisConfig = APP_CONFIG['redis'];
        $redis = new Redis();
        $redis->connect($redisConfig['host'], $redisConfig['port']);
        if (isset($redisConfig['password'])) $this->redis->auth($redisConfig['password']);
        return $redis;
    }
}
