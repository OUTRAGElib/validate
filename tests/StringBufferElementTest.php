<?php


namespace OUTRAGElib\Validate\Tests;

require __DIR__."/../vendor/autoload.php";

use \OUTRAGElib\Validate\BufferElement\StringBufferElement;
use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementList;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;


class StringBufferElementTest extends TestCase
{
	/**
	 *	A test case to test the generation of a simple validation
	 *	structure
	 */
	public function testElementListConstruction()
	{
		$template = new ElementList();
		
		$buffer = new StringBufferElement("buffer");
		$buffer->required(true);
		
		$template->append($buffer);
		
		$this->assertNotEmpty($template->children);
		
		foreach($template->children as $child)
			$this->assertInstanceOf(StringBufferElement::class, $child);
		
		return $template;
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
			"buffer" => null,
		];
		
		$this->assertEquals($output, $template->getValues());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with correct values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationCorrectValues(ElementListInterface $template)
	{
		$input = [
			"buffer" => "THIS IS A TEST BUFFER",
		];
		
		$this->assertTrue($template->validate($input));
		
		$values = $template->getValues();
		
		$this->assertInternalType("resource", $values["buffer"]);
		$this->assertEquals($input["buffer"], stream_get_contents($values["buffer"]));
	}
}