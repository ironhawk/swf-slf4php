<?php

namespace wwwind\logging\tests\BDD\steps;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use wwwind\logging\config\LogConfig;
use Behat\Behat\Tester\Exception\PendingException;
use wwwind\logging\Logger;
use wwwind\logging\config\builder\LogConfigBuilder;
use wwwind\logging\tests\mocks\AppenderMock;
use wwwind\errors\Preconditions;

/**
 * Defines application features from the specific context.
 */
class LoggerSteps implements Context, SnippetAcceptingContext {

	/**
	 *
	 * @var LogConfig
	 */
	private $config;

	/**
	 * Initializes context.
	 *
	 * Every scenario gets its own context instance.
	 * You can also pass arbitrary arguments to the
	 * context constructor through behat.yml.
	 */
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
			$logLevel = $row['logLevel'];
			$message = $row['message'];
			
			if (strcasecmp($logLevel, 'DEBUG') == 0)
				$logger->debug($message);
			elseif (strcasecmp($logLevel, 'INFO') == 0)
				$logger->info($message);
			elseif (strcasecmp($logLevel, 'NOTICE') == 0)
				$logger->notice($message);
			elseif (strcasecmp($logLevel, 'WARNING') == 0)
				$logger->warning($message);
			elseif (strcasecmp($logLevel, 'ERROR') == 0)
				$logger->error($message);
			elseif (strcasecmp($logLevel, 'CRITICAL') == 0)
				$logger->critical($message);
			elseif (strcasecmp($logLevel, 'ALERT') == 0)
				$logger->alert($message);
			elseif (strcasecmp($logLevel, 'EMERGENCY') == 0)
				$logger->emergency($message);
			else
				throw new \Exception("invalid logLevel '$logLevel'");
		}
	}

	
	/**
	 * @Then Appender :appenderName has received the following messages:
	 */
	public function appenderHasPrintedTheFollowingMessages($appenderName, TableNode $messagesTable) {
		$appender = $this->config->getAppender($appenderName);
		Preconditions::checkArgument(! is_null($appender), "no Appender found with name '{}'", $appenderName);
		
		// note: appender now is an AppenderMock!
		$actualMessages = $appender->getMessages();
		$expectedMessages = [];
		foreach ($messagesTable->getColumnsHash() as $row) {
			$message = $row['message'];
			$expectedMessages[] = $message;
		}
		
		\PHPUnit_Framework_TestCase::assertEquals($expectedMessages, $actualMessages, "Array of messages doesn't match with expected");
	}

}
