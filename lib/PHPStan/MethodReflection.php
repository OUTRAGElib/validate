<?php


namespace OUTRAGElib\Validate\PHPStan;

use \OUTRAGElib\Validate\Element as Element;
use \PHPStan\Reflection\ClassReflection as PHPStanClassReflection;
use \PHPStan\Reflection\MethodReflection as PHPStanMethodReflection;
use \PHPStan\Reflection\ParameterReflection as PHPStanParameterReflection;
use \PHPStan\Type\ObjectType as PHPStanObjectType;
use \PHPStan\Type\Type as PHPStanType;


abstract class MethodReflection implements PHPStanMethodReflection
{
	/**
	 *	Store a copy of the reflection
	 */
	protected $reflection = null;
	
	
	/**
	 *	Store a copy of the method name
	 */
	protected $method = null;
	
	
	/**
	 *	Construct this reflection
	 */
	public function __construct(PHPStanClassReflection $reflection, string $method)
	{
		$this->reflection = $reflection;
		$this->method = $method;
	}
	
	
	/**
	 *	Return the declaring class
	 */
	public function getDeclaringClass(): PHPStanClassReflection
	{
		return $this->reflection;
	}
	
	
	/**
	 *	Is this method static?
	 */
	public function isStatic(): bool
	{
		return false;
	}
	
	
	/**
	 *	Is this method private?
	 */
	public function isPrivate(): bool
	{
		return false;
	}
	
	
	/**
	 *	Is this method public?
	 */
	public function isPublic(): bool
	{
		return true;
	}
	
	
	/**
	 *	What is the full name of this function?
	 */
	public function getName(): string
	{
		return $this->reflection->getNativeReflection()->getNamespaceName()."\\".$this->method;
	}
}