<?php

namespace wwwind\logging\config\builder;

use wwwind\logging\Logger;
use wwwind\util\JsonUtil;
use wwwind\logging\LoggerFactory;
use wwwind\errors\Preconditions;
use wwwind\logging\config\LoggerTemplate;

class LoggerTemplateBuilder implements Builder {

	protected $name;

	protected $level = LoggerFactory::DEBUG;

	protected $appenderNames = [];

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return \wwwind\logging\config\builder\LoggerTemplateBuilder
	 */
	public static function create() {
		return new LoggerTemplateBuilder();
	}

	/**
	 *
	 * @return \wwwind\logging\config\builder\LoggerTemplateBuilder
	 */
	public function name($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 *
	 * @return \wwwind\logging\config\builder\LoggerTemplateBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @return \wwwind\logging\config\builder\LoggerTemplateBuilder
	 */
	public function appenderName($appenderName) {
		$this->appenderNames[] = $appenderName;
		return $this;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return LoggerTemplate
	 */
	public function build() {
		Preconditions::checkState(! empty($this->appenderNames), "config error! There are no Appenders at all configured in LoggerTemplateBuilder: {}", $this);
		
		$loggerTemplate = new LoggerTemplate($this->name, $this->level, $this->appenderNames);
		return $loggerTemplate;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \wwwind\logging\config\builder\Builder::initFromJson()
	 * @return \wwwind\logging\config\builder\LoggerTemplateBuilder
	 */
	public function initFromJson($jsonObj, $envVars) {
		if (isset($jsonObj->name)) {
			$this->name(JsonUtil::getResolvedJsonStringValue($jsonObj->name, $envVars));
		}
		if (isset($jsonObj->level)) {
			$this->level(LogConfigBuilder::getAsLogLevel(JsonUtil::getResolvedJsonStringValue($jsonObj->level, $envVars)));
		}
		if (isset($jsonObj->appenders)) {
			if (is_array($jsonObj->appenders)) {
				$appenderArray = $jsonObj->appenders;
			} else {
				$appenderArray = [
					$jsonObj->appenders
				];
			}
			foreach ($appenderArray as $appenderName) {
				$this->appenderName($appenderName);
			}
		}
		return $this;
	}

}