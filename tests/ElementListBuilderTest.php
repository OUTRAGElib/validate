<?php


namespace OUTRAGElib\Validate\Tests;

require __DIR__."/../vendor/autoload.php";

use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\Validate\ElementListBuilder;
use \OUTRAGElib\Validate\ElementListInterface;
use \PHPUnit\Framework\TestCase;


class ElementListBuilderTest extends TestCase
{
	/**
	 *	A test case to test the generation of a simple validation
	 *	structure
	 */
	public function testElementListConstruction()
	{
		$builder = new ElementListBuilder();
		
		$template = $builder->getTemplate([
			"a[b][c]" => [],
			
			"d.e.g.f" => [
				new \Symfony\Component\Validator\Constraints\Length([ "min" => 13 ]),
			],
			
			"d[e][g][c]" => [
				new Required(true),
			],
			
			"d.e.f" => [
				new \Zend\Validator\StringLength([ "min" => 12 ]),
			],
		]);
		
		$this->assertInstanceOf(ElementListInterface::class, $template);
		
		return $template;
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with empty values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationEmpty(ElementListInterface $template)
	{
		$input = [];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"a" => [
				"b" => [
					"c" => null,
				]
			],
			
			"d" => [
				"e" => [
					"f" => null,
					"g" => [
						"f" => null,
						"c" => null,
					],
				],
			],
		];
		
		$this->assertEquals($output, $template->getValues());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with incorrect values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationIncorrectValues(ElementListInterface $template)
	{
		$input = [
			"z" => 1,
		];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"a" => [
				"b" => [
					"c" => null,
				]
			],
			
			"d" => [
				"e" => [
					"f" => null,
					"g" => [
						"f" => null,
						"c" => null,
					],
				],
			],
		];
		
		$this->assertEquals($output, $template->getValues());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with partial values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationPartialValues(ElementListInterface $template)
	{
		$input = [
			"a" => [
				"b" => [
					"c" => "#",
				]
			],
			
			"d" => [
				"e" => [
					"f" => str_repeat("#", 5),
					"g" => [
						"f" => str_repeat("#", 5),
						"c" => str_repeat("#", 5),
					],
				],
			],
		];
		
		$this->assertFalse($template->validate($input));
		
		$output = [
			"a" => [
				"b" => [
					"c" => "#",
				]
			],
			
			"d" => [
				"e" => [
					"f" => str_repeat("#", 5),
					"g" => [
						"f" => str_repeat("#", 5),
						"c" => str_repeat("#", 5),
					],
				],
			],
		];
		
		$this->assertEquals($output, $template->getValues());
	}
	
	
	/**
	 *	Now perform a test to see if we can validate this with correct values
	 *
	 *	@depends testElementListConstruction
	 */
	public function testElementListValidationCorrectValues(ElementListInterface $template)
	{
		$input = [
			"a" => [
				"b" => [
					"c" => "#",
				]
			],
			
			"d" => [
				"e" => [
					"f" => str_repeat("#", 12),
					"g" => [
						"f" => str_repeat("#", 13),
						"c" => str_repeat("#", 13),
					],
				],
			],
		];
		
		$this->assertTrue($template->validate($input));
		
		$output = [
			"a" => [
				"b" => [
					"c" => "#",
				]
			],
			
			"d" => [
				"e" => [
					"f" => str_repeat("#", 12),
					"g" => [
						"f" => str_repeat("#", 13),
						"c" => str_repeat("#", 13),
					],
				],
			],
		];
		
		$this->assertEquals($output, $template->getValues());
	}
}