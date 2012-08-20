<?php
class SvgPath extends SvgElement
{

	private $commands = false;
	private $doclose = false;

	function __construct()
	{
		SvgElement::__construct();
		$this->style()->setStrokeColor("#000000");
		$this->style()->setStrokeWidth("0.5mm");
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
			

			$s .= " ".$point->x()*3.5 . ",".$point->y()*3.5;

		}
		if($this->doclose) $s .= " z";
		$e["d"]= trim($s);

		
	}

	function setStart($p)
	{
		$this->commands = array();
		array_push($this->commands, "M");
		array_push($this->commands, $p);
	}

	//Relaive => lowercase..
	function lineTo($p, $relative= "relative")
	{
		if($this->commands === false) throw new Exception("You must add a start to a path before you can ad a line.");
		array_push($this->commands, ($relative == "relative" )? "l" : "L");
		array_push($this->commands, $p);

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