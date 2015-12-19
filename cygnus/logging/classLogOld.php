<?php
define('FAILSAFE_LOG_FILE', APP_ROOT_DIR . 'log/safe_application.log');
define('FAILSAFE_ERRORLOG_FILE', APP_ROOT_DIR . 'log/safe_error.log');

define('NEWLINE', chr(10));

/**
 * Logger osztály, a szokásos statikus metódusokkal...
 */
class LogOld {

	/**
	 * lehetséges értékek: 'nolog' = 0, 'fatal' = 1, 'error' = 2, 'warn' = 3, 'info' = 4, 'debug' = 5, 'extraDebug' = 6;
	 */
	protected static $logLevel = null;

	static function getLogLevel() {
		if (! is_null(self::$logLevel))
			return self::$logLevel;
		if (defined('LOG_LEVEL'))
			return LOG_LEVEL;
		return 5;
	}

	/**
	 * Beállítja a logolás szintjét
	 * Visszakapcsolni a konfigurált értékre null átadásával lehet!
	 *
	 * @param int $level
	 *        	1-6, vagy null ha vussza akarsz térni a konfigurált szinthez
	 */
	static function setLogLevel($level) {
		self::$logLevel = $level;
	}

	protected static function getLogFile() {
		if (defined('LOG_FILE'))
			return LOG_FILE;
		return FAILSAFE_LOG_FILE;
	}

	protected static function getErrorLogFile() {
		if (defined('ERRORLOG_FILE'))
			return ERRORLOG_FILE;
		return FAILSAFE_ERRORLOG_FILE;
	}

	static function isExtraDebugEnabled() {
		return (Log::getLogLevel() >= 6);
	}

	static function isDebugEnabled() {
		return (Log::getLogLevel() >= 5);
	}

	static function isInfoEnabled() {
		return (Log::getLogLevel() >= 4);
	}

	static function isWarnEnabled() {
		return (Log::getLogLevel() >= 3);
	}

	static function isErrorEnabled() {
		return (Log::getLogLevel() >= 2);
	}

	static function isFatalEnabled() {
		return (Log::getLogLevel() >= 1);
	}

	protected static $userHandlerClass = false;

	static function getUsername() {
		if (static::$userHandlerClass === false) {
			if (defined("ADMIN_USERHANDLER_CLASS") && IS_ADMIN_CONTEXT) {
				static::$userHandlerClass = ADMIN_USERHANDLER_CLASS;
			} elseif (defined("SITE_USERHANDLER_CLASS")) {
				static::$userHandlerClass = SITE_USERHANDLER_CLASS;
			} elseif (hasClass("UserHandler")) {
				static::$userHandlerClass = "UserHandler";
			} else {
				static::$userHandlerClass = null;
			}
		}
		
		$username = null;
		if (static::$userHandlerClass) {
			$username = call_user_func_array(array(
				static::$userHandlerClass,
				'getLoginName'
			), array());
		}
		return $username;
	}

	static function getRemoteAddress() {
		if (isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		} else {
			return 'unknown';
		}
	}

	static function extraDebug($msg) {
		if (Log::getLogLevel() < 6)
			return;
		
		$sid = session_id();
		$ip = Log::getRemoteAddress();
		
		$username = self::getUsername();
		if ($username) {
			error_log("[" . date("Y-m-d H:i:s") . "] - eDEBUG (IP=$ip, sid=$sid): ($username) $msg\n\n", 3, Log::getLogFile());
		} else {
			error_log("[" . date("Y-m-d H:i:s") . "] - eDEBUG (IP=$ip, sid=$sid): $msg\n\n", 3, Log::getLogFile());
		}
	}

	static function debug($msg) {
		if (Log::getLogLevel() < 5)
			return;
		
		$sid = session_id();
		$ip = Log::getRemoteAddress();
		
		$username = self::getUsername();
		if ($username) {
			error_log("[" . date("Y-m-d H:i:s") . "] - DEBUG (IP=$ip, sid=$sid): ($username) $msg\n\n", 3, Log::getLogFile());
		} else {
			error_log("[" . date("Y-m-d H:i:s") . "] - DEBUG (IP=$ip, sid=$sid): $msg\n\n", 3, Log::getLogFile());
		}
	}

