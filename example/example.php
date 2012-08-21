<?php


require_once("../src/svgtk/svgtk.php");
include "../src/SurfaceCalculator.php";
include "../src/SurfaceDrawer.php";

/*
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

*/



$svg = new SvgDocument("A4");
$path = $svg->createPath();
$start = new Point;
$start->setX(100);
$start->setY(100);

$top = new Point;
$top->setX(120);
$top->setY(80);

$end = new Point;
$end->setX(140);
$end->setY(100);

$path->setStart($start);
$path->lineTo($start->differenceTo($top));
$path->lineTo($top->differenceTo($end));


$path->arc(100,60,0,0,1, new Point(-40,0));
$path->style()->setStrokeWidth("0.5mm");

$svg->addChild($path);



$x = $svg->asXml();
echo "\n\n".$x;
file_put_contents("../../out.svg", $x);


?>
