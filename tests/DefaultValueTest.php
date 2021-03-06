<?php


namespace OUTRAGElib\Validate\Tests;

use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \PHPUnit\Framework\TestCase;


class DefaultValueTest extends TestCase
{
	/**
	 *	Test to see if required works
	 */
	public function testConditionValid()
	{
		$element = new Element();
		$element->setDefault(1);
		$element->addConstraint(new Required(true));
		
		$result = $element->test(null);
		
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