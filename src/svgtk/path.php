<?php
class SvgPath extends SvgElement
{

	private $commands = false;
	private $doclose = false;

	function __construct()
	{
		SvgElement::__construct();
		$this->style()->setStrokeColor("#000000");
		$this->style()->setStrokeWidth("0.05mm");
		$this->style()->setStrokeLineCap("butt");
		$this->style()->setStrokeLineJoin("miter");
		$this->style()->setStrokeOpacity(1);
		$this->style()->setFillColor("none");
	}

	function elementName()
	{
		return "path";
	}

	function populateXmlElement($e)
	{

		$s = "";
		$lastcommand = "";
		for($i = 0; $i < sizeof($this->commands); $i+=2)
		{
			$command = $this->commands[$i];
			$point = $this->commands[$i+1];
			if($command != $lastcommand)
			{
				$lastcommand = $command;
				$s .= " ".$command;
			}

			switch($command)
			{
				case "L":
				case "l":
				case "M":
				case "m":
					//Check that the point is indeed a point.. TODO
					$s .= " ".$this->valueToBaseUnit($point->x().$point->unit()) . ",".$this->valueToBaseUnit($point->y().$point->unit());
					break;
				case "A":	
				case "a":
					$s .= $this->createDataForArc($point);	//<<--This point is not a point but a map of arc data.
					break;
			}

		}
		
		if($this->doclose) $s .= " z";
		$e["d"]= trim($s);

		
	}

	function setStart($p)
	{
		$this->moveTo($p, "absolute");
	}

	function moveTo($p, $positioning = "relative")
	{
		$this->commands = array();
		array_push($this->commands, ($positioning == "relative" )? "m" : "M");
		array_push($this->commands, $p);		
	}

	//Relaive => lowercase..
	function lineTo($p, $relative= "relative")
	{
		if($this->commands === false) throw new Exception("You must add a start to a path before you can add a line.");
		array_push($this->commands, ($relative == "relative" )? "l" : "L");
		array_push($this->commands, $p);
	}

	function createDataForArc($data)
	{
		$s = "";
		$s .= $this->valueToBaseUnit($data["xradius"]).",";
		$s .= $this->valueToBaseUnit($data["yradius"])." ";
		$s .= $data["x-axis-rotation"]." ";
		$s .= $data["large-arc-flag"].",";
		$s .= $data["sweep-flag"]." ";
		$p = $data["endpoint"];
		$s .= $this->valueToBaseUnit($p->x().$p->unit()) . ",".$this->valueToBaseUnit($p->y().$p->unit());
		return $s;
	}




	function arc($xradius, $yradius, $xaxisrotation, $largearcflag, $sweepflag, $p, $relative = "relative")
	{
		if($this->commands === false) throw new Exception("You must add a start to a path before you can add an arc.");
		array_push($this->commands, ($relative == "relative" )? "a" : "A");
		$data = array();
		$data["xradius"] = $xradius;
 		$data["yradius"] = $yradius;
		$data["x-axis-rotation"] = $xaxisrotation;
		$data["large-arc-flag"] = $largearcflag;
		$data["sweep-flag"] = $sweepflag;
		$data["endpoint"] = $p;
 		array_push($this->commands, $data);
	}




	function close()
	{
		$this->doclose = true;
	}

	function unClose()
	{
		$this->doclose = false;
	}
	

	

}


?>