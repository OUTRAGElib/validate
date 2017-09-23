<?php


namespace OUTRAGElib\Validate\Tests;

require __DIR__."/../vendor/autoload.php";

use \OUTRAGElib\Validate\Constraint\Numeric;
use \OUTRAGElib\Validate\Element;
use \PHPUnit\Framework\TestCase;


class ConstraintTest extends TestCase
{
	/**
	 *	Test to see if shorthand stuff works
	 */
	public function testConditionValid()
	{
		$element = new Element();
		$element->required(true);
		
		$this->assertFalse($element->test(null));
		
		$element->required(false);
		
		$this->assertTrue($element->test(null));
	}
}