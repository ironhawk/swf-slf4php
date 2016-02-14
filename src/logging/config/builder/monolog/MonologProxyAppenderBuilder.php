<?php

namespace wwwind\logging\config\builder\monolog;

use wwwind\logging\monolog\MonologProxyAppender;
use wwwind\logging\config\builder\AppenderBuilder;
use wwwind\errors\Preconditions;
use Monolog\Handler\AbstractProcessingHandler;
use wwwind\logging\config\builder\Builder;

class MonologProxyAppenderBuilder extends AppenderBuilder {

	protected $handlers = [];

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return \wwwind\logging\config\builder\monolog\MonologProxyAppenderBuilder
	 */
	public static function create() {
		return new MonologProxyAppenderBuilder();
	}

	/**
	 *
	 * @param AbstractProcessingHandler $handler        	
	 * @return \wwwind\logging\config\builder\monolog\MonologProxyAppenderBuilder
	 */
	public function handler($handler) {
		$this->handlers[] = $handler;
		return $this;
	}

	/**
	 *
	 * @return \wwwind\logging\monolog\MonologProxyAppender
	 */
	public function build() {
		$appender = new MonologProxyAppender($this->name, $this->handlers);
		return $appender;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\AppenderBuilder::initFromJson()
	 * @return \wwwind\logging\config\builder\MonologProxyAppenderBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		Preconditions::checkArgument(isset($jsonObj->name), "'name' attribute is missing from Appender json object: {}", $jsonObj);
		$this->name($jsonObj->name);
		if (isset($jsonObj->handlers)) {
			foreach ($jsonObj->handlers as $handlerJsonObj) {
				
				$reflection = new \ReflectionClass($handlerJsonObj->builderClass);
				Preconditions::checkArgument($reflection->implementsInterface("\wwwind\logging\config\builder\Builder"), "'builderClass' {} doesn't implement \\wwwind\\logging\\config\\builder\\Builder interface in appender def: {}", $handlerJsonObj->builderClass, $handlerJsonObj);
				$handlerBuilder = $reflection->newInstance();
				$handlerBuilder->initFromJson($handlerJsonObj, $envVars);
				$this->handler($handlerBuilder->build());
			}
		}
		return $this;
	}

}