<?php


namespace OUTRAGElib\Validate\Tests;

require __DIR__."/../vendor/autoload.php";

use \OUTRAGElib\Validate\Constraint\Prefix;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\Transformer\StringModifier;
use \PHPUnit\Framework\TestCase;


class TransformerStringModifierTest extends TestCase
{
	/**
	 *	Test to see if required works
	 */
	public function testTransformerValid()
	{
		$element = new Element();
		$element->addTransformer(new StringModifier("HELLO", StringModifier::PREFIX));
		$element->addConstraint(new Prefix("HELLO"));
		
		$result = $element->test("WORLD");
		
		$this->assertTrue($result);
	}
	
	
	/**
	 *	Test to see if required works
	 */
	public function testTransformerOutput()
	{
		$input = "yellow submarine";
		
		$prefix = "[[";
		$suffix = "]]";
		
		$element = new Element();
		$element->addTransformer(new StringModifier($prefix, StringModifier::PREFIX));
		$element->addTransformer(new StringModifier($suffix, StringModifier::SUFFIX));
		
		$output = $element->validate($input);
		
		$this->assertEquals($prefix.$input.$suffix, $output);
	}
}