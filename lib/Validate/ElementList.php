<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \Traversable;
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
		# clear errors on the root only - there are no
		# requirements to do so on either child elements or
		# child element lists as errors are either stored on
		# the element (which is cloned) or on the root 
		# element list
		$this->errors = [];
		
		# do our validation on the children
		$this->passed = $input;
		$this->performValidationIteration($input);
		$this->passed = [];
		
		return count($this->errors) == 0;
	}
	
	
	/**
	 *	Wrapper to perform validation and return some values, if needed.
	 */
	public function performValidationIteration($input, $tree = [])
	{
		if(!is_array($input))
		{
			if($input instanceof Traversable)
				$input = iterator_to_array($input);
		}
		
		if(!is_array($input))
			return $this->values = array();
		
		if(method_exists($this, "prevalidate"))
		{
			if($this->prevalidate($input) === false)
				return $this->values = array();
		}
		
		return $this->values = $this->iterate($input, $tree);
	}
	
	
	/**
	 *	Iterate through a set of values and do the validation.
	 */
	protected function iterate($input, $tree = [])
	{
		# now for the fun bit of iterating through this mess and doing our validation
		$offset = count($tree);
		$pairs = [];
				
		# iterate through our defined elements
		foreach($this->children as $element)
		{
			$tree[] = $element;
			
			# it's probably a good idea to locate the actual value we want to
			# manipulate here
			$pointer = $input;
			
			if(!is_null($pointer) && $count = count($tree))
			{
				for($i = $offset; $i < $count; ++$i)
				{
					$name = (string) $tree[$i];
					
					if(!$name)
						continue;
					
					if($i == $count)
						break;
					
					if(isset($pointer[$name]))
					{
						$pointer = &$pointer[$name];
						continue;
					}
					
					$pointer = null;
					break;
				}
			}
			
			# do different things depending on whether this is a template - or not
			$pair = new Value();
			
			$pair->tree = $tree;
			$pair->element = $element;
			
			if($element instanceof ElementList)
			{
				if($element->is_array)
				{
					$pair->value = [];
					
					if(is_array($pointer))
					{
						foreach($pointer as $key => $value)
						{
							$tree[] = $key;
							$pair->value[$key] = $element->duplicate()->performValidationIteration($value, $tree);
							
							array_pop($tree);
						}
					}
				}
				else
				{
					$pair->value = $element->duplicate()->performValidationIteration($pointer ?: [], $tree);
				}
				
				$pairs[] = $pair;
			}
			else
			{
				if($element->is_array)
				{
					if(is_array($pointer) && count($pointer) > 0)
					{
						foreach($pointer as $key => $value)
						{
							$copy = clone $pair;
							$copy->tree[] = $key;
							$copy->value = $element->validate($value, $copy);
							
							$pairs[] = $copy;
						}
					}
					else
					{
						$pair->value = [];
						$pairs[] = $pair;
					}
				}
				else
				{
					$pair->value = $element->validate($pointer, $pair);
					$pairs[] = $pair;
				}
			}
			
			unset($pair);
			unset($pointer);
			
			array_pop($tree);
		}
		
		return $pairs;
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