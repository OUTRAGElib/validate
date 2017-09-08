<?php

ini_set("xdebug.var_display_max_depth", 256);

require "../vendor/autoload.php";


use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\BufferElement\FileBuffer;
use \OUTRAGElib\Validate\BufferElement\StringBuffer;
use \OUTRAGElib\Validate\ElementList;


if(!empty($_POST))
{
	$template = new ElementList();

	$template->append("field1");
	$template->append("field2");
	$template->append("field3");
	$template->append((new Element("field4"))->setIsArray(true));
	
	$sub1 = new ElementList("bravo");
	$sub1->appendTo($template);
	
	$sub2 = new ElementList("delta");
	$sub2->append(new FileBuffer("upload"));
	$sub2->append(new FileBuffer("upload2"));
	$sub2->appendTo($sub1);
	
	$template->validate($_POST);
	$d = $template->getValues();
	
	var_dump(stream_get_contents($d["bravo"]["delta"]["upload"]));
	exit;
}


?>

<form method="post" enctype="multipart/form-data">
	<input type="file" name="bravo[delta][upload]" />
	<button name="hi" value="1">submit</button>
</form>