<?php

namespace wwwind\logging\examples\namespaceB;

use wwwind\logging\examples\namespaceA\ClassA;

/**
 * This class extends ClassA - which means it would inherit both:
 * <ul>
 * <li>the (protected) static $_LOG variable and
 * <li>the (protected) static logger() method
 * </ul>
 * right?<p>
 * The point is that in similar cases you do not have to add (again) the static logger() method since that is
 * already inherited and it is using late static binding BUT YOU MUST override the static $_LOG variable! If you do not
 * do so and the $_LOG variable is inherited then it will be initialized with a Logger instance only once - and you
 * clearly do not want that!
 *
 * @author ironhawk
 *        
 */
class ClassAExtended extends ClassA {

	protected static $_LOG;

	public function __construct($name = null) {
		parent::__construct($name);
	}
	
	// let's add a __toString() method - objects with __toString() method are dumped in logs using that
	public function __toString() {
		return static::class . "[" . $this->name . "]";
	}

}