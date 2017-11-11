<?php


namespace OUTRAGElib\Validate\Tests;

use \OUTRAGElib\Validate\BufferElement\FileBufferElement;
use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementList;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;


class FileBufferElementValidationTest extends TestCase
{
	/**
	 *	What is the file we're looking at?
	 */
	protected const TEST_FILE_URL = "https://ss.westie.sh/r5zI";
	
	
	/**
	 *	What is the correct MD5 hash of the file we're looking at?
	 */
	protected const TEST_FILE_MD5 = "57506d251613cb73c12aa947bedcfade";
	
	
	/**
	 *	Zend MD5 test
	 */
	public function testFileBufferZendMD5Valid()
	{
		$element = new FileBufferElement();
		$element->addConstraint(new \Zend\Validator\File\Md5(self::TEST_FILE_MD5));
		
		$result = $element->test(self::TEST_FILE_URL);
		
		$this->assertTrue($result);
	}
	
	
	/**
	 *	Zend MD5 test
	 */
	public function testFileBufferZendMD5Invalid()
	{
		$element = new FileBufferElement();
		$element->addConstraint(new \Zend\Validator\File\Md5("00000000000000000000000000000000"));
		
		$result = $element->test(self::TEST_FILE_URL);
		
		$this->assertFalse($result);
	}
	
	
	/**
	 *	Zend size test
	 */
	public function testFileBufferZendSizeValid()
	{
		$element = new FileBufferElement();
		$element->addConstraint(new \Zend\Validator\File\Size([ "min" => "10kB", "max" => "4MB" ]));
		
		$result = $element->test(self::TEST_FILE_URL);
		
		$this->assertTrue($result);
	}
	
	
	/**
	 *	Zend size test
	 */
	public function testFileBufferZendSizeInvalid()
	{
		$element = new FileBufferElement();
		$element->addConstraint(new \Zend\Validator\File\Size([ "min" => "1", "max" => "2" ]));
		
		$result = $element->test(self::TEST_FILE_URL);
		
		$this->assertFalse($result);
	}
	
	
	/**
	 *	Symfony file test
	 */
	public function testFileBufferSymfonyValid()
	{
		$element = new FileBufferElement();
		
		$settings = [
			"maxSize" => "1M",
			
			"mimeTypes" => [
				"image/png",
			],
		];
		
		$element->addConstraint(new \Symfony\Component\Validator\Constraints\File($settings));
		
		$result = $element->test(self::TEST_FILE_URL);
		
		$this->assertTrue($result);
	}
	
	
	/**
	 *	Symfony file test
	 */
	public function testFileBufferSymfonyInvalid()
	{
		$element = new FileBufferElement();
		
		$settings = [
			"maxSize" => "1",
			
			"mimeTypes" => [
				"image/gif",
			],
		];
		
		$element->addConstraint(new \Symfony\Component\Validator\Constraints\File($settings));
		
		$result = $element->test(self::TEST_FILE_URL);
		
		$this->assertFalse($result);
	}
}