<?php

namespace WPRemoteMediaExt\Guzzle\Tests\Plugin\CurlAuth;

use WPRemoteMediaExt\Guzzle\Common\Version;
use WPRemoteMediaExt\Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
use WPRemoteMediaExt\Guzzle\Http\Client;

/**
 * @covers WPRemoteMediaExt\Guzzle\Plugin\CurlAuth\CurlAuthPlugin
 */
class CurlAuthPluginTest extends \WPRemoteMediaExt\Guzzle\Tests\GuzzleTestCase
{
    public function testAddsBasicAuthentication()
    {
        Version::$emitWarnings = false;
        $plugin = new CurlAuthPlugin('michael', 'test');
        $client = new Client('http://www.test.com/');
        $client->getEventDispatcher()->addSubscriber($plugin);
        $request = $client->get('/');
        $this->assertEquals('michael', $request->getUsername());
        $this->assertEquals('test', $request->getPassword());
        Version::$emitWarnings = true;
    }

    public function testAddsDigestAuthentication()
    {
        Version::$emitWarnings = false;
        $plugin = new CurlAuthPlugin('julian', 'test', CURLAUTH_DIGEST);
        $client = new Client('http://www.test.com/');
        $client->getEventDispatcher()->addSubscriber($plugin);
        $request = $client->get('/');
        $this->assertEquals('julian', $request->getUsername());
        $this->assertEquals('test', $request->getPassword());
        $this->assertEquals('julian:test', $request->getCurlOptions()->get(CURLOPT_USERPWD));
        $this->assertEquals(CURLAUTH_DIGEST, $request->getCurlOptions()->get(CURLOPT_HTTPAUTH));
        Version::$emitWarnings = true;
    }
}
