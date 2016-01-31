
Feature: Logger
  To log messages
  As a Developer
  I have a well functioning Logger class

Background:
  Given a mocked Appender with name "AppenderMock1"
  Given a mocked Appender with name "AppenderMock2"

Scenario: 1. Log level test: DEBUG level - we should see all messages
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
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                 |
  	| DEBUG level message     |
  	| INFO level message      |
  	| NOTICE level message    |
  	| WARNING level message   |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  And Appender "AppenderMock2" has received the following messages in this order:
  	| message                 |
  	| DEBUG level message     |
  	| INFO level message      |
  	| NOTICE level message    |
  	| WARNING level message   |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  	
Scenario: 2. Log level test: INFO level
  Given a Logger with name "wwwind.logging.test", log level "INFO" and appenders "AppenderMock1, AppenderMock2"
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
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                 |
  	| INFO level message      |
  	| NOTICE level message    |
  	| WARNING level message   |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  And Appender "AppenderMock2" has received the following messages in this order:
  	| message                 |
  	| INFO level message      |
  	| NOTICE level message    |
  	| WARNING level message   |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  	
  	
Scenario: 3. Log level test: NOTICE level
  Given a Logger with name "wwwind.logging.test", log level "NOTICE" and appenders "AppenderMock1, AppenderMock2"
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
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                 |
  	| NOTICE level message    |
  	| WARNING level message   |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  And Appender "AppenderMock2" has received the following messages in this order:
  	| message                 |
  	| NOTICE level message    |
  	| WARNING level message   |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  	
Scenario: 4. Log level test: WARNING level
  Given a Logger with name "wwwind.logging.test", log level "WARNING" and appenders "AppenderMock1, AppenderMock2"
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
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                 |
  	| WARNING level message   |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  And Appender "AppenderMock2" has received the following messages in this order:
  	| message                 |
  	| WARNING level message   |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
    	

Scenario: 5. Log level test: ERROR level
  Given a Logger with name "wwwind.logging.test", log level "ERROR" and appenders "AppenderMock1, AppenderMock2"
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
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                 |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  And Appender "AppenderMock2" has received the following messages in this order:
  	| message                 |
  	| ERROR level message     |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
    
    
Scenario: 6. Log level test: CRITICAL level
  Given a Logger with name "wwwind.logging.test", log level "CRITICAL" and appenders "AppenderMock1, AppenderMock2"
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
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                 |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
  And Appender "AppenderMock2" has received the following messages in this order:
  	| message                 |
  	| CRITICAL level message  |
  	| ALERT level message     |
  	| EMERGENCY level message |
    

Scenario: 7. Log level test: ALERT level
  Given a Logger with name "wwwind.logging.test", log level "ALERT" and appenders "AppenderMock1, AppenderMock2"
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
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                 |
  	| ALERT level message     |
  	| EMERGENCY level message |
  And Appender "AppenderMock2" has received the following messages in this order:
  	| message                 |
  	| ALERT level message     |
  	| EMERGENCY level message |
    
Scenario: 8. Log level test: EMERGENCY level
  Given a Logger with name "wwwind.logging.test", log level "EMERGENCY" and appenders "AppenderMock1, AppenderMock2"
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
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                 |
  	| EMERGENCY level message |
  And Appender "AppenderMock2" has received the following messages in this order:
  	| message                 |
  	| EMERGENCY level message |

Scenario: 9. Log message simple string parameters parsing test
  Given a Logger with name "wwwind.logging.test", log level "INFO" and appenders "AppenderMock1"
  When the following log messages are sent to Logger "wwwind.logging.test":
  	| logLevel  | message                             | listed parameters |
  	| INFO      | A message with param1={}, param2={} | P1, P2            |
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                             |
  	| A message with param1=P1, param2=P2 |
  	
Scenario: 10. Log message simple string parameters parsing test with less params than expected
  Given a Logger with name "wwwind.logging.test", log level "INFO" and appenders "AppenderMock1"
  When the following log messages are sent to Logger "wwwind.logging.test":
  	| logLevel  | message                                                           | listed parameters |
  	| INFO      | A message with param1={}, param2={}, param3={} (which is missing) | P1, P2            |
  Then Appender "AppenderMock1" has received the following messages in this order:
  	| message                                                           |
  	| A message with param1=P1, param2=P2, param3={} (which is missing) |
  	