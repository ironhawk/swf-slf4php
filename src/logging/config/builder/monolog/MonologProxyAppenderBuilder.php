<?php

namespace cygnus\logging\config\builder\monolog;

use cygnus\logging\monolog\MonologProxyAppender;
use cygnus\logging\config\builder\AppenderBuilder;
use cygnus\errors\Preconditions;

class MonologProxyAppenderBuilder extends AppenderBuilder {

	protected $handlerBuilders = [];

	/**
	 *
	 * @return \cygnus\logging\config\builder\monolog\MonologProxyAppenderBuilder
	 */
	public static function create() {
		return new MonologProxyAppenderBuilder();
	}

	/**
	 *
	 * @return \cygnus\logging\config\builder\monolog\MonologProxyAppenderBuilder
	 */
	public function handlerBuilder($handlerBuilder) {
		$this->handlerBuilders[] = $handlerBuilder;
		return $this;
	}

	/**
	 *
	 * @return \cygnus\logging\monolog\MonologProxyAppender
	 */
	public function build() {
		$handlers = [];
		foreach ($this->handlerBuilders as $handlerBuilder) {
			$handlers[] = $handlerBuilder->build();
		}
		$appender = new MonologProxyAppender($this->name, $handlers);
		return $appender;
	}

	/**
	 *
	 * @return \cygnus\logging\monolog\MonologProxyAppender
	 */
	public function buildFromJson($jsonObj, $envVars) {
		Preconditions::checkArgument(isset($jsonObj->name), "'name' attribute is missing from Appender json object: {}", $jsonObj);
		$this->name($jsonObj->name);
		if (isset($jsonObj->handlers)) {
			foreach ($jsonObj->handlers as $handlerJsonObj) {
				// let's call the static create method which all builders have
				$handlerBuilder = call_user_func_array(array(
					$handlerJsonObj->type,
					'create'
				), []);
				$handlerBuilder->buildFromJson($handlerJsonObj, $envVars);
				$this->handlerBuilder($handlerBuilder);
			}
		}
		return $this->build();
	}

}