<?php


namespace OUTRAGElib\Validate;

use \Exception;


class ElementListBuilder
{
	/**
	 *	Begin building things!
	 */
	public function getTemplate($input = [])
	{
		$template = new ElementList();
		
		foreach($input as $index => $constraints)
			$this->parseConstraint($template, $index, $constraints);
		
		return $template;
	}
	
	
	/**
	 *	Do things to our element list...
	 */
	protected function parseConstraint(ElementList $template, $index, $constraints)
	{
		$tree = $this->parsePropertyName($index);
		
		if(!count($tree))
			return false;
		
		$object = $template;
		$branches = count($tree);
		
		for($i = 0; $i < $branches; ++$i)
		{
			if($object->hasElement($tree[$i]))
			{
				$object = $object->getElement($tree[$i]);
				
				if(($i + 1) == $branches)
				{
					if($object instanceof ElementList)
						throw new Exception("Unexpected ElementList");
					
					if(is_array($constraints))
					{
						foreach($constraints as $constraint)
							$object->addConstraint($constraint);
					}
					
					return true;
				}
				
				if(($i + 1) < $branches && $object instanceof Element)
					throw new Exception("Unexpected Element");
			}
			else
			{
				if(($i + 1) == $branches)
				{
					$object = (new Element($tree[$i]))->appendTo($object);
					
					if(is_array($constraints))
					{
						foreach($constraints as $constraint)
							$object->addConstraint($constraint);
					}
					
					return true;
				}
				else
				{
					$object = (new ElementList($tree[$i]))->appendTo($object);
				}
			}
		}
		
		return false;
	}
	
	
	/**
	 *	Parse the property name/index to determine what we should name it
	 */
	protected function parsePropertyName($index)
	{
		$tree = [];
		
		if(strstr($index, "[") !== false && strstr($index, "]") !== false)
		{
			$stack = [];
			
			parse_str($index, $stack);
			
			if(is_array($stack))
			{
				do
				{
					$tree[] = key($stack);
					$stack = array_pop($stack);
				}
				while(is_array($stack));
			}
		}
		else
		{
			$tree = array_filter(explode(".", $index));
		}
		
		return $tree;
	}
}