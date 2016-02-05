<?php

namespace wwwind\logging\tests\BDD\steps;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use wwwind\logging\config\LogConfig;
use wwwind\logging\Logger;
use wwwind\logging\config\builder\LogConfigBuilder;
use wwwind\logging\tests\mocks\AppenderMock;
use wwwind\errors\Preconditions;
use wwwind\testing\BDDUtil;
use wwwind\logging\LoggerFactory;

/**
 * Defines steps for building up logging setup and test Loggers
 */
class LoggerSteps implements Context, SnippetAcceptingContext {

	/**
	 * To store what @Given steps did
	 *
	 * @var LogConfig
	 */
	private $config;

	/**
	 * Temporary storage of returned Logger instance for checking it
	 *
	 * @var Logger
	 */
	private $lastReturnedLoggerInstance;

	public function __construct() {
		$this->config = new LogConfig();
	}

	/**
	 * @Given a mocked Appender with name :name
	 */
	public function GivenMockedAppenderWithName($name) {
		$appenderMock = new AppenderMock($name);
		$this->config->appender($appenderMock);
	}

	/**
	 * @Given a Logger with name :name, log level :levelStr and appenders :appenderNames
	 */
	public function GivenLoggerWithNameAndLogLevelAndAppenders($name, $levelStr, $appenderNames) {
		$this->createLoggerWithNameAndLogLevelAndAppenders($name, $levelStr, $appenderNames);
	}

	
	/**
	 * @Given a Logger without name, log level :levelStr and appenders :appenderNames
	 */
	public function GivenLoggerWithNameNotSetAndLogLevelAndAppenders($levelStr, $appenderNames) {
		$this->createLoggerWithNameAndLogLevelAndAppenders(null, $levelStr, $appenderNames);
	}

	private function createLoggerWithNameAndLogLevelAndAppenders($name, $levelStr, $appenderNames) {
		$logLevel = LogConfigBuilder::getAsLogLevel($levelStr);
		$appenders = [];
		$appenderNames = explode(', ', $appenderNames);
		foreach ($appenderNames as $appenderName) {
			$appenders[] = $this->config->getAppender($appenderName);
		}
		$logger = new Logger($name, $logLevel, $appenders);
		$this->config->logger($logger);
	}

	/**
	 * @When the following log messages are sent to Logger :loggerName:
	 */
	public function WhenLogMessagesAreSentToLogger($loggerName, TableNode $messagesTable) {
		$logger = $this->config->getLogger($loggerName);
		Preconditions::checkArgument(! is_null($logger), "no Logger found with name '{}'", $loggerName);
		
		foreach ($messagesTable->getColumnsHash() as $row) {
			
			// take call params from the table we got
			$logContext = [];
			$logLevel = BDDUtil::getStringFromColumn($row, 'logLevel');
			$message = BDDUtil::getStringFromColumn($row, 'message');
			$logParams = BDDUtil::getListFromColumn($row, 'listed parameters');
			
			// lets assemble the call parameters!
			$callParams = [];
			$callParams[] = $message;
			$callParams[] = $logContext;
			if (! empty($logParams)) {
				$callParams = BDDUtil::arrayConcatenate($callParams, $logParams);
			}
			

			if (strcasecmp($logLevel, 'DEBUG') == 0)
				call_user_func_array(array(
					$logger,
					'debug'
				), $callParams);
			elseif (strcasecmp($logLevel, 'INFO') == 0)
				call_user_func_array(array(
					$logger,
					'info'
				), $callParams);
			elseif (strcasecmp($logLevel, 'NOTICE') == 0)
				call_user_func_array(array(
					$logger,
					'notice'
				), $callParams);
			elseif (strcasecmp($logLevel, 'WARNING') == 0)
				call_user_func_array(array(
					$logger,
					'warning'
				), $callParams);
			elseif (strcasecmp($logLevel, 'ERROR') == 0)
				call_user_func_array(array(
					$logger,
					'error'
				), $callParams);
			elseif (strcasecmp($logLevel, 'CRITICAL') == 0)
				call_user_func_array(array(
					$logger,
					'critical'
				), $callParams);
			elseif (strcasecmp($logLevel, 'ALERT') == 0)
				call_user_func_array(array(
					$logger,
					'alert'
				), $callParams);
			elseif (strcasecmp($logLevel, 'EMERGENCY') == 0)
				call_user_func_array(array(
					$logger,
					'emergency'
				), $callParams);
			else
				throw new \Exception("invalid logLevel '$logLevel'");
		}
	}

	
	/**
	 * @Then Appender :appenderName has received the following messages in this order:
	 */
	public function ThenAppenderHasPrintedTheFollowingMessages($appenderName, TableNode $messagesTable) {
		$appender = $this->config->getAppender($appenderName);
		Preconditions::checkArgument(! is_null($appender), "no Appender found with name '{}'", $appenderName);
		
		// note: appender now is an AppenderMock!
		$actualMessages = $appender->getMessages();
		$expectedMessages = [];
		foreach ($messagesTable->getColumnsHash() as $row) {
			$message = BDDUtil::getStringFromColumn($row, 'message');
			$expectedMessages[] = $message;
		}
		
		\PHPUnit_Framework_TestCase::assertEquals($expectedMessages, $actualMessages, "Array of messages doesn't match with expected");
	}

	
	/**
	 * @When matching Logger asked from LoggerFactory for class name :className
	 */
	public function WhenMatchingLoggerAskedFromLoggerFactoryForClassName($className) {
		LoggerFactory::init($this->config);
		$this->lastReturnedLoggerInstance = LoggerFactory::getLogger($className);
	}

