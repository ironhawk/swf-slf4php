<?php

namespace swf\lf4php\config\builder;

use swf\lf4php\config\LogConfig;
use swf\errors\Preconditions;
use swf\lf4php\LoggerFactory;
use swf\lf4php\config\LoggerTemplate;

class LogConfigBuilder implements Builder {

	private $loggers = [];

	private $appenders = [];

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return \swf\lf4php\config\builder\LogConfigBuilder
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

	/**
	 * Adding a LoggerTemplate
	 *
	 * @param LoggerTemplate $loggerTemplate        	
	 * @return LogConfigBuilder
	 */
	public function logger($loggerTemplate) {
		$this->loggers[] = $loggerTemplate;
		return $this;
	}

	/**
	 * Adding an Appender
	 *
	 * @param Appender $appender        	
	 * @return LogConfigBuilder
	 */
	public function appender($appender) {
		$this->appenders[] = $appender;
		return $this;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\Builder::build()
	 * @return LogConfig
	 */
	public function build() {
		Preconditions::checkState(! empty($this->appenders), "config error! There are no configured Appenders at all!");
		
		return new LogConfig($this->loggers, $this->appenders);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\Builder::initFromJson()
	 * @return \swf\lf4php\config\builder\LogConfigBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		if (isset($jsonObj->appenders)) {
			foreach ($jsonObj->appenders as $appenderJsonObj) {
				Preconditions::checkArgument(isset($appenderJsonObj->builderClass), "'builderClass' attribute is missing from appender: {}", $appenderJsonObj);
				// let's create builder instance
				$reflection = new \ReflectionClass($appenderJsonObj->builderClass);
				Preconditions::checkArgument($reflection->implementsInterface("\swf\lf4php\config\builder\Builder"), "'builderClass' {} doesn't implement \\swf\\logging\\config\\builder\\Builder interface in appender def: {}", $appenderJsonObj->builderClass, $appenderJsonObj);
				$appenderBuilder = $reflection->newInstance();
				$appenderBuilder->initFromJson($appenderJsonObj, $envVars);
				$this->appender($appenderBuilder->build());
			}
		}
		if (isset($jsonObj->loggers)) {
			foreach ($jsonObj->loggers as $loggerJsonObj) {
				$loggerTemplateBuilder = new LoggerTemplateBuilder();
				$loggerTemplateBuilder->initFromJson($loggerJsonObj, $envVars);
				$this->logger($loggerTemplateBuilder->build());
			}
		}
		return $this;
	}

}