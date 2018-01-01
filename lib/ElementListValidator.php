<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \Traversable;


class ElementListValidator
{
	/**
	 *	Where is the template stored?
	 */
	protected $template = null;
	
	
	/**
	 *	Construct the iterator
	 */
	public function __construct(ElementListInterface $template)
	{
		$this->template = $template;
	}
	
	
	/**
	 *	Run the validator, somewhere
	 */
	public function validate($input)
	{
		# clear errors on the root only - there are no
		# requirements to do so on either child elements or
		# child element lists as errors are either stored on
		# the element (which is cloned) or on the root 
		# element list
		$this->template->errors = [];
		
		return $this->template->values = $this->iterate($this->template->duplicate(), $input);
	}
	
	
	/**
	 *	Iterate through a set of values and do the validation.
	 */
	protected function iterate(ElementListInterface $template, $input, $tree = [])
	{
		# a lovely bit of safeguarding - i'm going to be rather foolish here and
		# presume that if something implements traversable, AND has a function called
		# toArray, this would produce an array of the object we're wanting. let's hope
		# that this works as intended...
		if(!is_array($input))
		{
			if($input instanceof Traversable && method_exists($input, "toArray"))
				$input = call_user_func([ $input, "toArray" ]);
			elseif($input instanceof Traversable)
				$input = iterator_to_array($input);
			else
				return [];
		}
		
		# we might want to check to see whether or not we can actually validate
		# these values before we proceed - this is where the prevalidator comes in!
		if(method_exists($template, "prevalidate"))
		{
			if(call_user_func([ $template, "prevalidate" ], $input) === false)
				return [];
		}
		
		# this might be useful somewhere, so let's just populate this somewhere
		$template->passed = $input;
		
		# now for the fun bit of iterating through this mess and doing our validation
		$offset = count($tree);
		$pairs = [];
		
		# iterate through our defined elements
		foreach($template->children as $element)
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
							
							$copy = $element->duplicate();
							$copy->values = $this->iterate($copy, $value, $tree);
							
							$pair->value[$key] = $copy->values;
							
							array_pop($tree);
						}
					}
				}
				else
				{
					$copy = $element->duplicate();
					$copy->values = $this->iterate($copy, $pointer ?: [], $tree);
					
					$pair->value = $copy->values;
				}
				
				$pairs[] = $pair;
			}
			elseif($element instanceof Element)
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
			else
			{
				throw new Exception("Unknown validation type encountered");
			}
			
			unset($pair);
			unset($pointer);
			
			array_pop($tree);
		}
		
		# yeah this isn't needed anymore I suspect
		$template->passed = $input;
		
		return $pairs;
	}
}