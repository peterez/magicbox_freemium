<?php  class Magicbox_CacheMemcache{    private static $instance = NULL;    private $lifeTime = 43200;    private $memcachePureConnection;    protected function __construct($args)    {        $m = new Memcache();        $m->connect($args['host'], $args['port']);        $this->memcachePureConnection = $m;        if ($args['lifeTime'] != "") {            $this->lifeTime = $args['lifeTime'];        }        self::$instance[md5(serialize($args))] = $m;    }    /* Singleton Pattern */    public static function  getInstance($args)    {        $args['host'] = $args['host'] == "" ? "127.0.0.1" : $args['host'];        $args['port'] = $args['port'] == "" ? "11211" : $args['port'];        if (self::$instance == NULL) {            return self::$instance[md5(serialize($args))] = new Magicbox_CacheMemcache($args);        }        return self::$instance[md5(serialize($args))];    }    private function getMemcacheKeys()    {        $allSlabs = $this->memcachePureConnection->getExtendedStats('slabs');        /*  $items = $this->memcachePureConnection->getExtendedStats('items'); */        $return = array();        foreach ($allSlabs as  $slabs) {            foreach ($slabs as $slabId => $slabMeta) {                $cdump = $this->memcachePureConnection->getExtendedStats('cachedump', (int)$slabId);                foreach ($cdump as $arrVal) {                    if (!is_array($arrVal)) continue;                    foreach ($arrVal AS $k => $v) {                        $return[] = $k;                    }                }            }        }        return $return;    }    function flush()    {        $keys = $this->getMemcacheKeys();        foreach ($keys as $k) {            if (substr($k, 0, 9) == "mb_cache_") {                $this->memcachePureConnection->delete($k);            }        }    }    public function get($key)    {        return $this->memcachePureConnection->get($key);    }    public function set($key, $data)    {        return $this->memcachePureConnection->set($key, $data,0,$this->lifeTime);    }    public function delete($key)    {        return $this->memcachePureConnection->delete($key);    }}?>