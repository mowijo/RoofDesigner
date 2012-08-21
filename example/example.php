<?php


require_once("../src/svgtk/svgtk.php");
include "../src/SurfaceCalculator.php";
include "../src/SurfaceDrawer.php";


$sc = new SurfaceCalculator;

$sc->setHeight(100);
$sc->setEdges(3);
$sc->setToplength(25);
$sc->setBottomlength(40);

$parameters = $sc->calculateSurface();
var_dump($parameters);

if($parameters === false)
{
	die($sc->errorMessage()."\n");
}


$sd = new SurfaceDrawer();

if (! $sd->drawSurface($parameters))
{	
	die($sd->errorMessage()."\n");
}
else
{	
	$x = $sd->asXml();
	echo "\n\n".$x;
	file_put_contents("../../out.svg", $x);
}
/*

$svg = new SvgDocument("A4");
$p = $svg->createPath();
$path->setStart($a);


$svg->addChild($p);



$x = $svg->asXml();
echo "\n\n".$x;
file_put_contents("../../out.svg", $x);
*/

?>
