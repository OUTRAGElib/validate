<?php


namespace OUTRAGElib\Validate;


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
		$tree = $template->parsePropertyName($index);
		
		if(!count($tree))
			return false;
		
		$object = $template;
		$branches = count($tree);
		
		for($i = 0; $i < $branches; ++$i)
		{
			if(strlen($tree[$i]) > 0)
			{
				# if we're in this section we're dealing with proceeding down
				# the element tree
				# sort of: a > b > c
				if($object->hasElement($tree[$i]))
				{
					# okay, we have an element
					$object = $object->getElement($tree[$i]);
					
					if($branches == ($i + 1) || $branches == ($i + 2) && !strlen($tree[$i + 1]))
					{
						if($object instanceof ElementList)
							throw new ElementListBuilderException("Unexpected ElementList");
						
						if(is_array($constraints))
						{
							foreach($constraints as $constraint)
								$object->addConstraint($constraint);
						}
					}
					
					if(($i + 1) < $branches && $object instanceof Element)
						throw new ElementListBuilderException("Unexpected Element");
				}
				else
				{
					# okay, we're creating an element
					if($branches == ($i + 1) || $branches == ($i + 2) && !strlen($tree[$i + 1]))
					{
						$object = (new Element($tree[$i]))->appendTo($object);
						
						if(is_array($constraints))
						{
							foreach($constraints as $constraint)
								$object->addConstraint($constraint);
						}
					}
					else
					{
						$object = (new ElementList($tree[$i]))->appendTo($object);
					}
				}
			}
			elseif(!strlen($tree[$i]))
			{
				# if we get to this point then we're dealing with an element that is
				# for some reason an array
				# sort of: a > b > c[] > d
				#
				# but the first thing we need to check is to see whether or not we're able
				# to perform such an action.
				
				# we can't have this on the first branch
				if($i == 0)
					throw new ElementListBuilderException("Cannot turn the root of the tree into an array");
				
				# this purposefully does not support having syntax as 'a[][]' as this is pointless, will
				# end up with something similar to { a: [[ b ]] } which is yeah, stupid
				if(!strlen($tree[$i - 1]))
					throw new ElementListBuilderException("Invalid syntax - arrays do not being configured twice");
				
				$object->setIsArray(true);
			}
		}
		
		return false;
	}
}