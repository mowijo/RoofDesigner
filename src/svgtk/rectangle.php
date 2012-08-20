<?php

class SvgRectangle extends SvgElement
{

	private $x_;
	private $y_;
	private $height_;
	private $width_;
	
	function setX($v) { $this->x_ = $this->valueToBaseUnit($v); }
	function setY($v) { $this->y_ = $this->valueToBaseUnit($v); }
	function setHeight($v) { $this->height_ = $this->valueToBaseUnit($v); }
	function setWidth($v) { $this->width_ = $this->valueToBaseUnit($v); }
	
	function x() { return $this->x_; }
	function y() { return $this->y_; }
	function height() { return $this->height_; }
	function width() { return $this->width_; }
	
	function elementName() { return "rect"; }

	function __construct()
	{
		SvgElement::__construct();
	}
	
	function populateXmlElement($e)
	{
		$e["x"] = $this->x_;
		$e["y"] = $this->y_;
		$e["width"] = $this->width_;
		$e["height"] = $this->height_;
		
	}
}


?>