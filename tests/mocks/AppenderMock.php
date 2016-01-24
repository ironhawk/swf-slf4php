<?php

namespace wwwind\logging\tests\mocks;

use wwwind\logging\Appender;

/**
 * Testable implementation of Appender
 *
 * @author ironhawk
 *        
 */
class AppenderMock extends Appender {

	private $messages = [];

	public function __construct($name) {
		parent::__construct($name);
	}

	public function debug($message, array $context = []) {
		$this->messages[] = $message;
	}

	public function critical($message, array $context = []) {
		$this->messages[] = $message;
	}

	public function alert($message, array $context = []) {
		$this->messages[] = $message;
	}

	public function log($level, $message, array $context = []) {
		$this->messages[] = $message;
	}

	public function emergency($message, array $context = []) {
		$this->messages[] = $message;
	}

	public function warning($message, array $context = []) {
		$this->messages[] = $message;
	}

	public function error($message, array $context = []) {
		$this->messages[] = $message;
	}

	public function notice($message, array $context = []) {
		$this->messages[] = $message;
	}

	public function info($message, array $context = []) {
		$this->messages[] = $message;
	}

}