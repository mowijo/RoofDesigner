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
		if($parameters["shape"] == "rectangle") return $this->drawRectangle($parameters);
		if($parameters["shape"] == "pie") return $this->drawPie($parameters);
		if($parameters["shape"] == "rainbow") return $this->drawRainbow($parameters);
		
		$lasterrormessage = "I don't know how to draw a '".$parameters["shape"]."'";
		return false;
		
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


	function drawRainbow($parameters)
	{
		if(! $this->checkParameters($parameters, array("r", "R", "alpha", "amount"))) return false;
		
		$doc = new SvgDocument("A4");
		SvgStyle::setDefaultFontSize("3mm");

		$smallR = $parameters["r"];
		$bigR = $parameters["R"];
		$alpha = $parameters["alpha"];
		$amount = $parameters["amount"];

		$pagewidth = $doc->baseValueAs($doc->width(), "mm");
		$pageheight = $doc->baseValueAs($doc->height(), "mm");



		$dx = sin(deg2rad($alpha)/2)*$bigR;
		$dy = sin((3.1415-deg2rad($alpha))/2)*$bigR;

		if($alpha >= 180) 
		{
			$width= 2*$bigR;
			$height = $bigR + $dy;
		}
		else
		{
			$height = $bigR;
			$width = 2*$dx;
		}
		
		$top = new Point();
		$top->setX( ($pagewidth) /2);
		$top->setY( ($pageheight - $height) /2);

		$bigRbegin = new Point();
		$bigRbegin->setX( $top->x() - $dx);
		$bigRbegin->setY( $top->y() + $dy);

		$bigRend = new Point();
		$bigRend->setX( $top->x() + $dx);
		$bigRend->setY( $top->y() + $dy);

		
		$path = $doc->createPath();
		$path->style()->setStrokeWidth("0.1mm");
		$path->setStart($bigRbegin);
		$rotation = 0;
		$largearcflag = ($alpha > 180) ? 1 : 0;
		$sweepflag = 0;
		$path->arc($bigR."mm", $bigR."mm", $rotation, $largearcflag, $sweepflag, $bigRend, "absolute");
		$doc->addChild($path);


		//Draw small arc.
		$dx = sin(deg2rad($alpha)/2)*$smallR;
		$dy = sin((3.1415-deg2rad($alpha))/2)*$smallR;

		$smallRbegin = new Point();
		$smallRbegin->setX( $top->x() - $dx);
		$smallRbegin->setY( $top->y() + $dy);

		$smallRend = new Point();
		$smallRend->setX( $top->x() + $dx);
		$smallRend->setY( $top->y() + $dy);

		$path = $doc->createPath();
		$path->style()->setStrokeWidth("0.1mm");
		$path->setStart($smallRbegin);
		$rotation = 0;
		$largearcflag = ($alpha > 180) ? 1 : 0;
		$sweepflag = 0;
		$path->arc($smallR."mm", $smallR."mm", $rotation, $largearcflag, $sweepflag, $smallRend, "absolute");
		$doc->addChild($path);

		$path = $doc->createPath();
		$path->style()->setStrokeWidth("0.1mm");
		$path->setStart($smallRend);
		$path->lineTo($bigRend, "absolute");
		$doc->addChild($path);

		$path = $doc->createPath();
		$path->style()->setStrokeWidth("0.1mm");
		$path->setStart($smallRbegin);
		$path->lineTo($bigRbegin, "absolute");
		$doc->addChild($path);


		//The dashed lines.
		$path = $doc->createPath();
		$path->style()->setStrokeDashArray(array(1.772,1.772));
		$path->style()->setStrokeDashOffset(0);
		$path->style()->setStrokeWidth("0.1mm");
		$path->setStart($smallRbegin);
		$path->lineTo($top, "absolute");
		$path->lineTo($smallRend, "absolute");
		$doc->addChild($path);




		//Add the angle marking
		$t = $doc->createText();
		$t->setX($top->x()."mm");
		$t->setY(($top->y()+7)."mm");
		$t->setText(sprintf("%.1f&#176;", $alpha));
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);
	

		//Scale
		$t = $doc->createText();
		$t->setX(($pagewidth/2)."mm");
		$t->setY(($pageheight/2)."mm");
		$t->setText("1:1");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Number of copies
		$t = $doc->createText();
		$t->setX(($pagewidth/2)."mm");
		$t->setY((($pageheight/2)+4)."mm");
		$t->setText("1 copy");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Side length
		$dx = sin(deg2rad($alpha)/2)*($smallR/2);
		$dy = sin((3.1415-deg2rad($alpha))/2)*($smallR/2);
		$t = $doc->createText();
		$t->setX(($top->x()-($dx)-3)."mm");
		$t->setY(($top->y()+($dy)-3)."mm");
		$t->setText(sprintf("%.1fmm", $smallR));
		$t->rotate(($alpha/2)+270, ($top->x()-($dx))."mm",($top->y()+($dy))."mm");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Side length
		$dx = sin(deg2rad($alpha)/2)*($bigR/2);
		$dy = sin((3.1415-deg2rad($alpha))/2)*($bigR/2);
		$t = $doc->createText();
		$t->setX(($top->x()-($dx)-3)."mm");
		$t->setY(($top->y()+($dy)-3)."mm");
		$t->setText(sprintf("%.1fmm", $bigR));
		$t->rotate(($alpha/2)+270, ($top->x()-($dx))."mm",($top->y()+($dy))."mm");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);


		$this->svgdocument = $doc;
		return true;
	}




	function drawPie($parameters)
	{
		if(! $this->checkParameters($parameters, array("r", "alpha", "amount"))) return false;
		
		$doc = new SvgDocument("A4");
		SvgStyle::setDefaultFontSize("3mm");

		$r = $parameters["r"];
		$alpha = $parameters["alpha"];
		$amount = $parameters["amount"];

		$pagewidth = $doc->baseValueAs($doc->width(), "mm");
		$pageheight = $doc->baseValueAs($doc->height(), "mm");



		$dx = sin(deg2rad($alpha)/2)*$r;
		$dy = sin((3.1415-deg2rad($alpha))/2)*$r;

		if($alpha >= 180) 
		{
			$width= 2*$r;
			$height = $r + $dy;
		}
		else
		{
			$height = $r;
			$width = 2*$dx;
		}
		
		$top = new Point();
		$top->setX( ($pagewidth) /2);
		$top->setY( ($pageheight - $height) /2);

		$begin = new Point();
		$begin->setX( $top->x() - $dx);
		$begin->setY( $top->y() + $dy);

		$end = new Point();
		$end->setX( $top->x() + $dx);
		$end->setY( $top->y() + $dy);

		
		$path = $doc->createPath();
		$path->style()->setFillColor("#feffad");
		$path->style()->setStrokeWidth("0.1mm");
		$path->setStart($top);
		$path->lineTo($begin, "absolute");
		//$xradius, $yradius, $xaxisrotation, $largearcflag, $sweepflag, $p, $relative = "relative")
		$rotation = 0;
		$largearcflag = ($alpha > 180) ? 1 : 0;
		$sweepflag = 0;
		$path->arc($r."mm", $r."mm", $rotation, $largearcflag, $sweepflag, $end, "absolute");
		$path->close();
		$doc->addChild($path);


		//Add the angle marking
		$t = $doc->createText();
		$t->setX($top->x()."mm");
		$t->setY(($top->y()+7)."mm");
		$t->setText(sprintf("%.1f&#176;", $alpha));
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);
	
