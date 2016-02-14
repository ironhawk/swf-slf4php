<?php

namespace swf\lf4php\tests\BDD\steps;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use swf\lf4php\Logger;
use swf\lf4php\tests\mocks\AppenderMock;
use swf\errors\Preconditions;
use swf\testing\BDDUtil;
use swf\lf4php\Appender;
use swf\lf4php\config\builder\LogConfigBuilder;

/**
 * Defines steps for testing Loggers - Appenders interactions
 */
class LoggerSteps implements Context, SnippetAcceptingContext {

	
	/**
	 * Temporary storage of returned Logger instance for checking it
	 *
	 * @var Logger
	 */
	private $lastReturnedLoggerInstance;

	/**
	 *
	 * @return Logger
	 */
	private function getLastCreatedLogger() {
		Preconditions::checkArgument(! is_null($this->lastReturnedLoggerInstance), "you haven't created a Logger instance yet with one of the @given steps!");
		return $this->lastReturnedLoggerInstance;
	}

	/**
	 * Gets the Appender of the Logger which name is the given name.
	 *
	 * @param Logger $logger        	
	 * @param string $appenderName        	
	 * @return Appender if no Appender found in Logger with that name NULL is returned
	 */
	private function getAppenderOfLogger($logger, $appenderName) {
		foreach ($logger->getAppenders() as $appender) {
			if ($appender->getName() == $appenderName)
				return $appender;
		}
		return null;
	}

	
	/**
	 * @Given a Logger with log level :logLevel and appenders :appenderNames
	 */
	public function givenALoggerWithLogLevelAndAppenders($logLevel, $appenderNames) {
		$logLevel = LogConfigBuilder::getAsLogLevel($logLevel);
		$appenders = [];
		foreach (BDDUtil::getListFromString($appenderNames) as $appenderName) {
			$appenders[] = new AppenderMock($appenderName);
		}
		$this->lastReturnedLoggerInstance = new Logger("testLogger", $logLevel, $appenders);
	}

	
	/**
	 * @When the following log messages are sent to the Logger:
	 */
	public function whenLogMessagesAreSentToLogger(TableNode $messagesTable) {
		$logger = $this->getLastCreatedLogger();
		
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
	public function thenAppenderHasPrintedTheFollowingMessages($appenderName, TableNode $messagesTable) {
		$logger = $this->getLastCreatedLogger();
		$appender = $this->getAppenderOfLogger($logger, $appenderName);
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

}
