<?php


namespace OUTRAGElib\Validate\Tests;

use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementList;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;


class TransformerTest extends TestCase
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
		
		$alpha = new Element("alpha");
		$alpha->appendTo($template);
		
		$alpha->addTransformer(function($input)
		{
			return intval($input) + 1;
		});
		
		$this->assertNotEmpty($template->children);
		
		foreach($template->children as $child)
			$this->assertInstanceOf(ElementInterface::class, $child);
		
		return $template;
	}
	
	
	/**
	 *	Test that substitutions/transformations happen as requested
	 *
	 *	@depends testElementListConstruction
	 */
	public function testTransformation(ElementList $template)
	{
		$input = [
			"alpha" => 1,
		];
		
		$this->assertTrue($template->validate($input));
		
		$output = [
			"alpha" => 2,
		];
		
		$this->assertEquals($output, $template->getValues());
	}
}