	static function info($msg) {
		if (Log::getLogLevel() < 4)
			return;
		
		$sid = session_id();
		$ip = Log::getRemoteAddress();
		
		$username = self::getUsername();
		if ($username) {
			error_log("[" . date("Y-m-d H:i:s") . "] - INFO (IP=$ip, sid=$sid): ($username) $msg\n\n", 3, Log::getLogFile());
		} else {
			error_log("[" . date("Y-m-d H:i:s") . "] - INFO (IP=$ip, sid=$sid): $msg\n\n", 3, Log::getLogFile());
		}
	}

	static function warn($msg) {
		if (Log::getLogLevel() < 3)
			return;
		
		$sid = session_id();
		$ip = Log::getRemoteAddress();
		
		$username = self::getUsername();
		if ($username) {
			error_log("[" . date("Y-m-d H:i:s") . "] - WARN (IP=$ip, sid=$sid): ($username) $msg\n\n", 3, Log::getLogFile());
			error_log("[" . date("Y-m-d H:i:s") . "] - WARN (IP=$ip, sid=$sid): ($username) $msg\n\n", 3, Log::getErrorLogFile());
		} else {
			error_log("[" . date("Y-m-d H:i:s") . "] - WARN (IP=$ip, sid=$sid): $msg\n\n", 3, Log::getLogFile());
			error_log("[" . date("Y-m-d H:i:s") . "] - WARN (IP=$ip, sid=$sid): $msg\n\n", 3, Log::getErrorLogFile());
		}
		
		error_log("Stacktrace:\n" . Log::getCurrentStacktraceAsString() . "\n", 3, Log::getErrorLogFile());
	}

	static function error($msg) {
		if (Log::getLogLevel() < 2)
			return;
		
		$sid = session_id();
		$ip = Log::getRemoteAddress();
		
		$username = self::getUsername();
		if ($username) {
			error_log("[" . date("Y-m-d H:i:s") . "] - ERROR (IP=$ip, sid=$sid): ($username) $msg\n", 3, Log::getLogFile());
			error_log("[" . date("Y-m-d H:i:s") . "] - ERROR (IP=$ip, sid=$sid): ($username) $msg\n", 3, Log::getErrorLogFile());
		} else {
			error_log("[" . date("Y-m-d H:i:s") . "] - ERROR (IP=$ip, sid=$sid): $msg\n", 3, Log::getLogFile());
			error_log("[" . date("Y-m-d H:i:s") . "] - ERROR (IP=$ip, sid=$sid): $msg\n", 3, Log::getErrorLogFile());
		}
		
		error_log("Stacktrace:\n" . Log::getCurrentStacktraceAsString() . "\n", 3, Log::getErrorLogFile());
	}

	static function fatal($msg) {
		if (Log::getLogLevel() < 1)
			return;
		
		$sid = session_id();
		$ip = Log::getRemoteAddress();
		
		$username = self::getUsername();
		if ($username) {
			error_log("[" . date("Y-m-d H:i:s") . "] - FATAL (IP=$ip, sid=$sid): ($username) $msg\n", 3, Log::getLogFile());
			error_log("[" . date("Y-m-d H:i:s") . "] - FATAL (IP=$ip, sid=$sid): ($username) $msg\n", 3, Log::getErrorLogFile());
		} else {
			error_log("[" . date("Y-m-d H:i:s") . "] - FATAL (IP=$ip, sid=$sid): $msg\n", 3, Log::getLogFile());
			error_log("[" . date("Y-m-d H:i:s") . "] - FATAL (IP=$ip, sid=$sid): $msg\n", 3, Log::getErrorLogFile());
		}
		
		error_log("Stacktrace:\n" . Log::getCurrentStacktraceAsString() . "\n", 3, Log::getErrorLogFile());
	}

	/**
	 * Az exception-ből kinyerhető infók megszerzése stringként, mely tartalmazza a code-t, a message-et
	 * és a komplett stacktrace-t
	 *
	 * @param Exception $exception        	
	 * @param string $lineSeparator
	 *        	mi legyen a sortörés?
	 * @return string az exception info
	 */
	static function getExceptionInfo($exception, $lineSeparator = NEWLINE) {
		if (is_null($exception))
			return 'getExceptionInfo(): hey! exception object was null!';
		
		$str = "";
		$str .= "Exception code: " . $exception->getCode() . $lineSeparator;
		$str .= "Exception message: '" . $exception->getMessage() . "'" . $lineSeparator;
		$str .= "Stacktrace:" . $lineSeparator;
		$str .= Log::getExceptionStacktraceAsString($exception, $lineSeparator, 1);
		return $str;
	}

