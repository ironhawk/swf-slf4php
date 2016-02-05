
Feature: LoggerFactory
  To be able to log messages
  As a Developer
  I can get a suitable Logger instance matching the configuration from the LoggerFactory

Background:
  Given a mocked Appender with name "namespace.a.b.AppenderMock"
  Given a mocked Appender with name "namespace.a.b.c.AppenderMock"
  Given a mocked Appender with name "namespace.a.b.c.ClassA.AppenderMock"
  Given a mocked Appender with name "ClassA.AppenderMock"
  Given a Logger with name "namespace.a.b", log level "INFO" and appenders "namespace.a.b.AppenderMock"
  Given a Logger with name "namespace.a.b.c", log level "WARNING" and appenders "namespace.a.b.c.AppenderMock"
  Given a Logger with name "namespace.a.b.c.ClassA", log level "ERROR" and appenders "namespace.a.b.c.ClassA.AppenderMock"
  Given a Logger with name "ClassA", log level "ALERT" and appenders "ClassA.AppenderMock"

  
Scenario: 1. Find best matching Logger
  When matching Logger asked from LoggerFactory for class name "namespace.a.b.OneClass"
  Then the returned Logger instance was created by cloning configured Logger instance with name "namespace.a.b"
  And the returned Logger instance name is "namespace.a.b.OneClass"

  When matching Logger asked from LoggerFactory for class name "namespace.a.b.c.OneClass"
  Then the returned Logger instance was created by cloning configured Logger instance with name "namespace.a.b.c"
  And the returned Logger instance name is "namespace.a.b.c.OneClass"

  When matching Logger asked from LoggerFactory for class name "namespace.a.b.c.ClassA"
  Then the returned Logger instance was created by cloning configured Logger instance with name "namespace.a.b.c.ClassA"
  And the returned Logger instance name is "namespace.a.b.c.ClassA"

Scenario: 2. No matching Logger found and no default Logger (a Logger without name) is configured - should return the special NullLogger
  When matching Logger asked from LoggerFactory for class name "d.e.ClassNonConfigured"
  Then the returned Logger instance is the special NullLogger
  
Scenario: 3. If default Logger (a Logger without a name) is configured it should be returned in case no matching Logger found
  Given a mocked Appender with name "DefaultAppenderMock"
  And a Logger with name "", log level "CRITICAL" and appenders "DefaultAppenderMock"

  When matching Logger asked from LoggerFactory for class name "d.e.ClassNonConfigured"
  Then the returned Logger instance was created by cloning configured Logger instance with name ""
  And the returned Logger instance name is "d.e.ClassNonConfigured"

  When matching Logger asked from LoggerFactory for class name "ClassB"
  Then the returned Logger instance was created by cloning configured Logger instance with name ""
  And the returned Logger instance name is "ClassB"

Scenario: 4. A match requires match from the beginning 
  When matching Logger asked from LoggerFactory for class name "a.b.c.ClassA"
  Then the returned Logger instance is the special NullLogger
  
Scenario: 5. Just simple class name case 
  When matching Logger asked from LoggerFactory for class name "ClassA"
  Then the returned Logger instance was created by cloning configured Logger instance with name "ClassA"
  And the returned Logger instance name is "ClassA"
  