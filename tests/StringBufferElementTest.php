<?php


namespace OUTRAGElib\Validate\Tests;

use \OUTRAGElib\FileStream\FileInterface;
use \OUTRAGElib\FileStream\StreamInterface;
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
	 *
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
	 *	@covers OUTRAGElib\Validate\BufferElement\StringBufferElement
	 *	@covers OUTRAGElib\Validate\BufferElementAbstract
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
	 *	@covers OUTRAGElib\Validate\BufferElement\StringBufferElement
	 *	@covers OUTRAGElib\Validate\BufferElementAbstract
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationCorrectValues(ElementListInterface $template)
	{
		$input = [
			"buffer" => "THIS IS A TEST BUFFER",
		];
		
		$this->assertTrue($template->validate($input));
		
		$values = $template->getValues();
		
		$this->assertInstanceOf(FileInterface::class, $values["buffer"]);
		$this->assertInstanceOf(StreamInterface::class, $values["buffer"]->getStream());
		
		$this->assertEquals($input["buffer"], $values["buffer"]->getStream()->getContents());
	}
}