<?php

namespace swf\lf4php\config\builder\monolog;

use Monolog\Handler\StreamHandler;
use swf\errors\Preconditions;
use swf\util\JsonUtil;

class MonologStreamHandlerBuilder extends MonologHandlerBuilder {

	protected $stream;

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return \swf\lf4php\config\builder\monolog\MonologStreamHandlerBuilder
	 *
	 */
	public static function create() {
		return new MonologStreamHandlerBuilder();
	}

	
	/**
	 *
	 * @param string $stream        	
	 * @return \swf\lf4php\config\builder\MonologStreamHandlerBuilder
	 */
	public function stream($stream) {
		$this->stream = $stream;
		return $this;
	}

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\monolog\MonologHandlerBuilder::build()
	 * @return \Monolog\Handler\StreamHandler
	 */
	public function build() {
		$handler = new StreamHandler($this->stream);
		parent::injectSetup($handler);
		return $handler;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\monolog\MonologHandlerBuilder::initFromJson()
	 * @return \swf\lf4php\config\builder\MonologStreamHandlerBuilder
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