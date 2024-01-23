<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for DataContainer class
 */

namespace zeroline\MiniLoom\Tests\Event;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Event\Mediator;

class MediatorTest extends TestCase
{
    public function testMediatorSingleton()
    {
        $mediator = Mediator::getInstance();
        $this->assertInstanceOf(Mediator::class, $mediator);
    }
}