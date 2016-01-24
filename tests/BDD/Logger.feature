
Feature: Logger
  To log messages
  As a Developer
  I have a well functioning Logger class

Background:
  Given a mocked Appender with name "AppenderMock1"
  Given a mocked Appender with name "AppenderMock2"

Scenario: 1. Log level test: DEBUG level
  Given a Logger with name "wwwind.logging.test", log level "DEBUG" and appenders "AppenderMock1, AppenderMock2"
  When the following log messages are sent to Logger "wwwind.logging.test":
  	| logLevel  | message                 |
  	| DEBUG     | DEBUG level message     |
  	| INFO      | INFO level message      |
  	| NOTICE    | NOTICE level message    |
  	| WARNING   | WARNING level message   |
  	| ERROR     | ERROR level message     |
  	| CRITICAL  | CRITICAL level message  |
  	| ALERT     | ALERT level message     |
  	| EMERGENCY | EMERGENCY level message |
  