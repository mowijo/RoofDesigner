<?php


require_once("../src/svgtk/svgtk.php");
include "../src/SurfaceCalculator.php";
include "../src/SurfaceDrawer.php";


$sc = new SurfaceCalculator;

$sc->setHeight(40);
$sc->setEdges(1);
$sc->setToplength(0);
$sc->setBottomlength(60);

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
