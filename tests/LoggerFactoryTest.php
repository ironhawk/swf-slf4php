<?php
use swf\testing\BehatPHPUnitBridge;
use swf\lf4php\LoggerFactory;

class LoggerFactoryTest extends PHPUnit_Framework_TestCase {

	public function __construct($name = null, array $data = [], $dataName = '') {
		parent::__construct($name, $data, $dataName);
	}

	public function testNotConfiguredLoggerFactoryShouldAlwaysReturnNullLogger() {
		$actualLogger = LoggerFactory::getLogger("MyClass");
		
		$loggerFactoryReflectionProp = new \ReflectionProperty("swf\\lf4php\\LoggerFactory", "nullLogger");
		$loggerFactoryReflectionProp->setAccessible(true);
		$expectedLogger = $loggerFactoryReflectionProp->getValue();
		
		\PHPUnit_Framework_TestCase::assertEquals($expectedLogger, $actualLogger, "The returned Logger is not the special NullLogger instance");
	}

	public function testLoggerFactoryWithBehatScenarios() {
		BehatPHPUnitBridge::testWithBehat("BDD/LoggerFactory.feature");
	}

}