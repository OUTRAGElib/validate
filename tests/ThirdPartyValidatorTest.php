<?php


namespace OUTRAGElib\Validate\Tests;

use \Closure;
use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\ConstraintWrapper;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementList;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;
use \Symfony\Component\Validator\Constraints as ValidatorSymfony;
use \Zend\Validator as ValidatorZend;


class ThirdPartyValidatorTest extends TestCase
{
	/**
	 *	A test case to test the generation of a simple validation
	 *	structure
	 *
	 *	@covers \OUTRAGElib\Validate\Component
	 *	@covers \OUTRAGElib\Validate\Element
	 *	@covers \OUTRAGElib\Validate\ElementList
	 */
	public function testElementListConstruction()
	{
		$template = new ElementList();
		
		$template->append("symfony");
		$template->append("zend");
		$template->append("callback");
		
		$this->assertNotEmpty($template->children);
		
		foreach($template->children as $child)
			$this->assertInstanceOf(ElementInterface::class, $child);
		
		return $template;
	}
	
	
	/**
	 *	Add on a symfony validator (note to self: anything that is suffixed by
	 *	test is completely and utterly the wrong thing!
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
	public function testElementAddConstraintSymfony(ElementListInterface $template)
	{
		$this->assertTrue($template->hasElement("symfony"));
		
		$wrapper = new ConstraintWrapper\Symfony();
		$template->addConstraintWrapper($wrapper);
		
		$element = $template->getElement("symfony");
		$element->addConstraint(new ValidatorSymfony\Regex("/^abcd$/"));
		
		$constraints = $element->getConstraints();
		
		$this->assertNotEmpty($constraints);
		$this->assertNotEmpty($wrapper->filterConstraints($constraints));
		
		foreach($wrapper->filterConstraints($constraints) as $constraint)
			$this->assertInstanceOf(ValidatorSymfony\Regex::class, $constraint);
	}
	
	
	/**
	 *	Add on a zend validator (thankfully this is nice and easy...)
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
	public function testElementAddConstraintZend(ElementListInterface $template)
	{
		$this->assertTrue($template->hasElement("zend"));
		
		$wrapper = new ConstraintWrapper\Zend();
		$template->addConstraintWrapper($wrapper);
		
		$element = $template->getElement("zend");
		$element->addConstraint(new ValidatorZend\Regex("/^abcd$/"));
		
		$constraints = $element->getConstraints();
		
		$this->assertNotEmpty($constraints);
		$this->assertNotEmpty($wrapper->filterConstraints($constraints));
		
		foreach($wrapper->filterConstraints($constraints) as $constraint)
			$this->assertInstanceOf(ValidatorZend\Regex::class, $constraint);
	}
	
	
	/**
	 *	Add on a zend validator (thankfully this is nice and easy...)
	 *
	 *	@covers \OUTRAGElib\Validate\Component
	 *	@covers \OUTRAGElib\Validate\Constraint\Required
	 *	@covers \OUTRAGElib\Validate\ConstraintAbstract
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Callback
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\OUTRAGElib
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Symfony
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Zend
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapper\Callback
	 *	@covers \OUTRAGElib\Validate\ConstraintWrapperAbstract
	 *	@covers \OUTRAGElib\Validate\Element
	 *	@covers \OUTRAGElib\Validate\ElementList
	 *	@covers \OUTRAGElib\Validate\Value
	 *	@covers \OUTRAGElib\Validate\ValueBuilder
	 *	@depends testElementListConstruction
	 */
	public function testElementAddConstraintCallback(ElementListInterface $template)
	{
		$this->assertTrue($template->hasElement("callback"));
		
		$wrapper = new ConstraintWrapper\Callback();
		$template->addConstraintWrapper($wrapper);
		
		$element = $template->getElement("callback");
		
		$element->addConstraint(function($input)
		{
			return preg_match("/^abcd$/", $input);
		});
		
		$constraints = $element->getConstraints();
		
		$this->assertNotEmpty($constraints);
		$this->assertNotEmpty($wrapper->filterConstraints($constraints));
		
		foreach($wrapper->filterConstraints($constraints) as $constraint)
			$this->assertInstanceOf(Closure::class, $constraint);
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
			"symfony" => null,
			"zend" => null,
			"callback" => null,
		];
		
		$this->assertEquals($output, $template->getValues());
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
			"symfony" => "efg",
			"zend" => "xyz",
			"callback" => "ghj",
		];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"symfony" => "efg",
			"zend" => "xyz",
			"callback" => "ghj",
		];
		
		$this->assertEquals($output, $template->getValues());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with partial values
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
	public function testElementListValidationPartialValues(ElementListInterface $template)
	{
		$input = [
			"symfony" => "abcd",
		];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"symfony" => "abcd",
			"zend" => null,
			"callback" => null,
		];
		
		$this->assertEquals($output, $template->getValues());
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
			"symfony" => "abcd",
			"zend" => "abcd",
			"callback" => "abcd",
		];
		
		$this->assertTrue($template->validate($input));
		
		$output = [
			"symfony" => "abcd",
			"zend" => "abcd",
			"callback" => "abcd",
		];
		
		$this->assertEquals($output, $template->getValues());
	}
}