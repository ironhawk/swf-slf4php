<?php

namespace swf\lf4php\config\builder\monolog;

use Monolog\Formatter\LineFormatter;
use swf\errors\Preconditions;
use swf\util\JsonUtil;

class MonologLineFormatterBuilder extends MonologFormatterBuilder {

	protected $lineFormatTemplate;

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return \swf\lf4php\config\builder\monolog\MonologLineFormatterBuilder
	 *
	 */
	public static function create() {
		return new MonologLineFormatterBuilder();
	}

	/**
	 *
	 * @param string $lineFormatTemplate        	
	 * @return \swf\lf4php\config\builder\monolog\MonologLineFormatterBuilder
	 */
	public function format($lineFormatTemplate) {
		$this->lineFormatTemplate = $lineFormatTemplate;
		return $this;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\monolog\MonologFormatterBuilder::build()
	 * @return \Monolog\Formatter\LineFormatter
	 */
	public function build() {
		return new LineFormatter($this->lineFormatTemplate);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \swf\lf4php\config\builder\monolog\MonologFormatterBuilder::initFromJson()
	 * @return \swf\lf4php\config\builder\MonologLineFormatterBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		parent::initFromJson($jsonObj, $envVars);
		Preconditions::checkArgument(isset($jsonObj->format), "'format' mandatory attribute is not set on MonologLineFormatterBuilder type json object: {}", $jsonObj);
		$this->format(JsonUtil::getResolvedJsonStringValue($jsonObj->format, $envVars));
		return $this;
	}

}