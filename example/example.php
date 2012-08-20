<?php


require_once("../src/svgtk/svgtk.php");
include "../src/SurfaceCalculator.php";
include "../src/SurfaceDrawer.php";


$sc = new SurfaceCalculator;

$sc->setHeight(50);
$sc->setEdges(4);
$sc->setToplength(0);
$sc->setBottomlength(30);

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
	echo $x;
	file_put_contents("../../out.svg", $x);
}

?>
