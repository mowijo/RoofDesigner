<?php class SvgStyle
{
	var $strokecolor_ = "";
//	var $strokewidth_ = "";
	var $fillcolor_ = "";

	function setStrokeColor($c)
	{
		$this->strokecolor_ = $c;
	}
	/*
	function setStrokeWidth($c)
	{
		$this->strokewidth_ = $c;
	}*/

	function setFillColor($c)
	{
		$this->fillcolor_ = $c;
	}
	function toString()
	{
		//stroke:black; fill:none;
		$a = array();
		if($this->strokecolor_ != "") array_push($a, "stroke:".$this->strokecolor_);
		if($this->fillcolor_ != "") array_push($a, "fill:".$this->fillcolor_);
		return implode(";", $a);
	}

}
?>