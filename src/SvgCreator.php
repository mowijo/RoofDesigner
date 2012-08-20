<?php

class SVGCreator
{
	var $DOTSPERMM = 3.5432996633;
	var $DOTSPERCM = 35.432996633;

	var $svg = "";
	var $errormessage = "";

	function latestErrorMessage()
	{
		return $this->errormessage;
	}

	private function loadTemplate($type)
	{
		$filename = dirname(__FILE__)."/".$type.".svg-template";
		if( ! file_exists($filename)) {
			$this->errormessage  = "No template for SVG's containing '$type' could be found. ($filename)";
			return false;
		}	
		$this->svg = file_get_contents($filename);
		return true;
	}

	private function replaceMarkers($markers)
	{
		foreach($markers as $key => $value)
		{
			$this->svg = str_replace("[".$key."]", "".$value, $this->svg);
		}
	}


	private function calculateTriangle($data)
	{
   	return array(
			"X" => (744/2 - (cos(deg2rad($data["alpha"])) * $data["l"] * $this->DOTSPERCM)) ,
			"Y" => 1052/2 + (sin(deg2rad($data["alpha"])) * $data["l"] * $this->DOTSPERCM) / 2,
			"BL" => $data["bl"]*$this->DOTSPERCM,
			"DX" => cos(deg2rad($data["alpha"])) * $data["l"] * $this->DOTSPERCM,
 			"DY" => sin(deg2rad($data["alpha"])) * $data["l"] * $this->DOTSPERCM,
			"N" => $data["amount"]
		);

	}

	private function calculateArc($data)
	{
			return  array(
				"X" => 744.09448819/2,
				"Y" => 1052.3622047/2,
				"HEIGHT" => $data["radius"],
				"WIDTH" => $data["radius"],
				"START" => 0,
				"END" => deg2rad($data["alpha"])
			);

	}

	private function calculateDrawingMarkers($data)
	{
		if($data["shape"] == "triangle" ) return $this->calculateTriangle($data);
		if($data["shape"] == "arc" ) return $this->calculateArc($data);
		return array();
	}

	function generate($data)
	{
		if ( ! array_key_exists ( "shape", $data) ) { $this->errormessage = "Malformed data. No value for 'shape'."; return false; }
		if ( ! $this->loadTemplate($data["shape"])) return false;
		
		$markers = $this->calculateDrawingMarkers($data);
		$this->replaceMarkers($markers);
		return true;
	}

	function save($file)
	{
		if(file_put_contents($file, $this->svg) === false)
		{
			return false;
		}
		return true;
	}
}

?>
