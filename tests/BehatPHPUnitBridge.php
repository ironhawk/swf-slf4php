<?php

namespace wwwind\logging\tests;

use Symfony\Component\Console\Input\ArrayInput;
use Behat\Behat\ApplicationFactory;
use wwwind\errors\Preconditions;

/**
 * I want to be able to run Behaviour driven tests within PHPUnit.
 * Behat was choosen as BDD tool. This class
 * helps us to run Behat scenarios within a PHPUnit test.
 *
 * @author ironhawk
 *        
 */
class BehatPHPUnitBridge {

	/**
	 * Creates and runs a Behat test from within a PHPUnit test case
	 *
	 * @param String $specificFile
	 *        	Relative path of specific .feature file - if want to run just one
	 * @return returns Behat exit code which is 0 if all tests passed - non-zero otherwise
	 */
	public static function runBehat($specificFile = null) {
		Preconditions::checkArgument(empty($specificFile) || is_string($specificFile), "Given param '{}' should be a String as it should describe ONE file!", $specificFile);
		$factory = new ApplicationFactory();
		$behatApp = $factory->createApplication();
		$behatApp->setCatchExceptions(false);
		$behatApp->setAutoExit(false);
		
		$testsRootFolder = dirname(__FILE__);
		
		$input = [
			'--strict' => true,
			'--stop-on-failure' => true,
			'--config' => $testsRootFolder . '/behat-config.yml'
		];
		

		if (! empty($specificFile)) {
			$input['paths'] = $testsRootFolder . '/' . $specificFile;
		}
		return $behatApp->run(new ArrayInput($input));
	}

	public static function testWithBehat($specificFile = null) {
		$behatResult = self::runBehat($specificFile);
		if (! empty($specificFile)) {
			$errMsg = "return value of Behat script running '$specificFile' indicates failure";
		} else {
			$errMsg = "return value of Behat script indicates failure";
		}
		\PHPUnit_Framework_TestCase::assertEquals(0, $behatResult, $errMsg);
	}

}