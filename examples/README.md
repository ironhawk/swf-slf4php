# Notes for examples

These are just simple quick examples of how to setup and use logging facility.

## The examples

Examples are placed in folders. Name of folders show you what the example is about.

Since this Logging facility is designed to use the Loggers from classes (in OOP model, not flat PHP) examples need to have classes
to demonstrate the usage. These classes are defined in the root folder, they are: `ClassA`, `ClassB` and `ClassASubclass`

Examples are:
  * **json based config**  
  Shows you how to quickly configure the logging facility using a json based config file
  * **php based config**  
  Shows you how to configure the logging facility from PHP code making using the advantage of config builder based approach  
  * **handling inheritance**  
  Preferred way of storing a Logger which was created for a particular class (once!!) is storing it in `protected static $_LOG` variable.
  But what will happen in subclasses..?? They are different classes than their super class... This example show you the right way of
  usage through the example of attached `ClassA` and `ClassASubclass` classes.  
  
  
  
## Running examples

Each example folder contains a file `run-example.php`. Just run it to see the example in action!