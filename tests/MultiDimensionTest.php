<?php


namespace OUTRAGElib\Validate\Tests;

use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementList;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;


class MultiDimensionTest extends TestCase
{
	/**
	 *	A test case to test the generation of a multi-dimensional
	 *	validation structure
	 *
	 *	@covers \OUTRAGElib\Validate\Component
	 *	@covers \OUTRAGElib\Validate\Component
	 *	@covers \OUTRAGElib\Validate\Constraint\Required
	 *	@covers \OUTRAGElib\Validate\ConstraintAbstract
	 *	@covers \OUTRAGElib\Validate\ConstraintFactory
	 *	@covers \OUTRAGElib\Validate\ConstraintFactory
	 *	@covers \OUTRAGElib\Validate\Element
	 *	@covers \OUTRAGElib\Validate\ElementList
	 */
	public function testElementListConstruction()
	{
		$template = new ElementList();
		
		$alpha = new ElementList("alpha");
		$alpha->append((new Element("bravo"))->required(true));
		$alpha->appendTo($template);
		
		$this->assertNotEmpty($template->children);
		
		foreach($template->children as $child)
		{
			$this->assertInstanceOf(ElementListInterface::class, $child);
			$this->assertNotEmpty($template->children);
			
			foreach($child->children as $grand_child)
				$this->assertInstanceOf(ElementInterface::class, $grand_child);
		}
		
		return $template;
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with empty values
	 *
	 *	@covers \OUTRAGElib\Validate\Component
	 *	@covers \OUTRAGElib\Validate\Constraint\Required
	 *	@covers \OUTRAGElib\Validate\ConstraintAbstract
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Callback
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\OUTRAGElib
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Symfony
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Zend
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapperAbstract
	 *	@covers \OUTRAGElib\Validate\Element
	 *	@covers \OUTRAGElib\Validate\ElementList
	 *	@covers \OUTRAGElib\Validate\Value
	 *	@covers \OUTRAGElib\Validate\ValueBuilder
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationEmpty(ElementListInterface $template)
	{
		$input = [];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"alpha" => [
				"bravo" => null,
			],
		];
		
		$this->assertEquals($output, $template->getValues());
		
		$errors = [
			"alpha[bravo]" => [
				"Value not supplied.",
			],
		];
		
		$this->assertEquals($errors, $template->getErrors());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with incorrect values
	 *
	 *	@covers \OUTRAGElib\Validate\Component
	 *	@covers \OUTRAGElib\Validate\Constraint\Required
	 *	@covers \OUTRAGElib\Validate\ConstraintAbstract
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Callback
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\OUTRAGElib
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Symfony
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Zend
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapperAbstract
	 *	@covers \OUTRAGElib\Validate\Element
	 *	@covers \OUTRAGElib\Validate\ElementList
	 *	@covers \OUTRAGElib\Validate\Value
	 *	@covers \OUTRAGElib\Validate\ValueBuilder
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationIncorrectValues(ElementListInterface $template)
	{
		$input = [
			"alpha" => [
				"zulu" => 1,
			]
		];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"alpha" => [
				"bravo" => null,
			],
		];
		
		$this->assertEquals($output, $template->getValues());
		
		$errors = [
			"alpha[bravo]" => [
				"Value not supplied.",
			],
		];
		
		$this->assertEquals($errors, $template->getErrors());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with correct values
	 *
	 *	@covers \OUTRAGElib\Validate\Component
	 *	@covers \OUTRAGElib\Validate\Constraint\Required
	 *	@covers \OUTRAGElib\Validate\ConstraintAbstract
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Callback
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\OUTRAGElib
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Symfony
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Zend
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapperAbstract
	 *	@covers \OUTRAGElib\Validate\Element
	 *	@covers \OUTRAGElib\Validate\ElementList
	 *	@covers \OUTRAGElib\Validate\Value
	 *	@covers \OUTRAGElib\Validate\ValueBuilder
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationCorrectValues(ElementListInterface $template)
	{
		$input = [
			"alpha" => [
				"bravo" => 1,
			]
		];
		
		$this->assertTrue($template->validate($input));
		
		$output = [
			"alpha" => [
				"bravo" => 1,
			],
		];
		
		$this->assertEquals($output, $template->getValues());
		
		$errors = [];
		
		$this->assertEquals($errors, $template->getErrors());
	}
}