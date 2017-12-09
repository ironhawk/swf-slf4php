<?php

namespace swf\slf4php;

use Psr\Log\LoggerInterface;
use swf\slf4php\config\LogConfig;
use swf\slf4php\config\LoggerTemplate;


/**
 * Creates Logger instances which implement Psr\Log\LoggerInterface<p>
 * Behind the scenes you can use real logging implementations like Monolog for instance<p>
 *
 * Logging levels came from syslog protocol defined in RFC 5424
 *
 * @author ironhawk
 *        
 */
class LoggerFactory {

	const DEBUG = 100;

	const INFO = 200;

	const NOTICE = 250;

	const WARNING = 300;

	const ERROR = 400;

	const CRITICAL = 500;

	const ALERT = 550;

	const EMERGENCY = 600;

	
	/**
	 * Logging levels from syslog protocol defined in RFC 5424
	 *
	 * @var array $levels Logging levels
	 */
	public static $levels2names = array(
		self::DEBUG => 'DEBUG',
		self::INFO => 'INFO',
		self::NOTICE => 'NOTICE',
		self::WARNING => 'WARNING',
		self::ERROR => 'ERROR',
		self::CRITICAL => 'CRITICAL',
		self::ALERT => 'ALERT',
		self::EMERGENCY => 'EMERGENCY'
	);

	public static $names2levels = array(
		'DEBUG' => self::DEBUG,
		'INFO' => self::INFO,
		'NOTICE' => self::NOTICE,
		'WARNING' => self::WARNING,
		'ERROR' => self::ERROR,
		'CRITICAL' => self::CRITICAL,
		'ALERT' => self::ALERT,
		'EMERGENCY' => self::EMERGENCY
	);

	
	/**
	 *
	 * @var LogConfig
	 */
	private static $config;

	/**
	 * A special instance which log level is above CRITICAL so basically it will silently drop all incoming
	 * log messages...
	 *
	 * @var Logger
	 */
	private static $nullLogger;
	
	/**
	 * Name of the logger class to instantiate
	 * <p>
	 * This class should extend swf\slf4php\Logger class - see contructor of that class to see how yours should
	 * look like!
	 * <p>
	 * Sometimes it is handy being able to provide an own Logger. E.g. when you configure this logging facade
	 * to be used in bigger systems like Drupal. 
	 *  
	 * @var String
	 */
	private static $loggerClassName = "swf\slf4php\Logger";

	public static function init(LogConfig $config, $loggerClassName = "swf\slf4php\Logger") {
		static::$config = $config;
		static::$loggerClassName = $loggerClassName;
	}

	/**
	 *
	 * @param string $fullyQualifiedClassName
	 *        	If you omit this parameter then only the default Logger can match
	 * @return Psr\Log\LoggerInterface
	 */
	public static function getLogger($fullyQualifiedClassName = '_default_') {
		if (is_null(static::$config)) {
			// no config -> our special 'NullLogger' is the good decision
			return static::getNullLogger();
		}
		// let's find the "best" matching Logger - this is the one with highest matching weight
		$namespacePath = LoggerTemplate::splitNamespacePath($fullyQualifiedClassName);
		$selectedLoggerTemplate = null;
		$maxMatchWeight = - 1;
		foreach (static::$config->getLoggerTemplates() as $loggerTemplate) {
			$matchWeight = static::matchNamespacePaths($loggerTemplate->getNamespacePath(), $namespacePath);
			if ($matchWeight > $maxMatchWeight) {
				$selectedLoggerTemplate = $loggerTemplate;
				$maxMatchWeight = $matchWeight;
			}
		}
		if (is_null($selectedLoggerTemplate)) {
			return static::getNullLogger();
		} else {
			// let's create the logger instance based on selected template
			$appenders = $selectedLoggerTemplate->getAppenders();
			if (is_null($appenders)) {
				// let's build up the appender list!
				$selectedLoggerTemplate->buildAndCacheAppenders(static::$config->getAppenders());
				$appenders = $selectedLoggerTemplate->getAppenders();
			}
			
			$reflector = new \ReflectionClass(static::$loggerClassName);
			return $reflector->newInstance($fullyQualifiedClassName, $selectedLoggerTemplate->getLogLevel(), $appenders);
		}
	}

	/**
	 * Checking the Logger namespace path against the given class namespace path and returns a weight.<p>
	 * -1 means: not matching<br>
	 * 0 means: Logger was the default Logger - represented with an empty path<br>
	 * x means: we found a match with length x<br>
	 *
	 * @param array $loggerNamespacePath        	
	 * @param array $namespacePath        	
	 * @return number
	 */
	private static function matchNamespacePaths($loggerNamespacePath, $namespacePath) {
		if (is_null($loggerNamespacePath)) {
			// this is the Logger configured without a name so this should match with everything - with lowest
			// weight which represents a match...
			return 0;
		}
		
		$weight = 0;
		$len = min(count($loggerNamespacePath), count($namespacePath));
		for ($weight = 0; $weight < $len; $weight ++) {
			if ($namespacePath[$weight] != $loggerNamespacePath[$weight]) {
				$weight = - 1;
				break;
			}
		}
		return $weight;
	}

	/**
	 * Returns the special "null" logger instance
	 *
	 * @return \swf\slf4php\Logger
	 */
	private static function getNullLogger() {
		if (is_null(static::$nullLogger)) {
			// the level will be special: 1 above the highest CRITICAL level so everything will be dropped immediatelly
			// ...
			$reflector = new \ReflectionClass(static::$loggerClassName);
			static::$nullLogger = $reflector->newInstance("NULL", static::CRITICAL + 1, []);
		}
		return static::$nullLogger;
	}

}