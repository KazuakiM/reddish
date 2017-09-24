<?php

namespace Kazuakim\Reddish;

/**
 * Clients.
 *
 * @property array $config
 * @property array $_defaultConfig
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
    private $_defaultConfig = [
        'host' => null,
        'port' => 6379,
        'timeout' => 0.0,
        'password' => null,
        'serializer' => \Redis::SERIALIZER_NONE,
        'persistent' => false,
    ];

    /**
     * __construct.
     *
     * @param array $config
     *
     * @throws \RedisException
     */
    public function __construct(array $config) //{{{
    {
        $this->config = array_merge($this->_defaultConfig, $config);

        $this->connection();
    } //}}}

    /**
     * connection.
     *
     * @throws \RedisException
     *
     * @return \Kazuakim\Reddish\Clients
     */
    public function connection(): \Kazuakim\Reddish\Clients //{{{
    {
        // connect
        if (!$this->config['persistent'] && !@$this->connect($this->config['host'], $this->config['port'], $this->config['timeout'])) {
            throw new \RedisException('connect errored.');
        } elseif (!@$this->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'])) {
            throw new \RedisException('pconnect errored.');
        }

        // auth
        if (0 < strlen($this->config['password']) && !@$this->auth($this->config['password'])) {
            throw new \RedisException('auth errored.');
        }

        // serializer
        $this->setSerializer($this->config['serializer']);

        return $this;
    } //}}}

    /**
     * setSerializer.
     *
     * @param int $serializer
     *
     * @throws \Expectation
     *
     * @return \Kazuakim\Reddish\Clients
     */
    public function setSerializer($serializer): \Kazuakim\Reddish\Clients //{{{
    {
        assert(in_array($serializer, self::_getSerializerArray(), true), 'serializer setting errored.');

        $this->setOption(\Redis::OPT_SERIALIZER, $serializer);

        return $this;
    } //}}}

    /**
     * close.
     */
    public function close() //{{{
    {
        if ($this->config['persistent']) {
            parent::close();
        }

        return;
    } //}}}

    /**
     * _getSerializerArray.
     *
     * @return array
     */
    private static function _getSerializerArray(): array //{{{
    {
        if (false === extension_loaded('igbinary')) {
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
