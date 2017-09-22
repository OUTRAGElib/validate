<?php


namespace OUTRAGElib\Validate\Tests;

require __DIR__."/../vendor/autoload.php";

use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \PHPUnit\Framework\TestCase;


class ConstraintRequiredTest extends TestCase
{
	/**
	 *	Test to see if required works
	 */
	public function testConditionValid()
	{
		$element = new Element();
		$element->addConstraint(new Required(true));
		
		$result = $element->test(1);
		
		$this->assertTrue($result);
	}
	
	
	/**
	 *	Test to see if required works
	 */
	public function testConditionInvalid()
	{
		$element = new Element();
		$element->addConstraint(new Required(true));
		
		$result = $element->test(null);
		
		$this->assertFalse($result);
	}
}