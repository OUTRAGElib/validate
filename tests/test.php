<?php


ini_set("xdebug.var_display_max_depth", 256);

require "../vendor/autoload.php";


$whoops = new \Whoops\Run();
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


use \OUTRAGElib\Validate\BufferElement\FileBufferElement;
use \OUTRAGElib\Validate\BufferElement\StringBufferElement;
use \OUTRAGElib\Validate\Constraint\Required;
use \OUTRAGElib\Validate\ConstraintWrapper;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\ElementList;


$template = new ElementList();

$field1 = new Element("field1");
$field1->required(true);
$field1->addConstraint(function($input) { return true; });
$field1->addConstraint(new \Symfony\Component\Validator\Constraints\Length([ "min" => 10 ]));
$field1->addConstraint(new Required(true));
$field1->addConstraint(new \Zend\Validator\Regex("/^bb$/"));

$template->append($field1);
$template->append("field2");
$template->append("field3");
$template->append((new Element("field4"))->setIsArray(true));


$sub1 = new ElementList("bravo");

$sub1->append("field1");
$sub1->append("field2");
$sub1->append("field3");
$sub1->append((new Element("field4"))->setIsArray(true));

$sub1->appendTo($template);


$sub2 = new ElementList("delta");
$sub2->setIsArray(true);

$sub2->append("field1");
$sub2->append("field2");
$sub2->append("field3");
$sub2->append((new StringBufferElement("field4"))->required(true));

$sub2->appendTo($sub1);


$template->validate([
    "field1" => "bbbb",
    "field2" => 1,
    "field3" => 1,
    "field4" => [ 1 ],
    
    "bravo" => [
        "field1" => 19,
        "field2" => 19,
        "field3" => 19,
        "field4" => [ 19 ],
        
        "delta" => [
            [
                "field1" => 2,
                "field2" => 2,
                "field3" => 2,
            ],
            [
                "field1" => 31,
                "field2" => 31,
                "field3" => 31,
            ],
            [
                "field1" => 17,
                "field2" => 17,
                "field3" => 17,
            ],
            [
                "field1" => 76,
                "field2" => 76,
                "field3" => 76,
                "field4" => 99999,
            ],
        ]
    ],
]);

var_dump($template->getValues());
exit;