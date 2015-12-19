<?php

namespace cygnus\logging\config\builder;

use cygnus\logging\config\LogConfig;

class LogConfigBuilder {

	private $loggerBuilders;

	private $appenderBuilders;

	/**
	 *
	 * @return \cygnus\logging\config\builder\LogConfigBuilder
	 */
	public static function create() {
		return new LogConfigBuilder();
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
	public function logger($loggerBuilder) {
		$this->loggerBuilders[] = $loggerBuilder;
		return $this;
	}

	/**
	 *
	 * @param AppenderBuilder $appenderBuilder        	
	 * @return LogConfigBuilder
	 */
	public function appender($appenderBuilder) {
		$this->appenderBuilders[] = $appenderBuilder;
		return $this;
	}

	
	/**
	 *
	 * @return LogConfig
	 */
	public function build() {
		$appenders = [];
		foreach ($this->appenderBuilders as $appenderBuilder) {
			$appender = $appenderBuilder->build();
			$appenders[$appender->getName()] = $appender;
		}
		
		$loggers = [];
		foreach ($this->loggerBuilders as $loggerBuilder) {
			$logger = $loggerBuilder->build($appenders);
			$loggers[] = $logger;
		}
		
		return new LogConfig($loggers, $appenders);
	}

}