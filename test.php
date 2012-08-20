<?php


require_once("src/svgtk/svgtk.php");
include "src/SurfaceCalculator.php";


class SurfaceDrawer 
{
	
	private $lasterrormessage = "";
	private $svgdocument = false;

	function errorMessage() { return $this->lasterrormessage; }

	function drawSurface($parameters)
	{
		if(! isset($parameters["shape"])) 
		{
			$lasterrormessage = "There is no shape specified in the parameters.";
			return false;
		}

		if($parameters["shape"] == "triangle") return $this->drawTriangle($parameters);

		return true;
	}

	function checkParameters($given, $needed)
	{
		foreach($needed as $name)
		{
			if(! isset($given[$name])) 
			{
				$this->lasterrormessage = "There is no parameter named '$name'";
				return false;
			}
		}
		return true;
	}


	function drawTriangle($parameters)
	{
		if(! $this->checkParameters($parameters, array("r", "bl", "alpha", "amount"))) return false;
		
		$doc = new SvgDocument("A4");

		$r = $parameters["r"];
		$bl = $parameters["bl"];
		$alpha = $parameters["alpha"];
		$amount = $parameters["amount"];

		$trianglewidth = $bl;
		$triangleheight = sin(deg2rad($alpha))*$r;

		$pagewidth = $doc->baseValueAs($doc->width(), "mm");
		$pageheight = $doc->baseValueAs($doc->height(), "mm");

		echo "PAGEWIDTH      = ".$pagewidth."\n";
		echo "PAGEHEIGHT     = ".$pageheight."\n";
		echo "TRIANGLEWIDTH  = ".$trianglewidth."\n";
		echo "TRIANGLEHEIGHT = ".$triangleheight."\n";		

		if($triangleheight >= $trianglewidth)
		{

		
			$a = new Point();
			$a->setX(($pagewidth - $trianglewidth)/2);
			$a->setY(($pageheight + $triangleheight)/2);
			echo "A = ".$a."\n";

			$b = new Point();
			$b->setX(($pagewidth + $trianglewidth)/2);
			$b->setY(($pageheight + $triangleheight)/2);
			
			$c = new Point();
			$c->setX(($pagewidth)/2);
			$c->setY(($pageheight - $triangleheight)/2);
			
			$path = $doc->createPath();
			$path->setStart($a);
			$path->lineto($b, false);
			$path->lineto($c, false);
			$path->close();
			$doc->addChild($path);
			
			//Baseline text
			$t = $doc->createText();
			$t->style()->setFontSize("3mm");
			$t->setX(($pagewidth/2)."mm");
			$t->setY(($a->y()+2)."mm");
 			$t->setText(sprintf("%.1fmm", $bl));
			$t->style()->setTextAnchor("middle");
			$doc->addChild($t);
			
			//Length of side
			$y = $a->y() - sin(deg2rad($alpha)) * ($r/2);
			$x = $a->x() +sin(deg2rad(90 - $alpha)) * ($r/2);
			$t = $doc->createText();
			$t->style()->setFontSize("3mm");
			$t->setX(($x)."mm");
			$t->setY(($y)."mm");
 			$t->setText(sprintf("%.1fmm", $r));
			$t->rotate(-$alpha, ($x-2)."mm", ($y+2)."mm");
			$t->style()->setTextAnchor("middle");
			$doc->addChild($t);

			//Length of side
			$t = $doc->createText();
			$t->style()->setFontSize("3mm");
			$t->setX(($a->x()+1)."mm");
			$t->setY(($a->y()-3)."mm");
 			$t->setText(sprintf("%.1f&#176;	", $alpha));
			$t->style()->setTextAnchor("left");
			$doc->addChild($t);

			//Scale
			$t = $doc->createText();
			$t->style()->setFontSize("3mm");
			$t->setX(($pagewidth/2)."mm");
			$t->setY(($pageheight/2)."mm");
 			$t->setText("1:1");
			$t->style()->setTextAnchor("middle");
			$doc->addChild($t);

			//Number of copies
			$t = $doc->createText();
			$t->style()->setFontSize("3mm");
			$t->setX(($pagewidth/2)."mm");
			$t->setY((($pageheight/2)+4)."mm");
 			$t->setText("$amount copies");
			$t->style()->setTextAnchor("middle");
			$doc->addChild($t);

	
			$this->svgdocument = $doc;
		}
		else
		{
	
		}
		return true;
	}

	function asXml()
	{
		return $this->svgdocument->asXml();
	}

}




$sc = new SurfaceCalculator;

$sc->setHeight(50);
$sc->setEdges(4);
$sc->setToplength(0);
$sc->setBottomlength(30);

/*
$sc->setHeight(30);
$sc->setEdges(3);
$sc->setToplength(0);
$sc->setBottomlength(90);
*/
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
	file_put_contents("/home/morten/out.svg", $x);
}

/*



$svg = new SvgDocument();

$rect = $svg->createRectangle();
$rect->setX("25mm");
$rect->setY("25mm");
$rect->setHeight("20mm");
$rect->setWidth("30mm");
$rect->style()->setFillColor("red");
$svg->addChild($rect);


$path = $svg->createPath();
$point = new Point();
$point->setUnit("mm");

$point->setX(10);
$point->setY(10);
$path->setStart($point);

$point = new Point();
$point->setUnit("mm");
$point->setX(0);
$point->setY(10);
$path->lineto($point);

$point = new Point();
$point->setUnit("mm");
$point->setX(10);
$point->setY(0);
$path->lineto($point);

$path->close();

$svg->addChild($path);




echo $svg->asXML();

*/


?>
