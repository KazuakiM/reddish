<?php

namespace Kazuakim\Reddish;

/**
 * ClientsTest.
 *
 * @property array  $_defaultConfig
 * @property object $_clients;
 *
 * @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @link      https://github.com/KazuakiM/reddish
 */
class ClientsTest extends \PHPUnit\Framework\TestCase //{{{
{
    // Class variable {{{
    private $_defaultConfig = [
        // 'connect' paramater
        'host' => '127.0.0.1', //can be a host, or the path to a unix domain socket
        'port' => 6379,
        'timeout' => 1.0,      //value in seconds (optional, default is 0 meaning unlimited)
        'reserved' => null,    //should be NULL if retry_interval is specified
        'read_timeout' => 1.0, //value in seconds (optional, default is 0 meaning unlimited)

        // 'pconnect' paramater
        'persistent_id' => '', //identity for the requested persistent connection

        // 'auth' paramater
        'password' => null,

        // serializer
        'serializer' => \Redis::SERIALIZER_NONE,

        // 'connect' or 'pconnect'
        'persistent' => false, //default is connect
    ];

    private $_clients;
    //}}}

    /**
     * setUp.
     */
    protected function setUp() //{{{
    {
        $this->_clients = new Clients($this->_defaultConfig);
    } //}}}

    /**
     * tearDown.
     */
    protected function tearDown() //{{{
    {
        unset($this->_clients);
    } //}}}

    /**
     * testConnectionPersistentError.
     *
     * @expectedException        \RedisException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage connect errored.
     */
    public function testConnectionPersistentError() //{{{
    {
        $config = $this->_defaultConfig;
        $config['host'] = '127.0.0.2';
        $clients = new Clients($config);
    } //}}}

    /**
     * testConnectionError.
     *
     * @expectedException        \RedisException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage pconnect errored.
     */
    public function testConnectionError() //{{{
    {
        $config = $this->_defaultConfig;
        $config['host'] = '127.0.0.2';
        $config['persistent'] = true;
        $clients = new Clients($config);
    } //}}}

    /**
     * testAuthError.
     *
     * @expectedException        \RedisException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage auth errored.
     */
    public function testAuthError() //{{{
    {
        $config = $this->_defaultConfig;
        $config['password'] = 'dummy';
        $clients = new Clients($config);
    } //}}}

    /**
     * testPingError.
     *
     * @expectedException        \RedisException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Connection closed
     */
    public function testPingError() //{{{
    {
        $config = $this->_defaultConfig;
        $config['persistent'] = true;
        $clients = new Clients($config);
        $clients->close();
        $clients->ping();
    } //}}}

    /**
     * testConnection.
     */
    public function testConnection() //{{{
    {
        $this->assertTrue($this->_clients->isConnected());

        $config = $this->_defaultConfig;
        $config['persistent'] = true;
        $clients = new Clients($config);
        $this->assertTrue($clients->isConnected());
        $clients->ping();
        $clients->close();

        unset($clients);
    } //}}}

    /**
     * testIsConnected.
     */
    public function testIsConnected() //{{{
    {
        $config = $this->_defaultConfig;
        $config['persistent'] = true;
        $clients = new Clients($config);
        $this->assertTrue($clients->isConnected());
        $clients->close();

        $this->assertFalse($clients->isConnected());
        $clients->close();
    } //}}}

    /**
     * testSave.
     */
    public function testSave() //{{{
    {
        $this->_clients->set('key', '1');
        $this->assertSame('1', $this->_clients->get('key'));
    } //}}}
} //}}}
