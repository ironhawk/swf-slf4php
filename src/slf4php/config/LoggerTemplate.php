<?php

namespace swf\slf4php\config;

/**
 * This class is used by LoggerFactory to quickly create an appropriate Logger instance
 *
 * @author ironhawk
 *        
 */
class LoggerTemplate {

	const NAMESPACE_PATH_SPLIT_PATTERN = "/[\.\\\\\\/]/";

	private $namespacePath;

	private $logLevel;

	/**
	 * Array of names of Appenders the Logger will use
	 *
	 * @var array
	 */
	private $appenderNames;

	/**
	 * The assembled list of Appender instances - will be created the getAppenders() method
	 * is called the first time.
	 * This list also can be considered as some kind of a cache field...
	 *
	 * @var array
	 */
	private $appenders;

	public function __construct($name, $logLevel, array $appenderNames) {
		$this->appenderNames = $appenderNames;
		$this->logLevel = $logLevel;
		$this->namespacePath = static::splitNamespacePath($name);
		$this->appenders = null;
	}

	
	/**
	 *
	 * @return array
	 */
	public function getNamespacePath() {
		return $this->namespacePath;
	}

	/**
	 *
	 * @return int
	 */
	public function getLogLevel() {
		return $this->logLevel;
	}

	/**
	 *
	 * @return array
	 */
	public function getAppenderNames() {
		return $this->appenderNames;
	}

	
	/**
	 * Creates the list of Appenders this logger template is configured for.
	 * Result is cached!<p>
	 * This method is invoked by LoggerFactory when this template is used the first time to instantiate a Logger instance
	 *
	 * @param array $appendersByName
	 *        	the initialized Appender instances provided by caller in appenderName => appender format
	 */
	public function buildAndCacheAppenders(array $appendersByName) {
		// let's build up and cache the appender list!
		$this->appenders = [];
		foreach ($this->appenderNames as $appenderName) {
			if (array_key_exists($appenderName, $appendersByName))
				$this->appenders[] = $appendersByName[$appenderName];
		}
	}

	
	/**
	 * This method can be used only after buildAndCacheAppenders was already invoked!
	 *
	 * @return array the list of Appenders matching with appenderNames we have
	 */
	public function getAppenders() {
		return $this->appenders;
	}

	public static function splitNamespacePath($namespacePath) {
		if (empty($namespacePath))
			return null;
		return preg_split(self::NAMESPACE_PATH_SPLIT_PATTERN, $namespacePath, null, PREG_SPLIT_NO_EMPTY);
	}

}