<?php


namespace OUTRAGElib\Validate\Transformer;

use \Exception;
use \OUTRAGElib\Validate\TransformerAbstract;


class StringModifier extends TransformerAbstract
{
	/**
	 *	List of modes that this transformer supports.
	 */
	const REPLACE = "replace";
	const PREFIX = "prefix";
	const SUFFIX = "suffix";
	
	
	/**
	 *	What string will be helping us along here?
	 */
	protected $string = null;
	
	
	/**
	 *	What mode are we modifying in?
	 */
	protected $mode = null;
	
	
	/**
	 *	Called to set the arguments.
	 */
	public function init($string, $mode = self::REPLACE)
	{
		$this->string = $string;
		$this->mode = $mode;
	}
	
	
	/**
	 *	Transform the password into a nice little hash.
	 */
	public function transform($value)
	{
		switch($this->mode)
		{
			case self::REPLACE:
				return $this->string;
			break;
			
			case self::PREFIX:
				return $this->string.$value;
			break;
			
			case self::SUFFIX:
				return $value.$this->string;
			break;
		}
		
		return $value;
	}
}