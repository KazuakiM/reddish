<?php

namespace KazuakiM\Reddish;

/**
 * ClientsTest.
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
    private static $_defaultConfig = [
        // 'connect' paramater
        'host' => '127.0.0.1',    //can be a host, or the path to a unix domain socket
        'port' => 6379,
        'timeout' => 1.0,         //value in seconds (optional, default is 0 meaning unlimited)
        'reserved' => null,       //should be NULL if retry_interval is specified
        'retry_interval' => null, //value in milliseconds
        'read_timeout' => 1.0,    //value in seconds (optional, default is 0 meaning unlimited)

        // 'pconnect' paramater
        'persistent_id' => '',    //identity for the requested persistent connection

        // 'auth' paramater
        'password' => null,

        // serializer
        'serializer' => \Redis::SERIALIZER_NONE,

        // 'connect' or 'pconnect'
        'persistent' => false,    //default is connect
    ];
    //}}}

    protected function setUp() //{{{
    {
    } //}}}

    /**
     * @expectedException        \KazuakiM\Reddish\ReddishException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage connect errored.
     */
    public function testConnectionPersistentError() //{{{
    {
        $config = self::$_defaultConfig;
        $config['host'] = '127.0.0.2';
        $clients = new Clients($config);
    } //}}}

    /**
     * @expectedException        \KazuakiM\Reddish\ReddishException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage pconnect errored.
     */
    public function testConnectionError() //{{{
    {
        $config = self::$_defaultConfig;
        $config['host'] = '127.0.0.2';
        $config['persistent'] = true;
        $clients = new Clients($config);
    } //}}}

    /**
     * @expectedException        \KazuakiM\Reddish\ReddishException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Connection closed
     */
    public function testPingError() //{{{
    {
        $config = self::$_defaultConfig;
        $config['persistent'] = true;
        $clients = new Clients($config);
        $clients->close();
        $clients->ping();
    } //}}}

    public function testConnection() //{{{
    {
        $config = self::$_defaultConfig;
        $clients = new Clients($config);
        $this->assertTrue($clients->isConnected());
        unset($clients);

        $config['persistent'] = true;
        $clients = new Clients($config);
        $this->assertTrue($clients->isConnected());
        $clients->ping();
        $clients->close();
        unset($clients);
    } //}}}

    public function testSave() //{{{
    {
        $config = self::$_defaultConfig;
        $clients = new Clients($config);
        $clients->set('key', 1);
        $this->assertSame('1', $clients->get('key'));
    } //}}}
} //}}}
