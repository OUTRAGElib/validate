<?php


namespace OUTRAGElib\Validate;

use \OUTRAGElib\Delegator\DelegatorTrait;
use \OUTRAGElib\Validate\Error\ErrorMessage;


class Value implements ErrorInterface
{
	/**
	 *	Let's use delegation here.
	 */
	use DelegatorTrait;
	
	
	/**
	 *	Implement error handling functionality
	 */
	use ErrorTrait;
	
	
	/**
	 *	Where does this pair sit on the family tree?
	 */
	public $tree = null;
	
	
	/**
	 *	What are the value(s) of this field?
	 */
	public $value = null;
	
	
	/**
	 *	What is the element that represents this particular field?
	 */
	public $element = null;
	
	
	/**
	 *	Let's get the name of this property, based off of the tree stored within.
	 */
	public function getter_name()
	{
		return self::compileTree($this->tree);
	}
	
	
	/**
	 *	Let's get the name of this item's parent structure.
	 */
	public function getter_prefix()
	{
		$tree = $this->tree;
		
		array_pop($tree);
		
		if(!$tree)
			return "";
		
		return self::compileTree($tree);
	}
	
	
	/**
	 *	Compiles a property tree structure into a string structure.
	 */
	public static function compileTree(array $tree)
	{
		$name = "";
		
		while(!$name && $name !== "0")
			$name = (string) array_shift($tree);
		
		if(count($tree))
			$name .= "[".implode("][", $tree)."]";
		
		return $name;
	}
	
	
	/**
	 *	Creates a new value pair, but with this element in some sort of root level.
	 */
	public function rebase($offset = null)
	{
		if($offset === null)
			$offset = count($this->tree) - 1;
		
		if(!$offset)
			return $this;
		
		$tree = array_slice($this->tree, $offset);
		
		if(!$tree)
			return null;
		
		$pair = new self();
		
		$pair->tree = $tree;
		$pair->element = &$this->element;
		$pair->value = &$this->value;
		
		return $pair;
	}
}