	/**
	 * Aktuális stacktrace megszerzése string-ként
	 *
	 * @param string $lineSeparator
	 *        	mivel tagolja a sorokat?
	 * @return string stacktrace string
	 */
	static function getCurrentStacktraceAsString($lineSeparator = NEWLINE) {
		$e = new Exception('stacktrace generator exception');
		return Log::getExceptionStacktraceAsString($e, $lineSeparator, 1);
	}

	/**
	 * Adott exception stacktrace-ének megszerzése stringként
	 *
	 * @param Exception $exception        	
	 * @param String $lineSeparator
	 *        	mivel tagolja a sorokat?
	 * @param int $startIndex
	 *        	melyik indextől kezdve írjon ki?
	 * @return string stacktrace string
	 */
	static function getExceptionStacktraceAsString($exception, $lineSeparator = NEWLINE, $startIndex = 0) {
		if (is_null($exception))
			return 'getExceptionStacktraceAsString(): hey! exception object was null!';
		
		$i = 0;
		$str = "";
		foreach ($exception->getTrace() as $key => $trace) {
			if ($i >= $startIndex)
				$str .= Log::getTraceAsString($trace, $i) . $lineSeparator;
			$i ++;
		}
		return $str;
	}

	private static function getTraceAsString($_trace, $_i) {
		$str = "#$_i ";
		if (array_key_exists("file", $_trace)) {
			if (defined('APP_ROOT_DIR'))
				$filepath = FileUtil::getRelativePath(APP_ROOT_DIR, $_trace["file"]);
			else
				$filepath = $_trace["file"];
			$str .= $filepath;
		}
		if (array_key_exists("line", $_trace)) {
			$str .= "(" . $_trace["line"] . "): ";
		}
		if (array_key_exists("class", $_trace) && array_key_exists("type", $_trace)) {
			$str .= $_trace["class"] . $_trace["type"];
		}
		if (array_key_exists("function", $_trace)) {
			$str .= $_trace["function"] . "()";
			/*
			 * if (array_key_exists("args",$_trace)) {
			 * if (count($_trace["args"]) > 0) {
			 * $args = $_trace["args"];
			 * $type = gettype($args[0]);
			 * $value = $args[0];
			 * unset($args);
			 * if ($type == "boolean") {
			 * if ($value) {
			 * $str.= "true";
			 * }
			 * else {
			 * $str.= "false";
			 * }
			 * }
			 * elseif ($type == "integer" || $type == "double") {
			 * if (settype($value, "string")) {
			 * if (strlen($value) <= 20) {
			 * $str.= $value;
			 * }
			 * else {
			 * $str.= substr($value,0,17)."...";
			 * }
			 * }
			 * else {
			 * if ($type == "integer" ) {
			 * $str.= "? integer ?";
			 * }
			 * else {
			 * $str.= "? double or float ?";
			 * }
			 * }
			 * }
			 * elseif ($type == "string") {
			 * if (strlen($value) <= 18) {
			 * $str.= "'$value'";
			 * }
			 * else {
			 * $str.= "'".substr($value,0,15)."...'";
			 * }
			 * }
			 * elseif ($type == "array") {
			 * $str.= "Array";
			 * }
			 * elseif ($type == "object") {
			 * $str.= "Object";
			 * }
			 * elseif ($type == "resource") {
			 * $str.= "Resource";
			 * }
			 * elseif ($type == "NULL") {
			 * $str.= "null";
			 * }
			 * elseif ($type == "unknown type") {
			 * $str.= "? unknown type ?";
			 * }
			 * unset($type);
			 * unset($value);
			 * }
			 * if (count($_trace["args"]) > 1) {
			 * $str.= ",...";
			 * }
			 * }
			 * $str.= ")<br/>";
			 */
		}
		return $str;
	}

}

?>