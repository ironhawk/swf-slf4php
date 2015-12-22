<?php

namespace cygnus\logging\config\builder\monolog;

use Monolog\Handler\StreamHandler;
use cygnus\errors\Preconditions;
use cygnus\util\JsonUtil;

class MonologStreamHandlerBuilder extends MonologHandlerBuilder {

	protected $stream;

	/**
	 *
	 * @return \cygnus\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public static function create() {
		return new MonologStreamHandlerBuilder();
	}

	/**
	 *
	 * @param string $stream        	
	 * @return \cygnus\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public function stream($stream) {
		$this->stream = $stream;
		return $this;
	}

	
	/**
	 *
	 * @return \Monolog\Handler\StreamHandler
	 */
	public function build() {
		$handler = new StreamHandler($this->stream);
		parent::injectSetup($handler);
		return $handler;
	}

	/**
	 *
	 * @return \Monolog\Handler\StreamHandler
	 */
	public function buildFromJson($jsonObj, $envVars) {
		// let our parent init this instance from the json
		parent::initFromJson($jsonObj, $envVars);
		// and now let's add the extra
		Preconditions::checkArgument(isset($jsonObj->stream), "'stream' mandatory attribute is missing from MonologStreamHandlerBuilder type json object: {}", $jsonObj);
		$this->stream(JsonUtil::getResolvedJsonStringValue($jsonObj->stream, $envVars));
		return $this->build();
	}

}