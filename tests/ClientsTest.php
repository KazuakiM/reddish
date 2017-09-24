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
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 1.0,
        'password' => null,
        'serializer' => \Redis::SERIALIZER_NONE,
        'persistent' => false,
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
        unset($clients);
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
        unset($clients);
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
        unset($clients);
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
        $config = $this->_defaultConfig;
        $config['persistent'] = true;
        $clients = new Clients($config);
        $this->assertTrue($clients-> /* @scrutinizer ignore-call */ isConnected());
        $clients->ping();
        $clients->close();

        $this->assertFalse($clients-> /* @scrutinizer ignore-call */ isConnected());
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
