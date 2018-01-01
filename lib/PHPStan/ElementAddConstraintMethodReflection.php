<?php


namespace OUTRAGElib\Validate\PHPStan;

use \OUTRAGElib\Validate\Element as Element;
use \PHPStan\Reflection\ClassReflection as PHPStanClassReflection;
use \PHPStan\Reflection\MethodReflection as PHPStanMethodReflection;
use \PHPStan\Reflection\ParameterReflection as PHPStanParameterReflection;
use \PHPStan\Type\ObjectType as PHPStanObjectType;
use \PHPStan\Type\Type as PHPStanType;


class ElementAddConstraintMethodReflection extends MethodReflection
{
	/**
	 *	Return... itself!?
	 */
	public function getPrototype(): PHPStanMethodReflection
	{
		return null;
	}
	
	
	/**
	 *	What is the full name of this function?
	 */
	public function getName(): string
	{
		return $this->reflection->getNativeReflection()->getNamespaceName()."\\".$this->method;
	}
	
	
	/**
	 *	Is this method varadic?
	 */
	public function isVariadic(): bool
	{
		return true;
	}
	
	
	/**
	 *	What parameters are passed to this function?
	 *
	 *	@return \PHPStan\Reflection\ParameterReflection[]
	 */
	public function getParameters(): array
	{
		return [];
	}
	
	
	/**
	 *	What is the return type?
	 */
	public function getReturnType(): PHPStanType
	{
		return new PHPStanObjectType(Element::class, false);
	}
}