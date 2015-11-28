<?php



require_once(__DIR__ . "/../vendor/autoload.php");

use EeWalk11\Minify\Config;
use EeWalk11\Minify\Minifier;
use EeWalk11\Minify\MinifyException;



/**
 * Write results.
 * @param bolean $pass True if a test passed, false if it failed.
 */
function results($pass)
{
	echo "<span class='" . ($pass ? "pass" : "fail") . "'>" . ($pass ? "ok" : "fail") . "</span>";
}



/**
 * Write a note about the groupsConfig.php file.
 */
function groupsNote()
{
	echo " <span class='note'>copy the groupConfig.php file from the test directory into the "
		. "minify directory</span>";
}



/**
 * Run all tests.
 */
function runTests()
{
	$n = 1;
	do
	{
		$func = "test$n";
		if(function_exists($func))
		{
			$func();
			$n++;
		}
		else
		{
			$n = false;
		}
	}
	while($n);

	echo "<p class='note'>Tests finished</p>";
}



function test1()
{
	echo "<p>Testing nonexistent file... ";
	$min = new Minifier;
	$min->addFile("nofile.js");
	try
	{
		$min->createUri();
		results(false);
	}
	catch(MinifyException $e)
	{
		results(true);
	}
	echo "</p>";
}

function test2()
{
	echo "<p>Testing addFile()... ";
	$min = new Minifier;
	$min->addFile("scripts/script1.js");
	$min->addFile("scripts/script2.js");
	results($min->createUri() == "/min/f=scripts/script1.js,scripts/script2.js");
	echo "</p>";
}

function test3()
{
	echo "<p>Testing addFiles()... ";
	$min = new Minifier;
	$min->addFiles("styles/style1.css");
	$min->addFile(null);
	$min->addFiles(false);
	$min->addFiles(["styles/style2.css", "styles/page/pagestyle.css"]);
	results(
		$min->createUri() == "/min/f=styles/style1.css,styles/style2.css,styles/page/pagestyle.css"
	);
	echo "</p>";
}

function test4()
{
	echo "<p>Testing base dir... ";
	$min = new Minifier;
	$min->setBase("scripts");
	$min->addFile("script1.js");
	results($min->createUri() == "/min/b=scripts&amp;f=script1.js");
	echo "</p>";
}

function test5()
{
	echo "<p>Testing redundant files... ";
	$min = new Minifier;
	$min->setBase("styles");
	$min->addFile("style1.css");
	$min->addFile("style1.css");
	$min->addFile("");
	results($min->createUri() == "/min/b=styles&amp;f=style1.css");
	echo "</p>";
}

function test6()
{
	echo "<p>Testing addGroup()... ";
	$min = new Minifier;
	$min->setBase("scripts");
	$min->addGroup("script1");
	try
	{
		results($min->createUri() == "/min/b=scripts&amp;g=script1");
	}
	catch(MinifyException $e)
	{
		results(false);
		groupsNote();
	}
	echo "</p>";
}

function test7()
{
	echo "<p>Testing addGroups()... ";
	$min = new Minifier;
	$min->addGroups(false);
	$min->addGroups(["script1", "script2"]);
	try
	{
		results($min->createUri() == "/min/g=script1,script2");
	}
	catch(MinifyException $e)
	{
		results(false);
		groupsNote();
	}
	echo "</p>";
}

function test8()
{
	echo "<p>Testing redundant groups... ";
	$min = new Minifier;
	$min->addGroup("style1");
	$min->addGroups(["style1", "style2"]);
	try
	{
		results($min->createUri() == "/min/g=style1,style2");
	}
	catch(MinifyException $e)
	{
		results(false);
		groupsNote();
	}
	echo "</p>";
}

function test9()
{
	echo "<p>Testing nonexistent group... ";
	$min = new Minifier;
	$min->addGroup("badgroup");
	try
	{
		$min->createUri();
		results(false);
	}
	catch(MinifyException $e)
	{
		results(true);
	}
}

function test10()
{
	echo "<p>Testing unset base... ";
	$min = new Minifier;
	$min->setBase("scripts");
	$min->addFile("scripts/script1.js");
	$min->setBase(false);
	results($min->createUri() == "/min/f=scripts/script1.js");
	echo "</p>";
}

function test11()
{
	echo "<p>Testing removeFile()... ";
	$min = new Minifier;
	$min->addFile("styles/style1.css");
	$min->addFile("testfile");
	results(
		!$min->removeGroup("testfile") && !$min->removeFile("test") && $min->removeFile("testfile")
		&& $min->createUri() == "/min/f=styles/style1.css"
	);
	echo "</p>";
}

function test12()
{
	echo "<p>Testing removeGroup()... ";
	$min = new Minifier;
	$min->addFile("styles/style1.css");
	$min->addGroup("testgroup");
	results(
		!$min->removeFile("testgroup") && !$min->removeGroup("test")
		&& $min->removeGroup("testgroup") && $min->createUri() == "/min/f=styles/style1.css"
	);
	echo "</p>";
}

function test13()
{
	echo "<p>Testing empty minifier... ";
	$min = new Minifier;
	$min->setBase("scripts");
	results($min->createUri() === false);
}

function test14()
{
	echo "<p>Testing config... ";
	Config::setOption("debug", false);
	$min = new Minifier;
	$min->addFile("badfile");
	results($min->createUri() == "/min/f=badfile");
	echo "</p>";

	Config::setOption("debug", true);
}

function test15()
{
	echo "<p>Testing full minifier... ";
	$min = new Minifier;
	$min->setBase("scripts");
	$min->addFiles("page/pagescript.js");
	$min->addGroup("script1");
	results($min->createUri() == "/min/b=scripts&amp;g=script1&amp;f=page/pagescript.js");
}

function test16()
{
	echo "<p>Testing removeFiles()... ";
	$min = new Minifier;
	$min->setBase("scripts");
	$min->addFiles(["script1.js", "script2.js", "page/pagescript.js"]);
	$arr = $min->removeFiles(
		["scripts/script1.js", "script2.js", "script2.js", "page/pagescript.js"]
	);
	$res = ["script2.js", "page/pagescript.js"];
	results(
		array_diff($arr, $res) === [] && array_diff($res, $arr) === []
		&& $min->createUri() == "/min/b=scripts&amp;f=script1.js"
	);
}

function test17()
{
	echo "<p>Testing removeGroups()... ";
	$min = new Minifier;
	$min->setBase("scripts");
	$min->addFiles(["script1.js"]);
	$min->addGroups(["script1", "script2", "style1"]);
	$arr = $min->removeGroups(
		["notagroup", "script2", "script2s", "style1"]
	);
	$res = ["script2", "style1"];
	results(
		array_diff($arr, $res) === [] && array_diff($res, $arr) === []
		&& $min->createUri() == "/min/b=scripts&amp;g=script1&amp;f=script1.js"
	);
}

function test18()
{
	echo "<p>Testing helper functions... ";
	\Minify\setBase("scripts", "scripts/");
	\Minify\addFiles("scripts", ["script1.js", "script2.js"]);
	\Minify\addGroup("styles", "style1");
	results(
			\Minify\createUri("scripts") == "/min/b=scripts&amp;f=script1.js,script2.js"
			&& \Minify\createUri("styles") == "/min/g=style1"
	);
	echo "</p>";
}



//Build page

?>
<html>
<head>
	<style>
		.pass {color: green;}
		.fail {color: red;}
		.note {color: blue;}
	</style>
</head>
<body>
	<?php runTests(); ?>
</body>
</html>


