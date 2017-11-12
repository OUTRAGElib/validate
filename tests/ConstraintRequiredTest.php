<?php


namespace OUTRAGElib\Validate\Tests;

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
	
	
	/**
	 *	Test to see if ->setRequired functionality works
	 *	(basically is a wrapper for required()...
	 */
	public function testElementSetRequired()
	{
		$required = true;
		
		$element = new Element();
		
		$this->assertEquals(0, count($element->getConstraints()));
		
		for($i = 0; $i < 2; ++$i)
			$element->setRequired($required);
		
		$this->assertEquals(1, count($element->getConstraints()));
		$this->assertEquals($required, $element->isRequired());
		
		$result = $element->test(null);
		
		$this->assertFalse($result);
	}
}