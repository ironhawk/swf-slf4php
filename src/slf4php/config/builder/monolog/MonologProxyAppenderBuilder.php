<?php

namespace swf\slf4php\config\builder\monolog;

use swf\slf4php\monolog\MonologProxyAppender;
use swf\slf4php\config\builder\AppenderBuilder;
use swf\errors\Preconditions;
use Monolog\Handler\AbstractProcessingHandler;
use swf\slf4php\config\builder\Builder;

class MonologProxyAppenderBuilder extends AppenderBuilder {

	protected $handlers = [];

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return \swf\slf4php\config\builder\monolog\MonologProxyAppenderBuilder
	 */
	public static function create() {
		return new MonologProxyAppenderBuilder();
	}

	/**
	 *
	 * @param AbstractProcessingHandler $handler        	
	 * @return \swf\slf4php\config\builder\monolog\MonologProxyAppenderBuilder
	 */
	public function handler($handler) {
		$this->handlers[] = $handler;
		return $this;
	}

	/**
	 *
	 * @return \swf\slf4php\monolog\MonologProxyAppender
	 */
	public function build() {
		$appender = new MonologProxyAppender($this->name, $this->handlers);
		return $appender;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\slf4php\config\builder\AppenderBuilder::initFromJson()
	 * @return \swf\slf4php\config\builder\MonologProxyAppenderBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		Preconditions::checkArgument(isset($jsonObj->name), "'name' attribute is missing from Appender json object: {}", $jsonObj);
		$this->name($jsonObj->name);
		if (isset($jsonObj->handlers)) {
			foreach ($jsonObj->handlers as $handlerJsonObj) {
				
				$reflection = new \ReflectionClass($handlerJsonObj->builderClass);
				Preconditions::checkArgument($reflection->implementsInterface("\swf\slf4php\config\builder\Builder"), "'builderClass' {} doesn't implement \\swf\\logging\\config\\builder\\Builder interface in appender def: {}", $handlerJsonObj->builderClass, $handlerJsonObj);
				$handlerBuilder = $reflection->newInstance();
				$handlerBuilder->initFromJson($handlerJsonObj, $envVars);
				$this->handler($handlerBuilder->build());
			}
		}
		return $this;
	}

}