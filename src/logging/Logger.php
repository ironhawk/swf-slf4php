<?php

namespace wwwind\logging;

use Psr\Log\LoggerInterface;
use wwwind\util\TextUtil;
use wwwind\errors\Preconditions;

/**
 * You should instantiate Logger classes with LoggerFactory.
 *
 * @author ironhawk
 * @see \wwwind\logging\LoggerFactory
 */
class Logger implements LoggerInterface {

	private $name;

	private $namespacePath;

	private $logLevel;

	/**
	 * Array of appenders
	 *
	 * @var array
	 */
	private $appenders;

	/**
	 * You shouldn't instantiate this class directly! Use LoggerFactory instead!
	 *
	 * @see \wwwind\logging\LoggerFactory
	 */
	public function __construct($name, $logLevel, array $appenders) {
		$this->name = $name;
		$this->appenders = $appenders;
		$this->logLevel = $logLevel;
		
		if (! empty($name)) {
			$this->namespacePath = preg_split("/[\.\\\\\\/]/", $name);
		} else {
			$this->namespacePath = null;
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @return array
	 */
	public function getNamespacePath() {
		return $this->namespacePath;
	}

	/**
	 * Returns a clone but with a different name.<p>
	 * Used by LoggerFactory to quickly create a new instance of pre-configured Logger instance but with
	 * a different name.
	 *
	 * @param string $withName        	
	 * @return Logger
	 */
	public function getCloneWithName($withName) {
		return new Logger($withName, $this->logLevel, $this->appenders);
	}

	protected function parseMessage(array $funcParams) {
		// we skip the 1st 2 params - varargs begin at pos #2
		$msg = array_shift($funcParams);
		array_shift($funcParams);
		return TextUtil::resolveStringWithDataArray($msg, $funcParams);
	}

	
	/**
	 * System is unusable.
	 *
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function emergency($message, array $context = array()) {
		if ($this->logLevel > LoggerFactory::EMERGENCY)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->emergency($message, $context);
		}
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function alert($message, array $context = array()) {
		if ($this->logLevel > LoggerFactory::ALERT)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->alert($message, $context);
		}
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function critical($message, array $context = array()) {
		if ($this->logLevel > LoggerFactory::CRITICAL)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->critical($message, $context);
		}
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function error($message, array $context = array()) {
		if ($this->logLevel > LoggerFactory::ERROR)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->error($message, $context);
		}
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function warning($message, array $context = array()) {
		if ($this->logLevel > LoggerFactory::WARNING)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->warning($message, $context);
		}
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function notice($message, array $context = array()) {
		if ($this->logLevel > LoggerFactory::NOTICE)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->notice($message, $context);
		}
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function info($message, array $context = array()) {
		if ($this->logLevel > LoggerFactory::INFO)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->info($message, $context);
		}
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function debug($message, array $context = array()) {
		if ($this->logLevel > LoggerFactory::DEBUG)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->debug($message, $context);
		}
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level        	
	 * @param string $message        	
	 * @param array $context        	
	 *
	 * @return null
	 */
	public function log($level, $message, array $context = array()) {
		if ($this->logLevel > $level)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		foreach ($this->appenders as $appender) {
			$appender->log($level, $message, $context);
		}
	}

}