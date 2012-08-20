<?php class SvgStyle
{
	private $strokecolor_ = "";
	private $strokewidth_ = "";
	private $fillcolor_ = "";
	private $strokelinecap_ = "";
	private $strokelinejoin_  = "";
	private $strokeopacity_ = "";
	private $textanchor_ = "";

	function setStrokeColor($c)
	{
		$this->strokecolor_ = $c;
	}
	
	function setStrokeWidth($c)
	{
		$this->strokewidth_ = $c;
	}

	function setStrokeLineCap($lc)
	{
		$this->strokelinecap_ = $lc;
	}

	function setStrokeLineJoin($lj)
	{
		$this->strokelinejoin_ = $lj;
	}

	function setStrokeOpacity($so)
	{
		$this->strokeopacity_ = $so;
	}

	function setFillColor($c)
	{
		$this->fillcolor_ = $c;
	}

	function setTextAnchor($a)
	{
		$this->textanchor_ = $a;
	}

	function toString()
	{
		//stroke:black; fill:none;
		$a = array();
		if($this->strokecolor_ != "") array_push($a, "stroke:".$this->strokecolor_);
		if($this->fillcolor_ != "") array_push($a, "fill:".$this->fillcolor_);
		if($this->strokewidth_ != "") array_push($a, "stroke-width:".$this->fillcolor_);
		if($this->strokelinecap_ != "") array_push($a, "stroke-linecap:".$this->strokelinecap_);
		if($this->strokelinejoin_ != "") array_push($a, "stroke-linejoin:".$this->strokelinejoin_);
		if($this->strokeopacity_ != "") array_push($a, "stroke-opacity:".$this->strokeopacity_);
		if($this->textanchor_ != "") array_push($a, "text-anchor:".$this->textanchor_);
		return implode(";", $a);
	}







}
?>