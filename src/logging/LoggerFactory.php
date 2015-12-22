<?php

namespace cygnus\logging;

use cygnus\errors\Preconditions;
use Psr\Log\LoggerInterface;
use cygnus\logging\config\LogConfig;


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

	public static function init(LogConfig $config) {
		static::$config = $config;
	}

	/**
	 *
	 * @param string $fullyQualifiedClassName        	
	 * @return Psr\Log\LoggerInterface
	 */
	public static function getLogger($fullyQualifiedClassName) {
		Preconditions::checkArgument(! is_null($fullyQualifiedClassName), "fully qualified class name must be provided");
		if (is_null(static::$config)) {
			// no config -> NullLogger is the good decision
			return NullLogger::getInstance();
		}
		// let's find the "best" matching Logger
		$namespacePath = preg_split("/[\.\\\\\\/]/", $fullyQualifiedClassName);
		$selectedLogger = null;
		$maxMatchWeight = - 1;
		foreach (static::$config->getLoggers() as $logger) {
			$matchWeight = static::matchNamespacePaths($logger->getNamespacePath(), $namespacePath);
			if ($matchWeight > $maxMatchWeight) {
				$selectedLogger = $logger;
				$maxMatchWeight = $matchWeight;
			}
		}
		if (is_null($selectedLogger)) {
			$selectedLogger = NullLogger::getInstance();
		} else {
			// we quickly clone this logger and change its name to the fully qualified class name
			$selectedLogger = $selectedLogger->getCloneWithName($fullyQualifiedClassName);
		}
		return $selectedLogger;
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
			return 0;
		}
		$className = $namespacePath[count($namespacePath) - 1];
		$weight = 0;
		foreach ($loggerNamespacePath as $p) {
			if ($namespacePath[$weight] == $p) {
				$weight ++;
				// name of the class should represent more weight
				if ($namespacePath[$weight] == $className) {
					$weight ++;
				}
			} else {
				$weight = 0;
			}
		}
		if ($weight == 0)
			$weight = - 1;
		return $weight;
	}

}