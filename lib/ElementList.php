<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Structure\NotFoundException;


class ElementList extends Component implements ElementListInterface
{
	/**
	 *	We shall use this to store values generated from validated input methods.
	 */
	public $values = [];
	
	
	/**
	 *	This will store the initial values that are passed to this form when things are
	 *	being validated. Do not expect values to be here after validation has happened.
	 */
	public $passed = [];
	
	
	/**
	 *	Storing child elements
	 */
	public $children = [];
	
	
	/**
	 *	Please extend and return this - you'll probably be needing this to
	 *	create your definitions.
	 */
	public function __construct($component = null)
	{
		parent::__construct($component);
		
		if(method_exists($this, "rules"))
			$this->rules();
	}
	
	
	/**
	 *	Turn arrays into elements
	 */
	public function build($input)
	{
		$builder = new ElementListBuilder();
		$builder->build($this, $input);
		
		return $this;
	}
	
	
	/**
	 *	Retrieves an child on this template level.
	 */
	public function getElement($component)
	{
		if($this->children)
		{
			foreach($this->children as $child)
			{
				if($child->component == $component)
					return $child;
			}
		}
		
		return null;
	}
	
	
	/**
	 *	Checks if this template already has an element with the same name
	 *	already on this template level.
	 */
	public function hasElement($component)
	{
		if($this->children)
		{
			foreach($this->children as $child)
			{
				if($child->component == $component)
					return true;
			}
		}
		
		return false;
	}
	
	
	/**
	 *	Validate this template based on fields passed.
	 */
	public function validate($input)
	{
		# do our validation
		$iterator = new ElementListValidator($this);
		$iterator->validate($input);
		
		return count($this->errors) == 0;
	}
	
	
	/**
	 *	Retrieve values from the last validation attempt. Will return values regardless
	 *	of the validity of the last request.
	 */
	public function getValues()
	{
		return (new ValueBuilder($this->values))->getContext();
	}
	
	
	/**
	 *	Countable/ObjectListInterface: How many children does this template have?
	 */
	public function count()
	{
		return count($this->children);
	}
	
	
	/**
	 *	Iterator/ObjectListInterface interface: Returns the current accessed property.
	 */
	public function current()
	{
		return current($this->children);
	}
	
	
	/**
	 *	Iterator/ObjectListInterface interface: Returns the current accessed key.
	 */
	public function key()
	{
		return key($this->children);
	}
	
	
	/**
	 *	Iterator/ObjectListInterface interface: Returns the next property.
	 */
	public function next()
	{
		return next($this->children);
	}
	
	
	/**
	 *	Iterator/ObjectListInterface interface: Returns the previous property.
	 */
	public function rewind()
	{
		return reset($this->children);
	}
	
	
	/**
	 *	Iterator/ObjectListInterface interface: Checks if the internal array is valid.
	 */
	public function valid()
	{
		return current($this->children);
	}
	
	
	/**
	 *	ObjectListInterface interface: Called to return the first index of this array.
	 */
	public function first()
	{
		$set = array_slice($this->children, 0, 1, true);
		
		return isset($set[0]) ? $set[0] : null;
	}
	
	
	/**
	 *	ObjectListInterface interface: Called to return the last index of this array.
	 */
	public function last()
	{
		$set = array_slice($this->children, -1, 1, true);
		
		return isset($set[0]) ? $set[0] : null;
	}
	
	
	/**
	 *	ObjectListInterface interface: Push an item into the internal container.
	 */
	public function append($value)
	{
		if(is_string($value))
			$value = new Element($value);
		elseif($value instanceof Component == false)
			throw new Exception("Unable to add item to list - invalid type");
		
		if($value->parent)
			$value->parent->remove($value);
		
		$value->parent = $this;
		
		$this->children[] = $value;
		return $this;
	}
	
	
	/**
	 *	Removes a child element from this element.
	 */
	public function remove($value)
	{
		if($value instanceof Component == false)
			throw new Exception("Unable to add item to list - invalid type");
		
		$value->parent = null;
		
		foreach($this->children as $index => $child)
		{
			if($value === $child)
				unset($this->children[$index]);
		}
		
		$this->children = array_values($this->children);
		return $this;
	}
	
	
	/**
	 *	ObjectListInterface interface: Shift an item from the internal container.
	 */
	public function shift()
	{
		return array_shift($this->children);
	}
	
	
	/**
	 *	ObjectListInterface interface: Shift an item from the internal container.
	 */
	public function unshift($value)
	{
		if($value instanceof Component == false)
			throw new Exception("Unable to add item to list - invalid type");
		
		array_unshift($this->children, $value);
		return $this;
	}
	
	
	/**
	 *	ObjectListInterface interface: Removes and returns the last entry of the container.
	 */
	public function pop($value)
	{
		return array_pop($this->children);
	}
	
	
	/**
	 *	ObjectListInterface interface: Slices the internal container - this will not reset the pointer.
	 */
	public function slice($offset = 0, $length = null, $preserve_keys = false)
	{
		return array_slice($this->children, $offset, $length, $preserve_keys);
	}
	
	
	/**
	 *	ObjectListInterface interface: Splices the internal container - this will however reset the
	 *	internal pointer.
	 */
	public function splice($offset, $length = 0, $replacement = null)
	{
		return array_splice($this->children, $offset, $length, $replacement);
	}
	
	
	/**
	 *	ObjectListInterface interface: Iterator - but in a function.
	 */
	public function each(callable $callback = null)
	{
		foreach($this->children as $item)
		{
			if(call_user_func($callback, $item) === false)
				return $this;
		}
		
		return $this;
	}
	
	
	/**
	 *	ObjectListInterface interface: Return a map of this element's iterator.
	 */
	public function map(callable $callback = null)
	{
		return $callback ? array_map($callback, $this->children) : $this->children;
	}
	
	
	/**
	 *	ObjectListInterface interface: Called to shuffle the contents of this container.
	 */
	public function shuffle()
	{
		shuffle($this->children);
		return $this;
	}
	
	
	/**
	 *	ObjectListInterface interface: Creates a duplicate of this container
	 */
	public function duplicate()
	{
		$object = clone $this;
		
		foreach($object->children as $key => $child)
			$object->children[$key] = $child->duplicate();
		
		return $object;
	}
	
	
	/**
	 *	ObjectListInterface interface: Empties this container
	 */
	public function clear()
	{
		$this->children = [];
		return $this;
	}
	
	
	/**
	 *	ContainerInterface: does a property exist?
	 */
	public function has($property)
	{
		return array_key_exists($property, $this->children);
	}
	
	
	/**
	 *	ContainerInterface: retrieve a property if it does exist
	 */
	public function get($property)
	{
		if(!array_key_exists($property, $this->children))
			throw new NotFoundException("Invalid property '".$property."'");
		
		return $this->list[$property];
	}
	
	
	/**
	 *	Parse the property name/index to determine what we should name it
	 */
	public function parsePropertyName($index)
	{
		$tree = [];
		
		if(strstr($index, "[") !== false && strstr($index, "]") !== false)
		{
			$stack = [];
			
			preg_match("/^(.*?)(\[.*\])?$/", $index, $stack);
			
			if(!empty($stack[1]))
				$tree[] = $stack[1];
			
			if(!empty($stack[2]))
			{
				preg_match_all("/\[(.*?)\]/", $stack[2], $stack);
				
				if(!empty($stack[1]))
					$tree = array_merge($tree, $stack[1]);
			}
		}
		else
		{
			$tree = array_filter(explode(".", $index));
		}
		
		return $tree;
	}
}