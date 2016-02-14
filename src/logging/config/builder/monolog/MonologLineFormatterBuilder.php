<?php

namespace wwwind\logging\config\builder\monolog;

use Monolog\Formatter\LineFormatter;
use wwwind\errors\Preconditions;
use wwwind\util\JsonUtil;

class MonologLineFormatterBuilder extends MonologFormatterBuilder {

	protected $lineFormatTemplate;

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return \wwwind\logging\config\builder\monolog\MonologLineFormatterBuilder
	 *
	 */
	public static function create() {
		return new MonologLineFormatterBuilder();
	}

	/**
	 *
	 * @param string $lineFormatTemplate        	
	 * @return \wwwind\logging\config\builder\monolog\MonologLineFormatterBuilder
	 */
	public function format($lineFormatTemplate) {
		$this->lineFormatTemplate = $lineFormatTemplate;
		return $this;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\monolog\MonologFormatterBuilder::build()
	 * @return \Monolog\Formatter\LineFormatter
	 */
	public function build() {
		return new LineFormatter($this->lineFormatTemplate);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\monolog\MonologFormatterBuilder::initFromJson()
	 * @return \wwwind\logging\config\builder\MonologLineFormatterBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		parent::initFromJson($jsonObj, $envVars);
		Preconditions::checkArgument(isset($jsonObj->format), "'format' mandatory attribute is not set on MonologLineFormatterBuilder type json object: {}", $jsonObj);
		$this->format(JsonUtil::getResolvedJsonStringValue($jsonObj->format, $envVars));
		return $this;
	}

}