<?php

namespace WPRemoteMediaExt\Guzzle\Tests\Service\Command;

use WPRemoteMediaExt\Guzzle\Http\Message\Response;
use WPRemoteMediaExt\Guzzle\Service\Client;
use WPRemoteMediaExt\Guzzle\Service\Command\DefaultResponseParser;
use WPRemoteMediaExt\Guzzle\Service\Command\OperationCommand;
use WPRemoteMediaExt\Guzzle\Service\Description\Operation;

/**
 * @covers WPRemoteMediaExt\Guzzle\Service\Command\DefaultResponseParser
 */
class DefaultResponseParserTest extends \WPRemoteMediaExt\Guzzle\Tests\GuzzleTestCase
{
    public function testParsesXmlResponses()
    {
        $op = new OperationCommand(array(), new Operation());
        $op->setClient(new Client());
        $request = $op->prepare();
        $request->setResponse(new Response(200, array(
            'Content-Type' => 'application/xml'
        ), '<Foo><Baz>Bar</Baz></Foo>'), true);
        $this->assertInstanceOf('SimpleXMLElement', $op->execute());
    }

    public function testParsesJsonResponses()
    {
        $op = new OperationCommand(array(), new Operation());
        $op->setClient(new Client());
        $request = $op->prepare();
        $request->setResponse(new Response(200, array(
            'Content-Type' => 'application/json'
        ), '{"Baz":"Bar"}'), true);
        $this->assertEquals(array('Baz' => 'Bar'), $op->execute());
    }

    /**
     * @expectedException \WPRemoteMediaExt\Guzzle\Common\Exception\RuntimeException
     */
    public function testThrowsExceptionWhenParsingJsonFails()
    {
        $op = new OperationCommand(array(), new Operation());
        $op->setClient(new Client());
        $request = $op->prepare();
        $request->setResponse(new Response(200, array('Content-Type' => 'application/json'), '{"Baz":ddw}'), true);
        $op->execute();
    }

    public function testAddsContentTypeWhenExpectsIsSetOnCommand()
    {
        $op = new OperationCommand(array(), new Operation());
        $op['command.expects'] = 'application/json';
        $op->setClient(new Client());
        $request = $op->prepare();
        $request->setResponse(new Response(200, null, '{"Baz":"Bar"}'), true);
        $this->assertEquals(array('Baz' => 'Bar'), $op->execute());
    }
}
