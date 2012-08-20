<?php

//http://commons.oreilly.com/wiki/index.php/SVG_Essentials/Coordinates

class SvgDocument
{
	var $dpmm_ = 3.54330708662;
	var $width_;
	var $height_;	
	
	var $children_ = array();
	
	function __construct($x = 0, $y = 0)
	{
		if($x == 0 && $y == 0) 
		{
			$this->setSize("A4");
		}
	}


	function baseValueAs($value, $unit)
	{
		if(strtolower($unit) == "mm") return $value / $this->dpmm_;
		if(strtolower($unit) == "cm") return $value / ($this->dpmm_ * 10);
		return $value;
	}


	function valueToBaseUnit($value)
	{
		$value = strtolower($value);

		if(substr($value, strlen($value) - 2, 2) == "cm")
		{
			return floatval(substr($value, 0, strlen($value) - 2)) * $this->dpmm_ * 10;
		}
		if(substr($value, strlen($value) - 2, 2) == "mm")
		{
			return floatval(substr($value, 0, strlen($value) - 2)) * $this->dpmm_;
		}
		if(substr($value, strlen($value) - 2, 2) == "pt")
		{
			return floatval(substr($value, 0, strlen($value) - 2));
		}
		return floatval($value);
	}


	function setSize($x, $y = 0)
	{
		if($x == "A4") 
		{
			$this->width_=744.09448819;
			$this->height_=1052.3622047;
		}
		else
		{
			$this->width_=$x;
			$this->height_=$y;		
		}
	}
	
	function setHeight($h)
	{
		$this->height = $h;
	}
	
	function setWidth($w)
	{
		$this->width_ = $w;
	}


	function height()
	{
		return $this->height_;
	}
	
	function width()
	{	
		return $this->width_;
	}


	function addChild($svgelement)
	{
		array_push($this->children_, $svgelement);
	}
	

	function &createElement($elementname)
	{
		switch($elementname)
		{
			case "rectangle":	
			case "rect":
				return $this->createRectangle();
			case "rectangle":	
			case "rect":
				return $this->createPath();
		}
		return false;
	}

	function &createRectangle()
	{
		$r = new SvgRectangle();
		$r->setDocument(&$this);
		return $r;
	}

	function &createPath()
	{
		$p = new SvgPath();
		$p->setDocument(&$this);
		return $p;
	}

	
	function asXML()
	{
		$x = "<?xml version=\"1.0\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"5cm\" height=\"5cm\"></svg>";
		$x = new SimpleXMLElement($x);
		$x["width"] = round($this->width_ / $this->dpmm_)."mm";
		$x["height"] = round($this->height_ / $this->dpmm_)."mm";
		
		foreach($this->children_ as $child)
		{
			$e = $x->addChild($child->elementName());
			$child->createSubTree($e);
		}
		
		$dom = dom_import_simplexml($x)->ownerDocument;
		$dom->formatOutput = true;
		return $dom->saveXML();
	}

}

?>