	/**
	 * @Then the returned Logger instance was created by cloning configured Logger instance with name :loggerName
	 */
	public function ThenLoggerWithNameIsReturned($loggerName) {
		Preconditions::checkState(! is_null($this->lastReturnedLoggerInstance), "You can use this step only after using a @When step which picks up a Logger instance from the LoggerFactory!");
		$actualInstance = $this->lastReturnedLoggerInstance;
		
		$expectedInstance = $this->config->getLogger($loggerName);
		Preconditions::checkArgument(! is_null($expectedInstance), "there is no configured Logger found with name '{}'! check your @Given steps or the given loggerName parameter!", $loggerName);
		
		\PHPUnit_Framework_TestCase::assertEquals($expectedInstance->getLogLevel(), $actualInstance->getLogLevel(), "logLevel does not match");
		\PHPUnit_Framework_TestCase::assertEquals($expectedInstance->getAppenders(), $actualInstance->getAppenders(), "Appenders does not match");
	}

	
	/**
	 * @Then the returned Logger instance name is :loggerName
	 */
	public function ThenTheReturnedLoggerInstanceNameIs($loggerName) {
		Preconditions::checkState(! is_null($this->lastReturnedLoggerInstance), "You can use this step only after using a @When step which picks up a Logger instance from the LoggerFactory!");
		
		\PHPUnit_Framework_TestCase::assertEquals($loggerName, $this->lastReturnedLoggerInstance->getName(), "Name of Logger does not match with expected name!");
	}

	
	/**
	 * @Then the returned Logger instance is the special NullLogger
	 */
	public function ThenTheReturnedLoggerInstanceIsTheSpecialNulllogger() {
		Preconditions::checkState(! is_null($this->lastReturnedLoggerInstance), "You can use this step only after using a @When step which picks up a Logger instance from the LoggerFactory!");
		$actualLogger = $this->lastReturnedLoggerInstance;
		
		$loggerFactoryReflectionProp = new \ReflectionProperty("wwwind\\logging\\LoggerFactory", "nullLogger");
		$loggerFactoryReflectionProp->setAccessible(true);
		$expectedLogger = $loggerFactoryReflectionProp->getValue();
		
		\PHPUnit_Framework_TestCase::assertEquals($expectedLogger, $actualLogger, "The returned Logger is not the special NullLogger instance");
	}

}
