<?php

namespace swf\slf4php;

use Psr\Log\LoggerInterface;
use swf\util\TextUtil;

/**
 * You should instantiate Logger classes with LoggerFactory only...
 *
 * @author ironhawk
 * @see \swf\slf4php\LoggerFactory
 */
class Logger implements LoggerInterface {

	const LINE_FEED = "\n";
	
	const EMPTY_CONTEXT = array();
	
	static function CONTEXT_WITH_STACKTRACE() {
		return array(
				"stacktrace" => static::getCurrentStacktraceAsString(" | ")
		);
	}
	
	/**
	 * If TRUE then automatically injects the stacktrace into the logging context for WARNING, ERROR, etc...
	 * @var bool
	 */
	public static $addStacktraceToErrors = true;
	
	private $name;

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
	 * @see \swf\slf4php\LoggerFactory
	 */
	public function __construct($name, $logLevel, array $appenders) {
		$this->name = $name;
		$this->appenders = $appenders;
		$this->logLevel = $logLevel;
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
	 * @return int
	 */
	public function getLogLevel() {
		return $this->logLevel;
	}

	
	/**
	 *
	 * @return array
	 */
	public function getAppenders() {
		return $this->appenders;
	}

	private function parseMessage(array $funcParams) {
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
	public function emergency($message, array $context = self::EMPTY_CONTEXT) {
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
	public function alert($message, array $context = self::EMPTY_CONTEXT) {
		if ($this->logLevel > LoggerFactory::ALERT)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		if(static::$addStacktraceToErrors && !array_key_exists('stacktrace', $context)) {
			$context['stacktrace'] = static::getCurrentStacktraceAsString(" | ");
		}
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
	public function critical($message, array $context = self::EMPTY_CONTEXT) {
		if ($this->logLevel > LoggerFactory::CRITICAL)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		if(static::$addStacktraceToErrors && !array_key_exists('stacktrace', $context)) {
			$context['stacktrace'] = static::getCurrentStacktraceAsString(" | ");
		}
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
	public function error($message, array $context = self::EMPTY_CONTEXT) {
		if ($this->logLevel > LoggerFactory::ERROR)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		if(static::$addStacktraceToErrors && !array_key_exists('stacktrace', $context)) {
			$context['stacktrace'] = static::getCurrentStacktraceAsString(" | ");
		}
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
	public function warning($message, array $context = self::EMPTY_CONTEXT) {
		if ($this->logLevel > LoggerFactory::WARNING)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		if(static::$addStacktraceToErrors && !array_key_exists('stacktrace', $context)) {
			$context['stacktrace'] = static::getCurrentStacktraceAsString(" | ");
		}
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
	public function notice($message, array $context = self::EMPTY_CONTEXT) {
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
	public function info($message, array $context = self::EMPTY_CONTEXT) {
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
	public function debug($message, array $context = self::EMPTY_CONTEXT) {
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
	public function log($level, $message, array $context = self::EMPTY_CONTEXT) {
		if ($this->logLevel > $level)
			return;
		$message = $this->parseMessage(func_get_args());
		$context['loggerName'] = $this->name;
		if($level >= LoggerFactory::WARNING && static::$addStacktraceToErrors && !array_key_exists('stacktrace', $context)) {
			$context['stacktrace'] = static::getCurrentStacktraceAsString(" | ");
		}
		foreach ($this->appenders as $appender) {
			$appender->log($level, $message, $context);
		}
	}
	
	
	
	/**
	 * get the current stacktrace
	 *
	 * @param string $lineSeparator
	 * @return string stacktrace string
	 */
	static function getCurrentStacktraceAsString($lineSeparator = self::LINE_FEED)
	{
		$e = new \Exception('stacktrace generator exception');
		return static::getExceptionStacktraceAsString($e, $lineSeparator, 1);
	}
	
	/**
	 * Returns formatted stacktrace as string
	 *
	 * @param Exception $exception
	 * @param String $lineSeparator
	 * @param int $startIndex starting by this index
	 * @return string
	 */
	static function getExceptionStacktraceAsString($exception, $lineSeparator = self::LINE_FEED, $startIndex = 0) {
	
		if(is_null($exception))
			return 'getExceptionStacktraceAsString(): hey! exception object was null!';
	
			$i = 0;
			$str = "";
			foreach ($exception->getTrace() as $key => $trace) {
				if($i >= $startIndex)
					$str.= static::getTraceAsString($trace, $i).$lineSeparator;
					$i++;
			}
			return $str;
	}
	
	private static function getTraceAsString($_trace, $_i) {
		$str = "#$_i ";
		if (array_key_exists("file",$_trace)) {
			$filepath = $_trace["file"];
			/*
			if(defined('APP_ROOT_DIR')) {
				$filepath = FileUtil::getRelativePath(APP_ROOT_DIR, $_trace["file"]);
			}
			*/
					
			$str.= $filepath;
		}
		if (array_key_exists("line",$_trace)) {
			$str.= "(".$_trace["line"]."): ";
		}
		if (array_key_exists("class",$_trace) && array_key_exists("type",$_trace)) {
			$str.= $_trace["class"].$_trace["type"];
		}
		if (array_key_exists("function",$_trace)) {
			$str.= $_trace["function"]."()";
		}
		return $str;
	}
	

}