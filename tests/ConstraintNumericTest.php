<?php


namespace OUTRAGElib\Validate\Tests;

use \OUTRAGElib\Validate\Constraint\Numeric;
use \OUTRAGElib\Validate\Element;
use \PHPUnit\Framework\TestCase;


class ConstraintNumericTest extends TestCase
{
	/**
	 *	Test to see if required works
	 */
	public function testConditionValid()
	{
		$element = new Element();
		$element->addConstraint(new Numeric(true));
		
		$result = $element->test(1);
		
		$this->assertTrue($result);
	}
	
	
	/**
	 *	Test to see if required works
	 */
	public function testConditionInvalid()
	{
		$element = new Element();
		$element->addConstraint(new Numeric(true));
		
		$result = $element->test("z");
		
		$this->assertFalse($result);
	}
}