<?php

namespace swf\slf4php\config\builder;

use swf\slf4php\Logger;
use swf\util\JsonUtil;
use swf\slf4php\LoggerFactory;
use swf\errors\Preconditions;
use swf\slf4php\config\LoggerTemplate;

class LoggerTemplateBuilder implements Builder {

	protected $name;

	protected $level = LoggerFactory::DEBUG;

	protected $appenderNames = [];

	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @return \swf\slf4php\config\builder\LoggerTemplateBuilder
	 */
	public static function create() {
		return new LoggerTemplateBuilder();
	}

	/**
	 *
	 * @return \swf\slf4php\config\builder\LoggerTemplateBuilder
	 */
	public function name($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 *
	 * @return \swf\slf4php\config\builder\LoggerTemplateBuilder
	 */
	public function level($level) {
		$this->level = $level;
		return $this;
	}

	/**
	 *
	 * @return \swf\slf4php\config\builder\LoggerTemplateBuilder
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
	 * @see \swf\slf4php\config\builder\Builder::initFromJson()
	 * @return \swf\slf4php\config\builder\LoggerTemplateBuilder
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