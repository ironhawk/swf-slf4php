<?php

namespace swf\lf4php\config\builder\monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use swf\lf4php\config\builder\Builder;
use swf\util\JsonUtil;
use swf\lf4php\config\builder\LogConfigBuilder;
use swf\errors\Preconditions;
use Monolog\Formatter\FormatterInterface;

abstract class MonologHandlerBuilder implements Builder {

	/**
	 *
	 * @var FormatterInterface
	 */
	protected $formatter;

	protected $bubble = true;

	protected $level = Logger::DEBUG;

	/**
	 *
	 * @param FormatterInterface $builder        	
	 * @return \swf\lf4php\config\builder\monolog\MonologHandlerBuilder
	 */
	public function formatter(FormatterInterface $formatter) {
		$this->formatter = $formatter;
		return $this;
	}

	/**
	 *
	 * @param int $level        	
	 * @return \swf\lf4php\config\builder\monolog\MonologHandlerBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @param boolean $bubble        	
	 * @return \swf\lf4php\config\builder\monolog\MonologHandlerBuilder
	 */
	public function bubble($bubble) {
		$this->bubble = $bubble;
		return $this;
	}

	/**
	 * Helper method for subclasses - although all attributes are protected (therefore visible)
	 * it is easier to simply invoke this inject method to add them all to concrete handlers.
	 *
	 * @param AbstractProcessingHandler $handler        	
	 */
	protected function injectSetup(AbstractProcessingHandler $handler) {
		$handler->setBubble($this->bubble);
		$handler->setLevel($this->level);
		if (! is_null($this->formatter)) {
			$handler->setFormatter($this->formatter);
		}
	}

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\Builder::build()
	 * @return \swf\lf4php\config\builder\Appender
	 */
	public abstract function build();

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\Builder::initFromJson()
	 * @return \swf\lf4php\config\builder\MonologHandlerBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		if (isset($jsonObj->bubble)) {
			$this->bubble(JsonUtil::getAsBoolValue($jsonObj->bubble, $envVars));
		}
		if (isset($jsonObj->level)) {
			$this->level(LogConfigBuilder::getAsLogLevel(JsonUtil::getResolvedJsonStringValue($jsonObj->level, $envVars)));
		}
		if (isset($jsonObj->formatter)) {
			$formatterJsonObj = $jsonObj->formatter;
			Preconditions::checkArgument(isset($formatterJsonObj->builderClass), "'builderClass' attribute is missing from Monolog Formatter json object: {}", $formatterJsonObj);
			
			$reflection = new \ReflectionClass($formatterJsonObj->builderClass);
			Preconditions::checkArgument($reflection->implementsInterface("\swf\lf4php\config\builder\Builder"), "'builderClass' {} doesn't implement \\swf\\logging\\config\\builder\\Builder interface in appender def: {}", $formatterJsonObj->builderClass, $formatterJsonObj);
			$formatterBuilder = $reflection->newInstance();
			$formatterBuilder->initFromJson($formatterJsonObj, $envVars);
			$this->formatter($formatterBuilder->build());
		}
		return $this;
	}

}