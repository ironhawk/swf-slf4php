<?php

namespace wwwind\logging\config;

use wwwind\logging\Logger;
use wwwind\logging\Appender;

class LogConfig {

	/**
	 * Simple array of Logger objects<p>
	 * Name of logger objects are from the config - they are typically fully qualified class names or full or partial
	 * namespace strings
	 *
	 * @var array
	 */
	private $loggers;

	/**
	 * This is a Map in the following format: Appender.name => Appender
	 *
	 * @var array $appendersByName
	 */
	private $appendersByName;

	public function __construct(array $loggers = [], array $appendersByName = []) {
		$this->loggers = $loggers;
		$this->appendersByName = $appendersByName;
	}

	/**
	 *
	 * @param Logger $logger        	
	 * @return LogConfig
	 */
	public function logger(Logger $logger) {
		$this->loggers[] = $logger;
		return $this;
	}

	/**
	 *
	 * @param Appender $appender        	
	 * @return LogConfig
	 */
	public function appender(Appender $appender) {
		$this->appendersByName[$appender->getName()] = $appender;
		return $this;
	}

	public function getLoggers() {
		return $this->loggers;
	}

	public function getAppenders() {
		return $this->appendersByName;
	}

	/**
	 * Returns the Appender with the given name.
	 * If there is no Appender with that name NULL is returned
	 *
	 * @param string $name
	 *        	name of the Appender
	 * @return Appender
	 */
	public function getAppender($name) {
		if (array_key_exists($name, $this->appendersByName))
			return $this->appendersByName;
		return null;
	}

}