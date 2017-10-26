<?php


namespace OUTRAGElib\Validate\Tests;

require __DIR__."/../vendor/autoload.php";

use \OUTRAGElib\FileStream\FileInterface;
use \OUTRAGElib\FileStream\StreamInterface;
use \OUTRAGElib\Validate\BufferElement\FileBufferElement;
use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementList;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;


class FileBufferElementTest extends TestCase
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
		
		$buffer = new FileBufferElement("buffer");
		$buffer->required(true);
		
		$template->append($buffer);
		
		$this->assertNotEmpty($template->children);
		
		foreach($template->children as $child)
			$this->assertInstanceOf(FileBufferElement::class, $child);
		
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
			"buffer" => "https://ss.westie.sh/r5zI",
		];
		
		$this->assertTrue($template->validate($input));
		
		$values = $template->getValues();
		
		$this->assertInstanceOf(FileInterface::class, $values["buffer"]);
		$this->assertInstanceOf(StreamInterface::class, $values["buffer"]->getStream());
		
		# check the name of the file
		$metadata = $values["buffer"]->getStream()->getMetadata();
		
		$this->assertEquals("Screen Shot 2017-09-20 at 21.28.53.png", $values["buffer"]->getClientFilename());
		$this->assertEquals("Screen Shot 2017-09-20 at 21.28.53.png", basename($metadata["uri"]));
		
		# check the md5 hash of the file 
		$md5_hash_source = md5_file($input["buffer"]); # 57506d251613cb73c12aa947bedcfade
		$md5_hash_local = md5_file($metadata["uri"]);
		
		$this->assertEquals("57506d251613cb73c12aa947bedcfade", $md5_hash_source); 
		$this->assertEquals("57506d251613cb73c12aa947bedcfade", $md5_hash_local); 
	}
}