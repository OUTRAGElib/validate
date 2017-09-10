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
	$sub2->append(new FileBufferElement("upload"));
	$sub2->append(new FileBufferElement("upload2"));
	$sub2->appendTo($sub1);
	
	$_POST["bravo"]["delta"]["upload"] = "https://ss.westie.sh/W7aa";
	$_POST["bravo"]["delta"]["upload2"] = "https://assets-cdn.github.com/assets/github-980cd404854e87ee88b12c7281a0875365a8f966c834dc30f3f656011f7f4df6.css";
	
	$template->validate($_POST);
	$d = $template->getValues();
	
	var_dump(stream_get_meta_data($d["bravo"]["delta"]["upload"])["uri"]);
	
	exit;
}


?>

<form method="post" enctype="multipart/form-data">
	<input type="file" name="bravo[delta][upload]" />
	<button name="hi" value="1">submit</button>
</form>