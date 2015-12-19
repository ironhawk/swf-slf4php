<?php

namespace cygnus\phpbasic\lib\logging\config\builder;

use cygnus\phpbasic\lib\logging\Logger;

class LoggerBuilder {

	private $name;

	private $level;

	private $appenderNames;

	
	/**
	 *
	 * @return \cygnus\phpbasic\lib\logging\config\builder\LoggerBuilder
	 */
	public static function create() {
		return new LoggerBuilder();
	}

	public function __construct() {
		$this->appenderNames = [];
	}

	/**
	 *
	 * @return \cygnus\phpbasic\lib\logging\config\builder\LoggerBuilder
	 */
	public function name($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 *
	 * @return \cygnus\phpbasic\lib\logging\config\builder\LoggerBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @return \cygnus\phpbasic\lib\logging\config\builder\LoggerBuilder
	 */
	public function appenderName($appenderName) {
		$this->appenderNames[] = $appenderName;
		return $this;
	}

	/**
	 *
	 * @param
	 *        	array array of all available Appenders in appenderName => Appender format
	 * @return Logger
	 */
	public function build(array $availableAppenders) {
		$appenders = [];
		foreach ($this->appenderNames as $appenderName) {
			if (array_key_exists($appenderName, $availableAppenders))
				$appenders[] = $availableAppenders[$appenderName];
		}
		$logger = new Logger($this->name, $this->level, $appenders);
		return $logger;
	}

}