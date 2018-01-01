<?php


namespace OUTRAGElib\Validate\PHPStan;

use \OUTRAGElib\Validate\ElementInterface as ElementInterface;
use \PHPStan\Reflection\ClassReflection as PHPStanClassReflection;
use \PHPStan\Reflection\MethodsClassReflectionExtension as PHPStanMethodsClassReflectionExtension;
use \PHPStan\Reflection\MethodReflection as PHPStanMethodReflection;


class ElementMagicMethodReflection implements PHPStanMethodsClassReflectionExtension
{
	/**
	 *	Is this object instanceof 'Element' - and is the __call invokation valid?
	 */
	public function hasMethod(PHPStanClassReflection $reflection, string $method): bool
	{
		$native = $reflection->getNativeReflection();
		
		if($native->getName() === ElementInterface::class || $native->isSubclassOf(ElementInterface::class))
		{
			$matches = [];
			
			if(preg_match("/^(has|remove)([A-Za-z])$/", $method, $matches))
				return true;
			
			return true;
		}
		
		return false;
	}
	
	public function getMethod(PHPStanClassReflection $reflection, string $method): PHPStanMethodReflection
	{
		$native = $reflection->getNativeReflection();
		
		if($native->getName() === ElementInterface::class || $native->isSubclassOf(ElementInterface::class))
		{
			$matches = [];
			
			if(preg_match("/^(has)([A-Za-z])$/", $method, $matches))
				return new ElementHasConstraintMethodReflection($reflection, $method);
			
			if(preg_match("/^(remove)([A-Za-z])$/", $method, $matches))
				return new ElementRemoveConstraintMethodReflection($reflection, $method);
			
			return new ElementAddConstraintMethodReflection($reflection, $method);
		}
		
		return false;
	}
}