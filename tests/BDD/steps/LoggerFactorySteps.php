<?php

namespace swf\lf4php\tests\BDD\steps;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use swf\lf4php\config\LogConfig;
use swf\lf4php\Logger;
use swf\lf4php\config\builder\LogConfigBuilder;
use swf\lf4php\tests\mocks\AppenderMock;
use swf\errors\Preconditions;
use swf\lf4php\LoggerFactory;
use swf\lf4php\config\LoggerTemplate;
use swf\testing\BDDUtil;

/**
 * Defines steps for building up logging setup and test Loggers
 */
class LoggerFactorySteps implements Context, SnippetAcceptingContext {

	/**
	 * To store what @Given steps did
	 *
	 * @var LogConfigBuilder
	 */
	private $configBuilder;

	
	/**
	 * Temporary storage of returned Logger instance for checking it
	 *
	 * @var Logger
	 */
	private $lastReturnedLoggerInstance;

	public function __construct() {
		$this->givenNewLogConfigBuilder();
	}

	
	/**
	 * Builds and stores the LogConfig object based on the current state of the configBuilder
	 *
	 * @return \swf\lf4php\config\LogConfig
	 */
	private function getConfig() {
		return $this->configBuilder->build();
	}

	
	/**
	 * @Given we have an empty LogConfigBuilder
	 */
	public function givenNewLogConfigBuilder() {
		$this->configBuilder = new LogConfigBuilder();
	}

	/**
	 * @Given a configured mocked Appender with name :name
	 */
	public function givenAConfigurednMockedAppenderWithName($name) {
		$appenderMock = new AppenderMock($name);
		$this->configBuilder->appender($appenderMock);
	}

	/**
	 * @Given a configured LoggerTemplate with name :name, log level :levelStr and appenders :appenderNames
	 */
	public function givenAConfiguredLoggerTemplateWithNameAndLogLevelAndAppenders($name, $levelStr, $appenderNames) {
		$this->createLoggerTemplateWithNameAndLogLevelAndAppenders($name, $levelStr, $appenderNames);
	}

	
	/**
	 * @Given a configured LoggerTemplate with log level :levelStr and appenders :appenderNames but without name
	 */
	public function givenLoggerTemplateWithoutNameAndLogLevelAndAppenders($levelStr, $appenderNames) {
		$this->createLoggerTemplateWithNameAndLogLevelAndAppenders(null, $levelStr, $appenderNames);
	}

	private function createLoggerTemplateWithNameAndLogLevelAndAppenders($name, $levelStr, $appenderNames) {
		$logLevel = LogConfigBuilder::getAsLogLevel($levelStr);
		$appenderNames = BDDUtil::getListFromString($appenderNames);
		$logger = new LoggerTemplate($name, $logLevel, $appenderNames);
		$this->configBuilder->logger($logger);
	}

	
	/**
	 * @When matching Logger asked from LoggerFactory for class name :className
	 */
	public function whenMatchingLoggerAskedFromLoggerFactoryForClassName($className) {
		LoggerFactory::init($this->getConfig());
		$this->lastReturnedLoggerInstance = LoggerFactory::getLogger($className);
	}

	/**
	 * @Then the returned Logger instance was created by cloning configured LoggerTemplate instance with name :loggerTplName
	 */
	public function thenLoggerWithNameIsReturned($loggerTplName) {
		Preconditions::checkState(! is_null($this->lastReturnedLoggerInstance), "You can use this step only after using a @When step which picks up a Logger instance from the LoggerFactory!");
		$logger = $this->lastReturnedLoggerInstance;
		
		$template = $this->getConfig()->getLoggerTemplate($loggerTplName);
		Preconditions::checkArgument(! is_null($template), "there is no configured LoggerTemplate found with name '{}'! check your @Given steps or the given loggerName parameter!", $loggerTplName);
		
		// testing logLevel is easy
		\PHPUnit_Framework_TestCase::assertEquals($template->getLogLevel(), $logger->getLogLevel(), "logLevel does not match");
		
		\PHPUnit_Framework_TestCase::assertEquals($template->getAppenders(), $logger->getAppenders(), "Appenders does not match");
	}

	
	/**
	 * @Then the returned Logger instance name is :loggerName
	 */
	public function thenTheReturnedLoggerInstanceNameIs($loggerName) {
		Preconditions::checkState(! is_null($this->lastReturnedLoggerInstance), "You can use this step only after using a @When step which picks up a Logger instance from the LoggerFactory!");
		
		\PHPUnit_Framework_TestCase::assertEquals($loggerName, $this->lastReturnedLoggerInstance->getName(), "Name of Logger does not match with expected name!");
	}

	
	/**
	 * @Then the returned Logger instance is the special NullLogger
	 */
	public function thenTheReturnedLoggerInstanceIsTheSpecialNulllogger() {
		Preconditions::checkState(! is_null($this->lastReturnedLoggerInstance), "You can use this step only after using a @When step which picks up a Logger instance from the LoggerFactory!");
		$actualLogger = $this->lastReturnedLoggerInstance;
		
		$loggerFactoryReflectionProp = new \ReflectionProperty("swf\\lf4php\\LoggerFactory", "nullLogger");
		$loggerFactoryReflectionProp->setAccessible(true);
		$expectedLogger = $loggerFactoryReflectionProp->getValue();
		
		\PHPUnit_Framework_TestCase::assertEquals($expectedLogger, $actualLogger, "The returned Logger is not the special NullLogger instance");
	}

}
