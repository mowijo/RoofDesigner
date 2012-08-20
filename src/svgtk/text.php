<?php

class SvgText extends SvgElement
{

	private $x_;
	private $y_;
	private $text_;
	
	function setX($v) { $this->x_ = $this->valueToBaseUnit($v); }
	function setY($v) { $this->y_ = $this->valueToBaseUnit($v); }
	
	function x() { return $this->x_; }
	function y() { return $this->y_; }
	
	function elementName() { return "text"; }

	function __construct()
	{
		SvgElement::__construct();
	}
	
	function populateXmlElement($e)
	{
		$e["x"] = $this->x_;
		$e["y"] = $this->y_;	
	}

	function setText($t)
	{
		$this->text_ = $t;
	}

	function text()
	{
		return $this->text_;
	}

	function elementContent()
	{
		return $this->text_;
	}

}


?>