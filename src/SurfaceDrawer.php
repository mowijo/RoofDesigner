<?php
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
		if($parameters["shape"] == "trapezoid") return $this->drawTrapezoid($parameters);

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


/*
array(6) {                                                                                                                                              
  ["alpha"]=>                                                                                                                                           
  float(73.532384751422)                                                                                                                                
  ["psl"]=>                                                                                                                                             
  float(52.915026221292)                                                                                                                                
  ["bl"]=>                                                                                                                                              
  int(40)                                                                                                                                               
  ["tl"]=>                                                                                                                                              
  int(10)                                                                                                                                               
  ["shape"]=>                                                                                                                                           
  string(9) "trapezoid"                                                                                                                                 
  ["amount"]=>                                                                                                                                          
  int(3)                                                                                                                                                
}     
*/
	function drawTrapezoid($parameters)
	{
		if(! $this->checkParameters($parameters, array("tl", "bl", "psl", "alpha", "amount"))) return false;
		
		$doc = new SvgDocument("A4");

		$psl = $parameters["psl"];
		$bl = $parameters["bl"];
		$tl = $parameters["tl"];
		$alpha = $parameters["alpha"];
		$amount = $parameters["amount"];

		$trapezoidwidth = $bl;
		$trapezoidheight = sin(deg2rad($alpha))*$psl;

		$pagewidth = $doc->baseValueAs($doc->width(), "mm");
		$pageheight = $doc->baseValueAs($doc->height(), "mm");


		$a = new Point();
		$a->setX(($pagewidth - $trapezoidwidth)/2);
		$a->setY(($pageheight + $trapezoidheight)/2);

		$b = new Point();
		$b->setX(($pagewidth + $trapezoidwidth)/2);
		$b->setY($a->y());

		$c = new Point();
		$c->setX(($pagewidth + $tl)/2);
		$c->setY(($pageheight - $trapezoidheight)/2);

		$d = new Point();
		$d->setX(($pagewidth - $tl)/2);
		$d->setY($c->y());

		$path = $doc->createPath();
		$path->setStart($a);
		$path->lineto($b, false);
		$path->lineto($c, false);
		$path->lineto($d, false);
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
		$y = $a->y() - sin(deg2rad($alpha)) * ($psl/2);
		$x = $a->x() +sin(deg2rad(90 - $alpha)) * ($psl/2);
		$t = $doc->createText();
		$t->style()->setFontSize("3mm");
		$t->setX(($x)."mm");
		$t->setY(($y)."mm");
		$t->setText(sprintf("%.1fmm", $psl));
		$t->rotate(-$alpha, ($x-2)."mm", ($y+2)."mm");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Topline text
		$t = $doc->createText();
		$t->style()->setFontSize("3mm");
		$t->setX(($pagewidth/2)."mm");
		$t->setY(($c->y()-3)."mm");
		$t->setText(sprintf("%.1fmm", $tl));
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Angle
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

	//	if($triangleheight >= $trianglewidth)
	//	{		
			$a = new Point();
			$a->setX(($pagewidth - $trianglewidth)/2);
			$a->setY(($pageheight + $triangleheight)/2);

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

			//Angle
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
		//}
		//else
		//{
	
		//}
		return true;
	}

	function asXml()
	{
		return $this->svgdocument->asXml();
	}

}

?>