<?php

namespace wwwind\logging\config\builder\monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use wwwind\logging\config\builder\Builder;
use wwwind\util\JsonUtil;
use wwwind\logging\config\builder\LogConfigBuilder;
use wwwind\errors\Preconditions;

abstract class MonologHandlerBuilder implements Builder {

	protected $formatterBuilder;

	protected $bubble = true;

	protected $level = Logger::DEBUG;

	/**
	 *
	 * @param MonologFormatterBuilder $builder        	
	 * @return \wwwind\logging\config\builder\monolog\MonologHandlerBuilder
	 */
	public function formatterBuilder(MonologFormatterBuilder $builder) {
		$this->formatterBuilder = $builder;
		return $this;
	}

	/**
	 *
	 * @param int $level        	
	 * @return \wwwind\logging\config\builder\monolog\MonologHandlerBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @param boolean $bubble        	
	 * @return \wwwind\logging\config\builder\monolog\MonologHandlerBuilder
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
		if (! is_null($this->formatterBuilder)) {
			$handler->setFormatter($this->formatterBuilder->build());
		}
	}

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\Builder::build()
	 * @return \wwwind\logging\config\builder\Appender
	 */
	public abstract function build(array $builderContext = null);

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\Builder::initFromJson()
	 * @return \wwwind\logging\config\builder\MonologHandlerBuilder
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
			// let's call the static create method which all builders have
			$formatterBuilder = call_user_func_array(array(
				$formatterJsonObj->builderClass,
				'create'
			), []);
			$formatterBuilder->initFromJson($formatterJsonObj, $envVars);
			$this->formatterBuilder($formatterBuilder);
		}
		return $this;
	}

}