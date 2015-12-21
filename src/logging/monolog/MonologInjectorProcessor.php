<?php

namespace cygnus\logging\monolog;

class MonologInjectorProcessor {

	public static function process($record) {
		$record['extra']['loggerName'] = $record['context']['loggerName'];
		unset($record['context']['loggerName']);
		return $record;
	}

}