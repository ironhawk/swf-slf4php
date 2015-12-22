<?php

namespace cygnus\logging\config\builder\monolog;

use Monolog\Formatter\LineFormatter;
use cygnus\errors\Preconditions;
use cygnus\util\JsonUtil;

class MonologLineFormatterBuilder extends MonologFormatterBuilder {

	protected $lineFormatTemplate;

	/**
	 *
	 * @return \cygnus\logging\config\builder\monolog\MonologLineFormatterBuilder
	 *
	 */
	public static function create() {
		return new MonologLineFormatterBuilder();
	}

	/**
	 *
	 * @param string $lineFormatTemplate        	
	 * @return \cygnus\logging\config\builder\monolog\MonologLineFormatterBuilder
	 */
	public function format($lineFormatTemplate) {
		$this->lineFormatTemplate = $lineFormatTemplate;
		return $this;
	}

	/**
	 *
	 * @return \Monolog\Formatter\LineFormatter
	 */
	public function build($builderContext = null) {
		return new LineFormatter($this->lineFormatTemplate);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \cygnus\logging\config\builder\monolog\MonologFormatterBuilder::initFromJson()
	 * @return \cygnus\logging\config\builder\MonologLineFormatterBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		parent::initFromJson($jsonObj, $envVars);
		Preconditions::checkArgument(isset($jsonObj->format), "'format' mandatory attribute is not set on MonologLineFormatterBuilder type json object: {}", $jsonObj);
		$this->format(JsonUtil::getResolvedJsonStringValue($jsonObj->format, $envVars));
		return $this;
	}

}