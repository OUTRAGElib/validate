<?php


namespace OUTRAGElib\Validate\Tests;

require __DIR__."/../vendor/autoload.php";

use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementList;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;


class SingleDimensionTest extends TestCase
{
	/**
	 *	A test case to test the generation of a simple validation
	 *	structure
	 */
	public function testElementListConstruction()
	{
		$template = new ElementList();
		
		$template->append("alpha");
		$template->append("bravo");
		
		$this->assertNotEmpty($template->children);
		
		foreach($template->children as $child)
			$this->assertInstanceOf(ElementInterface::class, $child);
		
		return $template;
	}
	
	
	/**
	 *	Check to see if we're able to retrieve an element from the list
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListGetElement(ElementListInterface $template)
	{
		$this->assertInstanceOf(ElementInterface::class, $template->getElement("alpha"));
	}
	
	
	/**
	 *	A test case to check to see if we can add a simple OUTRAGElib
	 *	onto a new element via __call modification
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementAddConstraintViaCall(ElementListInterface $template)
	{
		$element = $template->getElement("alpha");
		$element->required(true);
		
		$this->assertNotEmpty($element->constraints);
		
		foreach($element->constraints as $constraint)
		{
			$this->assertInstanceOf(Required::class, $constraint);
			$this->assertTrue($constraint->test(1));
		}
	}
	
	
	/**
	 *	A test case to check to see if we can add a simple OUTRAGElib
	 *	onto a new element directly via a new object
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementAddConstraintViaClass(ElementListInterface $template)
	{
		$element = $template->getElement("bravo");
		$element->addConstraint(new Required(true));
		
		$this->assertNotEmpty($element->constraints);
		
		foreach($element->constraints as $constraint)
		{
			$this->assertInstanceOf(Required::class, $constraint);
			$this->assertTrue($constraint->test(1));
		}
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with empty values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationEmpty(ElementListInterface $template)
	{
		$input = [];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"alpha" => null,
			"bravo" => null,
		];
		
		$this->assertEquals($output, $template->getValues());
		
		$errors = [
			"alpha" => [
				"Value not supplied.",
			],
			"bravo" => [
				"Value not supplied.",
			],
		];
		
		$this->assertEquals($errors, $template->getErrors());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with incorrect values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationIncorrectValues(ElementListInterface $template)
	{
		$input = [
			"zulu" => 1,
		];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"alpha" => null,
			"bravo" => null,
		];
		
		$this->assertEquals($output, $template->getValues());
		
		$errors = [
			"alpha" => [
				"Value not supplied.",
			],
			"bravo" => [
				"Value not supplied.",
			],
		];
		
		$this->assertEquals($errors, $template->getErrors());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with partial values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationPartialValues(ElementListInterface $template)
	{
		$input = [
			"alpha" => 1,
		];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"alpha" => 1,
			"bravo" => null,
		];
		
		$this->assertEquals($output, $template->getValues());
		
		$errors = [
			"bravo" => [
				"Value not supplied.",
			],
		];
		
		$this->assertEquals($errors, $template->getErrors());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with correct values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationCorrectValues(ElementListInterface $template)
	{
		$input = [
			"alpha" => 1,
			"bravo" => 1,
		];
		
		$this->assertTrue($template->validate($input));
		
		$output = [
			"alpha" => 1,
			"bravo" => 1,
		];
		
		$this->assertEquals($output, $template->getValues());
		
		$errors = [];
		
		$this->assertEquals($errors, $template->getErrors());
	}
}