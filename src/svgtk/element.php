<?php

abstract class SvgElement
{

	var $children_ = array();
	var $style_;
	var $svgdocument_ = false;
	abstract function elementName();
	abstract function populateXmlElement($e);	
	var $rotation = false;

	function __construct()
	{
		$this->style_ = new SvgStyle();
	}

	function rotate($degrees, $x, $y)
	{
		$this->rotation = array("angle" => $degrees, "x" =>  $this->valueToBaseUnit($x), "y" => $this->valueToBaseUnit($y));
	}

	function rotation()
	{
		return $this->rotation;
	}


	function elementContent()
	{
		return "";
	}


	function baseValueAs($value, $unit)
	{
		if($this->svgdocument_ !== false)
		{
			return $this->svgdocument_->baseValueAs($value, $unit);
		}
		else
		{
			throw new Exception("Cannot convert value to baseunit without a document.");
		}
	}


	function valueToBaseUnit($v)
	{
		if($this->svgdocument_ !== false)
		{
			return $this->svgdocument_->valueToBaseUnit($v);
		}
		else
		{
			throw new Exception("Cannot convert value to baseunit without a document.");
		}
	}
	
	function &style()
	{
		return $this->style_;
	}
	
	function addChild($svgelement)
	{
		array_push($this->children_, $svgelement);
	}
	
	function createSubTree($xmlelement)
	{
		$style = $this->style()->toString();
		if($style != "") $xmlelement["style"] = $style;
		
		$transformations = "";
		if($this->rotation !== false)
		{
			if($transformations != "") $transformations .= " ";
			$transformations .= "rotate(".$this->rotation["angle"].", ".$this->rotation["x"].", ".$this->rotation["y"].")";
		}

		if($transformations != "") $xmlelement["transform"] = $transformations;

		$this->populateXmlElement(&$xmlelement);
		foreach($this->children_ as $child)
		{
			$e = $xmlelement->addChild($child->elementName(), "This is a piece of text");
			$child->createSubTree(&$e);
		}
	}

	function setDocument(&$doc)
	{
		$this->svgdocument_ = &$doc;
	}

	function &document($doc)
	{
		return $this->svgdocument_;
	}

}


?>