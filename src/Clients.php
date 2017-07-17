<?php

namespace Kazuakim\Reddish;

/**
 * Clients.
 *
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/reddish
 */
class Clients extends \Redis //{{{
{
    protected $config;
    private static $_defaultConfig = [
        'host' => null,           //can be a host, or the path to a unix domain socket
        'port' => 6379,
        'timeout' => 0.0,         //value in seconds (optional, default is 0 meaning unlimited)
        'reserved' => null,       //should be NULL if retry_interval is specified
        'retry_interval' => null, //value in milliseconds
        'persistent_id' => '',    //identity for the requested persistent connection
        'password' => null,
        'serializer' => \Redis::SERIALIZER_NONE,
        'persistent' => false,    //default is connect
    ];

    public function __construct(array $config) //{{{
    {
        $this->config = array_merge(self::$_defaultConfig, $config);

        $this->connection();
    } //}}}

    public function connection() //{{{
    {
        // connect
        if (!$this->config['persistent'] && !@$this->connect($this->config['host'], $this->config['port'], $this->config['timeout'], $this->config['reserved'], $this->config['retry_interval'])) {
            throw new ReddishException('connect errored.');
        } elseif (!@$this->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'], $this->config['persistent_id'], $this->config['retry_interval'])) {
            throw new ReddishException('pconnect errored.');
        }

        // auth
        if (0 < strlen($this->config['password']) && !$this->auth($this->config['password'])) {
            throw new ReddishException('auth errored.');
        }

        // serializer
        $this->setSerializer($this->config['serializer']);
    } //}}}

    public function setSerializer($serializer) //{{{
    {
        assert(in_array($serializer, self::_getSerializerArray(), true), 'serializer setting errored.');

        if (!$this->setOption(\Redis::OPT_SERIALIZER, $serializer)) {
            throw new ReddishException('serializer errored.');
        }
    } //}}}

    public function close() //{{{
    {
        if ($this->config['persistent']) {
            parent::close();
        }
    } //}}}

    public function ping() //{{{
    {
        try {
            parent::ping();
        } catch (\RedisException $e) {
            throw new ReddishException($e->getMessage());
        }
    } //}}}

    private static function _getSerializerArray(): array //{{{
    {
        if (extension_loaded('igbinary') === false) {
            return [
                \Redis::SERIALIZER_NONE,
                \Redis::SERIALIZER_PHP,
            ];
        }

        return [
            \Redis::SERIALIZER_NONE,
            \Redis::SERIALIZER_PHP,
            \Redis::SERIALIZER_IGBINARY,
        ];
    } //}}}
} //}}}
