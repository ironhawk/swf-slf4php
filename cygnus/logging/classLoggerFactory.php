<?php

namespace cygnus\phpbasic\lib\logging;

use cygnus\phpbasic\lib\errors\Preconditions;
use Psr\Log\LoggerInterface;
use cygnus\phpbasic\lib\logging\config\LogConfig;


/**
 * Creates Logger instances which implement Psr\Log\LoggerInterface<p>
 * Behind the scenes there are real LoggerServices which might encapsulate different reeal logger
 * implementations<p>
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
	protected static $levels = array(
		self::DEBUG => 'DEBUG',
		self::INFO => 'INFO',
		self::NOTICE => 'NOTICE',
		self::WARNING => 'WARNING',
		self::ERROR => 'ERROR',
		self::CRITICAL => 'CRITICAL',
		self::ALERT => 'ALERT',
		self::EMERGENCY => 'EMERGENCY'
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
	}

}