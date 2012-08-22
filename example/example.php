<?php


require_once("../src/svgtk/svgtk.php");
include "../src/SurfaceCalculator.php";
include "../src/SurfaceDrawer.php";


$sc = new SurfaceCalculator;

$sc->setHeight(55);
$sc->setEdges(1);
$sc->setToplength(20);
$sc->setBottomlength(100);

$parameters = $sc->calculateSurface();

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


?>
