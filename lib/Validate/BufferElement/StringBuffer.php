<?php


namespace OUTRAGElib\Validate\BufferElement;

use \Exception;
use \OUTRAGElib\Validate\Element;


class StringBuffer extends Element
{
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		$result = parent::validate($input, $context);
		
		$fp = fopen("php://temp", "w+");
		
		if($result !== null)
			fwrite($fp, $result);
		
		rewind($fp);
		
		return $fp;
	}
}