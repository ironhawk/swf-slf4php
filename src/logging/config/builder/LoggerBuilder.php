<?php

namespace cygnus\logging\config\builder;

use cygnus\logging\Logger;

class LoggerBuilder implements Builder {

	protected $name;

	protected $level;

	protected $appenderNames;

	
	/**
	 *
	 * @return \cygnus\logging\config\builder\LoggerBuilder
	 */
	public static function create() {
		return new LoggerBuilder();
	}

	public function __construct() {
		$this->appenderNames = [];
	}

	/**
	 *
	 * @return \cygnus\logging\config\builder\LoggerBuilder
	 */
	public function name($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 *
	 * @return \cygnus\logging\config\builder\LoggerBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @return \cygnus\logging\config\builder\LoggerBuilder
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

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \cygnus\logging\config\builder\Builder::buildFromJson()
	 */
	public function buildFromJson($jsonObj, $envVars) {
		// TODO: Auto-generated method stub
	}

}