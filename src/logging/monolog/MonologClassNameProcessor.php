<?php

namespace cygnus\logging\monolog;

class MonologClassNameProcessor {

	public static function process($record) {
		$record['extra']['loggerName'] = $record['context']['loggerName'];
		unset($record['context']['loggerName']);
		return $record;
	}

}