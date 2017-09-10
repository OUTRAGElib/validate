<?php


ini_set("xdebug.var_display_max_depth", 256);


require "../vendor/autoload.php";


$whoops = new \Whoops\Run();
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


use \OUTRAGElib\Validate\ElementListBuilder;


$builder = new ElementListBuilder();

$template = $builder->getTemplate([
    "a[b][c]" => [
        new \Symfony\Component\Validator\Constraints\Length([ "min" => 10 ])
    ],
    
    "d.e.g.f" => [
        new \Symfony\Component\Validator\Constraints\Length([ "min" => 13 ])
    ],
    
    "d[e][g][c]" => [
        new \Symfony\Component\Validator\Constraints\Length([ "min" => 13 ])
    ],
    
    "d.e.f" => [
        new \Symfony\Component\Validator\Constraints\Length([ "min" => 12 ])
    ],
]);

$template->validate([
    "a" => [
        "b" => [
            "c" => str_repeat("a", 9),
        ]
    ],
    
    "d" => [
        "e" => [
            "f" => str_repeat("a", 9),
            "g" => [
                "f" => str_repeat("a", 9),
                "c" => str_repeat("a", 9),
            ],
        ]
    ],
]);

var_dump($template->getValues(), $template->getErrors());
exit;