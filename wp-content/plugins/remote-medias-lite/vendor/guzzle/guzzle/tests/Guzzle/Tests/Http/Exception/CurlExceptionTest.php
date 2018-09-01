<?php

namespace WPRemoteMediaExt\Guzzle\Tests\Http\Exception;

use WPRemoteMediaExt\Guzzle\Http\Exception\CurlException;
use WPRemoteMediaExt\Guzzle\Http\Curl\CurlHandle;

/**
 * @covers WPRemoteMediaExt\Guzzle\Http\Exception\CurlException
 */
class CurlExceptionTest extends \WPRemoteMediaExt\Guzzle\Tests\GuzzleTestCase
{
    public function testStoresCurlError()
    {
        $e = new CurlException();
        $this->assertNull($e->getError());
        $this->assertNull($e->getErrorNo());
        $this->assertSame($e, $e->setError('test', 12));
        $this->assertEquals('test', $e->getError());
        $this->assertEquals(12, $e->getErrorNo());

        $handle = new CurlHandle(curl_init(), array());
        $e->setCurlHandle($handle);
        $this->assertSame($handle, $e->getCurlHandle());
        $handle->close();
    }
}
