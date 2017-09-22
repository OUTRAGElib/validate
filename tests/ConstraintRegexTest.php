<?php


namespace OUTRAGElib\Validate\Tests;

require __DIR__."/../vendor/autoload.php";

use \OUTRAGElib\Validate\Constraint\Regex;
use \OUTRAGElib\Validate\Element;
use \PHPUnit\Framework\TestCase;


class ConstraintRegexTest extends TestCase
{
	/**
	 *	Test to see if required works
	 */
	public function testConditionValid()
	{
		$element = new Element();
		$element->addConstraint(new Regex("/^HELLO WORLD$/"));
		
		$result = $element->test("HELLO WORLD");
		
		$this->assertTrue($result);
	}
	
	
	/**
	 *	Test to see if required works
	 */
	public function testConditionInvalid()
	{
		$element = new Element();
		$element->addConstraint(new Regex("/^HELLO WORLD$/"));
		
		$result = $element->test("HELLO NATION");
		
		$this->assertFalse($result);
	}
}