<?php

namespace zeroline\MiniLoom\Tests\Moduling;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Moduling\ModuleManager;
use zeroline\MiniLoom\Moduling\IModule;

class ModuleManagerTest extends TestCase
{
    public function testRegisterAndGetModule()
    {
        $moduleMock = $this->createMock(IModule::class);
        $moduleMock->method('getModuleName')->willReturn('TestModule');

        ModuleManager::registerModule($moduleMock);

        $module = ModuleManager::getModule('TestModule');
        $this->assertSame($moduleMock, $module);
    }

    public function testGetModules()
    {
        $moduleMock1 = $this->createMock(IModule::class);
        $moduleMock1->method('getModuleName')->willReturn('TestModule1');

        $moduleMock2 = $this->createMock(IModule::class);
        $moduleMock2->method('getModuleName')->willReturn('TestModule2');

        ModuleManager::registerModule($moduleMock1);
        ModuleManager::registerModule($moduleMock2);

        $modules = ModuleManager::getModules();
        $this->assertContains($moduleMock1, $modules);
        $this->assertContains($moduleMock2, $modules);
    }
}