<?php

namespace wwwind\logging\config\builder\monolog;

use wwwind\logging\monolog\MonologProxyAppender;
use wwwind\logging\config\builder\AppenderBuilder;
use wwwind\errors\Preconditions;

class MonologProxyAppenderBuilder extends AppenderBuilder {

	protected $handlerBuilders = [];

	/**
	 *
	 * @return \wwwind\logging\config\builder\monolog\MonologProxyAppenderBuilder
	 */
	public static function create() {
		return new MonologProxyAppenderBuilder();
	}

	/**
	 *
	 * @return \wwwind\logging\config\builder\monolog\MonologProxyAppenderBuilder
	 */
	public function handlerBuilder($handlerBuilder) {
		$this->handlerBuilders[] = $handlerBuilder;
		return $this;
	}

	/**
	 *
	 * @return \wwwind\logging\monolog\MonologProxyAppender
	 */
	public function build(array $builderContext = null) {
		$handlers = [];
		foreach ($this->handlerBuilders as $handlerBuilder) {
			$handlers[] = $handlerBuilder->build();
		}
		$appender = new MonologProxyAppender($this->name, $handlers);
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
				// let's call the static create method which all builders have
				$handlerBuilder = call_user_func_array(array(
					$handlerJsonObj->builderClass,
					'create'
				), []);
				$handlerBuilder->initFromJson($handlerJsonObj, $envVars);
				$this->handlerBuilder($handlerBuilder);
			}
		}
		return $this;
	}

}