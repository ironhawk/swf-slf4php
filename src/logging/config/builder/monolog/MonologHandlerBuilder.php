<?php

namespace cygnus\logging\config\builder\monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use cygnus\logging\config\builder\Builder;
use cygnus\util\JsonUtil;
use cygnus\logging\config\builder\LogConfigBuilder;
use cygnus\errors\Preconditions;

abstract class MonologHandlerBuilder implements Builder {

	protected $formatterBuilder;

	protected $bubble = true;

	protected $level = Logger::DEBUG;

	/**
	 *
	 * @param MonologFormatterBuilder $builder        	
	 * @return \cygnus\logging\config\builder\monolog\MonologHandlerBuilder
	 */
	public function formatterBuilder(MonologFormatterBuilder $builder) {
		$this->formatterBuilder = $builder;
		return $this;
	}

	/**
	 *
	 * @param int $level        	
	 * @return \cygnus\logging\config\builder\monolog\MonologHandlerBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @param boolean $bubble        	
	 * @return \cygnus\logging\config\builder\monolog\MonologHandlerBuilder
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
	 * @return \cygnus\logging\config\builder\Appender
	 */
	public abstract function build();

	/**
	 * Helper method for subclasses ment to be part of buildFromJson().
	 * This method is initializing instance from json
	 */
	protected function initFromJson($jsonObj, $envVars) {
		if (isset($jsonObj->bubble)) {
			$this->bubble(JsonUtil::getAsBoolValue($jsonObj->bubble, $envVars));
		}
		if (isset($jsonObj->level)) {
			$this->level(LogConfigBuilder::getAsLogLevel(JsonUtil::getResolvedJsonStringValue($jsonObj->level, $envVars)));
		}
		if (isset($jsonObj->formatter)) {
			$formatterJsonObj = $jsonObj->formatter;
			Preconditions::checkArgument(isset($formatterJsonObj->type), "'type' attribute is missing from Monolog Formatter json object: {}", $formatterJsonObj);
			// let's call the static create method which all builders have
			$formatterBuilder = call_user_func_array(array(
				$formatterJsonObj->type,
				'create'
			), []);
			$formatterBuilder->buildFromJson($formatterJsonObj, $envVars);
			$this->formatterBuilder($formatterBuilder);
		}
	}

	/**
	 *
	 * @return \cygnus\logging\config\builder\Appender
	 */
	public abstract function buildFromJson($jsonObj, $envVars);

}