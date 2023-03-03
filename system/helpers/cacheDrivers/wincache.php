<?php  class Magicbox_CacheWincache{    private static $instance = NULL;    private $lifeTime = 43200;    protected function __construct($args)    {    return $this;    }    /* Singleton Pattern */    public static function  getInstance($args = array())    {        if (self::$instance == NULL) {            return self::$instance[md5(serialize($args))] = new Magicbox_CacheWincache($args);        }        return self::$instance[md5(serialize($args))];    }    function flush()    {        wincache_ucache_clear();    }    public function get($key)    {        return wincache_ucache_get($key);    }    public function set($key, $data)    {        return wincache_ucache_set(            $key,            $data,            $this->lifeTime        );    }    public function delete($key)    {        return wincache_ucache_delete($key);    }}?>