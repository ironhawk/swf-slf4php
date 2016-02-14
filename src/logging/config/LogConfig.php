<?php

namespace wwwind\logging\config;

use wwwind\logging\Logger;
use wwwind\logging\Appender;
use wwwind\logging\config\builder\LogConfigBuilder;

/**
 * An immutable object holding the log configuration<p>
 * To build up a LogConfig in more convinient way you might use LogConfigBuilder.
 *
 * @immutable
 *
 * @author ironhawk
 * @see LogConfigBuilder
 *
 */
class LogConfig {

	/**
	 * This is a Map in the following format: Logger.name => Logger<p>
	 * Name of logger objects are from the config - they are typically fully qualified class names or full or partial
	 * namespace strings
	 *
	 * @var array
	 */
	private $loggerTemplatesByNamespacePath;

	/**
	 * This is a Map in the following format: Appender.name => Appender
	 *
	 * @param array $appenders
	 *        	list of Appenders should be added to this config
	 * @param array $logg        	
	 */
	private $appendersByName;

	public function __construct(array $loggerTemplates = [], array $appenders = []) {
		$this->loggerTemplatesByNamespacePath = [];
		$this->appendersByName = [];
		foreach ($loggerTemplates as $loggerTemplate) {
			$namespacePath = $loggerTemplate->getNamespacePath();
			if (empty($namespacePath))
				$namespacePath = 'null';
			else
				$namespacePath = implode('.', $loggerTemplate->getNamespacePath());
			$this->loggerTemplatesByNamespacePath[$namespacePath] = $loggerTemplate;
		}
		foreach ($appenders as $appender) {
			$this->appendersByName[$appender->getName()] = $appender;
		}
	}

	public function getLoggerTemplates() {
		return $this->loggerTemplatesByNamespacePath;
	}

	/**
	 * Returns the LoggerTemplate with the given namespacePath.
	 * If there is no LoggerTemplate with that name NULL is returned
	 *
	 * @param string $namespacePath
	 *        	name of the LoggerTemplate
	 * @return LoggerTemplate
	 */
	public function getLoggerTemplate($namespacePath) {
		$namespacePath = LoggerTemplate::splitNamespacePath($namespacePath);
		$namespacePath = empty($namespacePath) ? 'null' : implode('.', $namespacePath);
		if (array_key_exists($namespacePath, $this->loggerTemplatesByNamespacePath))
			return $this->loggerTemplatesByNamespacePath[$namespacePath];
		return null;
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
			return $this->appendersByName[$name];
		return null;
	}

}