<?php

abstract class SvgElement
{

	var $children_ = array();
	var $style_;
	var $svgdocument_ = false;
	abstract function elementName();
	abstract function populateXmlElement($e);	

	function __construct()
	{
		$this->style_ = new SvgStyle();
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
		$this->populateXmlElement($xmlelement);
		foreach($this->children_ as $child)
		{
			$e = $xmlelement->addChild($child->elementName());
			$child->createSubTree($e);
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