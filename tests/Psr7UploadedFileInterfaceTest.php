<?php


namespace OUTRAGElib\Validate\Tests;

use \OUTRAGElib\Validate\BufferElement\FileBufferElement;
use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementList;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;
use \Psr\Http\Message\UploadedFileInterface;


class Psr7UploadedFileInterfaceTest extends TestCase
{
	/**
	 *	The new way of doing things on here is to make sure everything
	 *	uses PSR 7's uploaded file interface
	 */
	public function testFilesystemFile()
	{
		$element = new FileBufferElement();
		$stream = $element->validate(__DIR__."/assets/sample.jpg");
		
		$this->assertInstanceOf(UploadedFileInterface::class, $stream);
	}
	
	
	/**
	 *	The new way of doing things on here is to make sure everything
	 *	uses PSR 7's uploaded file interface
	 */
	public function testRemoteFile()
	{
		$element = new FileBufferElement();
		$stream = $element->validate("https://ss.westie.sh/r5zI");
		
		$this->assertInstanceOf(UploadedFileInterface::class, $stream);
	}
}