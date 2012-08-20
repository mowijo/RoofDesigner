<?php
class SvgPath extends SvgElement
{

	private $commands = false;
	private $doclose = false;

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

		//$e["style"] = "fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1";
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