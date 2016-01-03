<?php

namespace wwwind\logging\config\builder\monolog;

use Monolog\Handler\StreamHandler;
use wwwind\errors\Preconditions;
use wwwind\util\JsonUtil;

class MonologStreamHandlerBuilder extends MonologHandlerBuilder {

	protected $stream;

	/**
	 *
	 * @return \wwwind\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public static function create() {
		return new MonologStreamHandlerBuilder();
	}

	/**
	 *
	 * @param string $stream        	
	 * @return \wwwind\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public function stream($stream) {
		$this->stream = $stream;
		return $this;
	}

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\monolog\MonologHandlerBuilder::build()
	 * @return \Monolog\Handler\StreamHandler
	 */
	public function build(array $builderContext = null) {
		$handler = new StreamHandler($this->stream);
		parent::injectSetup($handler);
		return $handler;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\monolog\MonologHandlerBuilder::initFromJson()
	 * @return \wwwind\logging\config\builder\MonologStreamHandlerBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		// let our parent init this instance from the json
		parent::initFromJson($jsonObj, $envVars);
		// and now let's add the extra
		Preconditions::checkArgument(isset($jsonObj->stream), "'stream' mandatory attribute is missing from MonologStreamHandlerBuilder type json object: {}", $jsonObj);
		$this->stream(JsonUtil::getResolvedJsonStringValue($jsonObj->stream, $envVars));
		return $this;
	}

}