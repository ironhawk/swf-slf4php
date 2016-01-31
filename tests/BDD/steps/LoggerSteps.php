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

}
