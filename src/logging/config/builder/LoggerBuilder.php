<?php

namespace cygnus\logging\config\builder;

use cygnus\logging\Logger;
use cygnus\util\JsonUtil;
use cygnus\logging\LoggerFactory;
use cygnus\errors\Preconditions;

class LoggerBuilder implements Builder {

	protected $name;

	protected $level = LoggerFactory::DEBUG;

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
	 * {@inheritDoc}
	 *
	 * @param array $builderContext
	 *        	It must has an entry named 'appenders' which is an associative array holding all available Appenders
	 *        	in an assoc array in format
	 *        	appenderName => Appender
	 * @return Logger
	 */
	public function build(array $builderContext = null) {
		Preconditions::checkArgument(! empty($builderContext) && isset($builderContext['appenders']), "missing 'appenders' entry from builderContext which should contain all available Appender object instances in array appenderName=>Appender format");
		$availableAppenders = $builderContext['appenders'];
		$appenders = [];
		foreach ($this->appenderNames as $appenderName) {
			Preconditions::checkArgument(array_key_exists($appenderName, $availableAppenders), "config error! Appender '{}' referenced but there is no Appender found with this name in Logger: {}", $appenderName, $this);
			$appenders[] = $availableAppenders[$appenderName];
		}
		Preconditions::checkState(! empty($appenders), "config error! There are no Appenders at all configured in LoggerBuilder: {}", $this);
		
		$logger = new Logger($this->name, $this->level, $appenders);
		return $logger;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \cygnus\logging\config\builder\Builder::initFromJson()
	 * @return \cygnus\logging\config\builder\LoggerBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		if (isset($jsonObj->name)) {
			$this->name(JsonUtil::getResolvedJsonStringValue($jsonObj->name, $envVars));
		}
		if (isset($jsonObj->level)) {
			$this->level(LogConfigBuilder::getAsLogLevel(JsonUtil::getResolvedJsonStringValue($jsonObj->level, $envVars)));
		}
		if (isset($jsonObj->appenders)) {
			if (is_array($jsonObj->appenders)) {
				$appenderArray = $jsonObj->appenders;
			} else {
				$appenderArray = [
					$jsonObj->appenders
				];
			}
			foreach ($appenderArray as $appenderName) {
				$this->appenderName($appenderName);
			}
		}
		return $this;
	}

}