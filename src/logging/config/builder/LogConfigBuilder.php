<?php

namespace cygnus\logging\config\builder;

use cygnus\logging\config\LogConfig;
use cygnus\errors\Preconditions;
use cygnus\logging\LoggerFactory;

class LogConfigBuilder implements Builder {

	private $loggerBuilders;

	private $appenderBuilders;

	/**
	 *
	 * @return \cygnus\logging\config\builder\LogConfigBuilder
	 */
	public static function create() {
		return new LogConfigBuilder();
	}

	
	/**
	 * Given a log level with string representation like DEBUG, INFO, etc.
	 * This gives back the numeric representation
	 *
	 * @param string $logLevelStr        	
	 * @return int
	 * @throws IllegalArgumentException
	 */
	public static function getAsLogLevel($logLevelStr) {
		Preconditions::checkArgument(! empty($logLevelStr), "you have provided an empty or NULL log level string");
		$key = strtoupper($logLevelStr);
		Preconditions::checkArgument(isset(LoggerFactory::$names2levels[$key]), "unknown log level: {}", $logLevelStr);
		return LoggerFactory::$names2levels[$key];
	}

	public function __construct() {
		$this->loggerBuilders = [];
		$this->appenderBuilders = [];
	}

	/**
	 *
	 * @param LoggerBuilder $loggerBuilder        	
	 * @return LogConfigBuilder
	 */
	public function loggerBuilder($loggerBuilder) {
		$this->loggerBuilders[] = $loggerBuilder;
		return $this;
	}

	/**
	 *
	 * @param AppenderBuilder $appenderBuilder        	
	 * @return LogConfigBuilder
	 */
	public function appenderBuilder($appenderBuilder) {
		$this->appenderBuilders[] = $appenderBuilder;
		return $this;
	}

	
	/**
	 *
	 * @return LogConfig
	 */
	public function build($builderContext = null) {
		$appenders = [];
		foreach ($this->appenderBuilders as $appenderBuilder) {
			$appender = $appenderBuilder->build();
			$appenders[$appender->getName()] = $appender;
		}
		
		$loggers = [];
		foreach ($this->loggerBuilders as $loggerBuilder) {
			$logger = $loggerBuilder->build([
				'appenders' => $appenders
			]);
			$loggers[] = $logger;
		}
		
		return new LogConfig($loggers, $appenders);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \cygnus\logging\config\builder\Builder::initFromJson()
	 * @return \cygnus\logging\config\builder\LogConfigBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		if (isset($jsonObj->appenders)) {
			foreach ($jsonObj->appenders as $appenderJsonObj) {
				Preconditions::checkArgument(isset($appenderJsonObj->type), "'type' attribute is missing from appender: {}", $appenderJsonObj);
				// let's call the static create method which all builders have
				$appenderBuilder = call_user_func_array(array(
					$appenderJsonObj->type,
					'create'
				), []);
				$appenderBuilder->initFromJson($appenderJsonObj, $envVars);
				$this->appenderBuilder($appenderBuilder);
			}
		}
		if (isset($jsonObj->loggers)) {
			foreach ($jsonObj->loggers as $loggerJsonObj) {
				$loggerBuilder = LoggerBuilder::create();
				$loggerBuilder->initFromJson($loggerJsonObj, $envVars);
				$this->loggerBuilder($loggerBuilder);
			}
		}
		return $this;
	}

}