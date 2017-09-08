<?php


ini_set("xdebug.var_display_max_depth", 256);

require "../vendor/autoload.php";


use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\BufferElement\FileBuffer;
use \OUTRAGElib\Validate\BufferElement\StringBuffer;
use \OUTRAGElib\Validate\ElementList;


$template = new ElementList();

$template->append("field1");
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
$sub2->append((new StringBuffer("field4"))->required(false));

$sub2->appendTo($sub1);


$template->validate([
    "field1" => 1,
    "field2" => 1,
    "field3" => 1,
    "field4" => [ 1 ],
    
    "bravo" => [
        "field1" => 1,
        "field2" => 1,
        "field3" => 1,
        "field4" => [ 1 ],
        
        "delta" => [
            [
                "field1" => 1,
                "field2" => 1,
                "field3" => 1,
            ],
            [
                "field1" => 1,
                "field2" => 1,
                "field3" => 1,
            ],
            [
                "field1" => 1,
                "field2" => 1,
                "field3" => 1,
            ],
            [
                "field1" => 1,
                "field2" => 1,
                "field3" => 1,
                "field4" => "ddd",
            ],
        ]
    ],
]);

var_dump($template->getValues());
exit;