//&#176;

		//Scale
		$t = $doc->createText();
		$t->setX(($pagewidth/2)."mm");
		$t->setY(($pageheight/2)."mm");
		$t->setText("1:1");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Number of copies
		$t = $doc->createText();
		$t->setX(($pagewidth/2)."mm");
		$t->setY((($pageheight/2)+4)."mm");
		$t->setText("1 copy");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Side length
		$dx = sin(deg2rad($alpha)/2)*($r/2);
		$dy = sin((3.1415-deg2rad($alpha))/2)*($r/2);
		$t = $doc->createText();
		$t->setX(($top->x()-($dx)-3)."mm");
		$t->setY(($top->y()+($dy)-3)."mm");
		$t->setText(sprintf("%.1fmm", $r));
		$t->rotate(($alpha/2)+270, ($top->x()-($dx))."mm",($top->y()+($dy))."mm");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		$this->svgdocument = $doc;
		return true;
	}


	function drawRectangle($parameters)
	{
		if(! $this->checkParameters($parameters, array("width", "height", "amount"))) return false;
		
		$doc = new SvgDocument("A4");
		SvgStyle::setDefaultFontSize("3mm");

		$w = $parameters["width"];
		$h = $parameters["height"];
		$amount = $parameters["amount"];

		$pagewidth = $doc->baseValueAs($doc->width(), "mm");
		$pageheight = $doc->baseValueAs($doc->height(), "mm");

		$a = new Point();
		$a->setX(($pagewidth - $w)/2);
		$a->setY(($pageheight + $h)/2);

		$b = new Point();
		$b->setX(($pagewidth + $w)/2);
		$b->setY($a->y());

		$c = new Point();
		$c->setX($b->x());
		$c->setY(($pageheight - $h)/2);

		$d = new Point();
		$d->setX($a->x());
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
		$t->setX(($pagewidth/2)."mm");
		$t->setY(($a->y()+2)."mm");
		$t->setText(sprintf("%.1fmm", $w));
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);
		
		//Length of side

		$x = ($pagewidth-$w)/2;
		$y = ($pageheight)/2;
		$t = $doc->createText();
		$t->setX(($x)."mm");
		$t->setY(($y-3)."mm");
		$t->setText(sprintf("%.1fmm",$h));
		$t->rotate(-90, $x."mm", $y."mm");
		
		$doc->addChild($t);

		//Scale
		$t = $doc->createText();
		$t->setX(($pagewidth/2)."mm");
		$t->setY(($pageheight/2)."mm");
		$t->setText("1:1");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Number of copies
		$t = $doc->createText();
		$t->setX(($pagewidth/2)."mm");
		$t->setY((($pageheight/2)+4)."mm");
		$t->setText("$amount copies");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		$this->svgdocument = $doc;
		return true;

	}



	function drawTrapezoid($parameters)
	{
		if(! $this->checkParameters($parameters, array("tl", "bl", "psl", "alpha", "amount"))) return false;
		
		$doc = new SvgDocument("A4");
		SvgStyle::setDefaultFontSize("3mm");

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
		$t->setX(($pagewidth/2)."mm");
		$t->setY(($a->y()+4)."mm");
		$t->setText(sprintf("%.1fmm", $bl));
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);
		
		//Length of side
		$y = $a->y() - sin(deg2rad($alpha)) * ($psl/2);
		$x = $a->x() +sin(deg2rad(90 - $alpha)) * ($psl/2);
		$t = $doc->createText();
		$t->setX(($x)."mm");
		$t->setY(($y)."mm");
		$t->setText(sprintf("%.1fmm", $psl));
		$t->rotate(-$alpha, ($x)."mm", ($y+2)."mm");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Topline text
		$t = $doc->createText();
		$t->setX(($pagewidth/2)."mm");
		$t->setY(($c->y()-3)."mm");
		$t->setText(sprintf("%.1fmm", $tl));
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Angle
		$t = $doc->createText();
		$t->setX(($a->x()+1)."mm");
		$t->setY(($a->y()-3)."mm");
		$t->setText(sprintf("%.1f&#176;	", $alpha));
		$t->style()->setTextAnchor("left");
		$doc->addChild($t);

		//Scale
		$t = $doc->createText();
		$t->setX(($pagewidth/2)."mm");
		$t->setY(($pageheight/2)."mm");
		$t->setText("1:1");
		$t->style()->setTextAnchor("middle");
		$doc->addChild($t);

		//Number of copies
		$t = $doc->createText();
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
			$t->setX(($pagewidth/2)."mm");
			$t->setY(($a->y()+2)."mm");
 			$t->setText(sprintf("%.1fmm", $bl));
			$t->style()->setTextAnchor("middle");
			$doc->addChild($t);
			
			//Length of side
			$y = $a->y() - sin(deg2rad($alpha)) * ($r/2);
			$x = $a->x() +sin(deg2rad(90 - $alpha)) * ($r/2);
			$t = $doc->createText();
			$t->setX(($x)."mm");
			$t->setY(($y)."mm");
 			$t->setText(sprintf("%.1fmm", $r));
			$t->rotate(-$alpha, ($x-2)."mm", ($y+2)."mm");
			$t->style()->setTextAnchor("middle");
			$doc->addChild($t);

			//Angle
			$t = $doc->createText();
			$t->setX(($a->x()+1)."mm");
			$t->setY(($a->y()-3)."mm");
 			$t->setText(sprintf("%.1f&#176;	", $alpha));
			$t->style()->setTextAnchor("left");
			$doc->addChild($t);

			//Scale
			$t = $doc->createText();
			$t->setX(($pagewidth/2)."mm");
			$t->setY(($pageheight/2)."mm");
 			$t->setText("1:1");
			$t->style()->setTextAnchor("middle");
			$doc->addChild($t);

			//Number of copies
			$t = $doc->createText();
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