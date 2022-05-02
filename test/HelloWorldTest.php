<?php

namespace Test\RafaelCardoso\PhpWithGuzzle;

use RafaelCardoso\PhpWithGuzzle\HelloWorld;
use PHPUnit\Framework\TestCase;

class HelloWorldTest extends TestCase
{
    public function testHelloWorld(): void {
        $client = new HelloWorld();

        $this->assertSame('Hello World!', $client->getHelloWorld());
    }

}
