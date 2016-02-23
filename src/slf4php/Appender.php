<?php

namespace swf\slf4php;

use Psr\Log\LoggerInterface;

/**
 * Appenders are responsible for streaming the log messages to different outputs like files, streams, db tables, etc.
 *
 * @author ironhawk
 *        
 */
abstract class Appender implements LoggerInterface {

	private $name;

	public function __construct($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

}