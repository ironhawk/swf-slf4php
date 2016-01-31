<?php
use wwwind\testing\BehatPHPUnitBridge;

class LoggerTest extends PHPUnit_Framework_TestCase {

	public function __construct($name = null, array $data = [], $dataName = '') {
		parent::__construct($name, $data, $dataName);
	}

	public function testLoggerWithBehatScenarios() {
		BehatPHPUnitBridge::testWithBehat("BDD/Logger.feature");
	}

}