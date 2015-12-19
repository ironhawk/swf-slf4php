<?php

namespace cygnus\logging\config\builder;

use cygnus\logging\Logger;

class RootLoggerBuilder {

	protected $level;

	protected $appenderNames;

	
	/**
	 *
	 * @return \cygnus\logging\config\builder\RootLoggerBuilder
	 */
	public static function create() {
		return new RootLoggerBuilder();
	}

	public function __construct() {
		$this->appenderNames = [];
	}

	
	/**
	 *
	 * @return \cygnus\logging\config\builder\RootLoggerBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @return \cygnus\logging\config\builder\RootLoggerBuilder
	 */
	public function appenderName($appenderName) {
		$this->appenderNames[] = $appenderName;
		return $this;
	}

	protected function buildAppendersArray() {
		$appenders = [];
		foreach ($this->appenderNames as $appenderName) {
			if (array_key_exists($appenderName, $availableAppenders))
				$appenders[] = $availableAppenders[$appenderName];
		}
		return $appenders;
	}

	/**
	 *
	 * @param
	 *        	array array of all available Appenders in appenderName => Appender format
	 * @return Logger
	 */
	public function build(array $availableAppenders) {
		$logger = new Logger($this->name, $this->level, $this->buildAppendersArray());
		return $logger;
	}

}