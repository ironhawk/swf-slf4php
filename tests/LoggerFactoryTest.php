<?php
use wwwind\testing\BehatPHPUnitBridge;

class LoggerFactoryTest extends PHPUnit_Framework_TestCase {

	public function __construct($name = null, array $data = [], $dataName = '') {
		parent::__construct($name, $data, $dataName);
	}

	public function testLoggerFactoryWithBehatScenarios() {
		BehatPHPUnitBridge::testWithBehat("BDD/LoggerFactory.feature");
	}

}