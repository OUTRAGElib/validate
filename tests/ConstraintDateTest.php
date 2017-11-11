<?php


namespace OUTRAGElib\Validate\Tests;

use \OUTRAGElib\Validate\Constraint\Date;
use \OUTRAGElib\Validate\Element;
use \PHPUnit\Framework\TestCase;


class ConstraintDateTest extends TestCase
{
	/**
	 *	Test to see if required works
	 */
	public function testConditionValid()
	{
		$element = new Element();
		$element->addConstraint(new Date("Y-m-d"));
		
		$result = $element->test("1992-11-20");
		
		$this->assertTrue($result);
	}
	
	
	/**
	 *	Test to see if required works
	 */
	public function testConditionInvalid()
	{
		$element = new Element();
		$element->addConstraint(new Date("Y-m-d"));
		
		$result = $element->test("20/11/1992");
		
		$this->assertFalse($result);
	}
}