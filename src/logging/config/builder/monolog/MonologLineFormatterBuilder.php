<?php

namespace cygnus\logging\config\builder\monolog;

use Monolog\Formatter\LineFormatter;

class MonologLineFormatterBuilder implements MonologFormatterBuilder {

	private $lineFormatTemplate;

	/**
	 *
	 * @return \cygnus\logging\config\builder\monolog\MonologLineFormatterBuilder
	 *
	 */
	public static function create() {
		return new MonologLineFormatterBuilder();
	}

	/**
	 *
	 * @param string $lineFormateTemplate        	
	 * @return \cygnus\logging\config\builder\monolog\MonologLineFormatterBuilder
	 */
	public function format($lineFormateTemplate) {
		$this->lineFormatTemplate = $lineFormateTemplate;
		return $this;
	}

	/**
	 *
	 * @return \Monolog\Formatter\LineFormatter
	 */
	public function build() {
		return new LineFormatter($this->lineFormatTemplate);
	}

}