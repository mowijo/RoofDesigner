<?php

class SurfaceCalculator
{
	static $pi = 3.14159265358979323846264338327950288419716939937510582;
	var $height = -1;
	var $edges = -1;
	var $toplength = -1;
	var $bottomlength = -1;
	
	var $errormessage = "";


	function radianAngleInPolygon($n)
	{
		return ($n - 2) * SurfaceCalculator::$pi;
	}

	function circumscribedRadius($n, $sl)
	{
		$a = $this->radianAngleInPolygon($n);
		return ($sl) / (2*sin($a/2));
	}


//If n == 1 $bottomlength = DIAMETER!! of circle
	function setHeight($p)   {$this->height = $p; }
	function setEdges($p)   {$this->edges = $p; }
	function setToplength($p)   {$this->toplength = $p; }
	function setBottomlength($p)   {$this->bottomlength = $p; }

	function isInputSane()
	{
		//If n == 1 $bl = DIAMETER!! of circle
		if($this->height < 1)  { $this->errormessage = "You need to specify a height."; return false; }
		if($this->bottomlength < 1) {$this->errormessage = "You need a bottom length larget than 0."; return false; }
		if($this->toplength > $this->bottomlength) doError("Top side length must be larger than bottom side length");
		if(($this->edges < 1) || ($this->edges == 2))  {$this->errormessage = "You need to have 1, 3 or more sides."; return false; }
		return true;
	}

	function latestErrorMessage()
	{
		return $this->errormessage;
	}


	function calculateSurface()
	{
		if (! $this->isInputSane()) return false;
		if($this->bottomlength == $this->toplength) return $this->doCylinder();
		if( ($this->bottomlength !=  $this->toplength) && ($this->toplength == 0) ) return $this->doPyramid();
		if( ($this->bottomlength !=  $this->toplength) && ($this->toplength > 0) ) return $this->doPyramidStub();
	}


	function doCylinder() 
	{
		if($this->edges == 1)
		{
			return array(
				"width" => $this->bottomlength * SurfaceCalculator::$pi,
				"height" => $this->height,
				"shape" => "rectangle",
				"amount" => $this->edges
			);

		}
		else
		{
			return array(
				"width" => $this->bottomlength ,
				"height" => $this->height,
				"shape" => "rectangle",
				"amount" => $this->edges
			);
		}

	}



	private function doPyramid()
	{
		$h = $this->height;
		$bl = $this->bottomlength;
		$n = $this->edges;
		$pi = SurfaceCalculator::$pi;

		if($n == 1) 
		{
			$R = sqrt ( $h * $h + ($bl / 2) * ($bl / 2)  );
			$alpha = 180 * $bl / $R;
			return array("r" => $R, "alpha" => $alpha, "shape" => "pie", "amount" => 1);
			
		}
		else 
		{
			//pyramid with n sides.
			$bcr = $this->bottomlength /( 2 * sin(SurfaceCalculator::$pi/$this->edges));
			$r = sqrt( pow($bcr,2) + pow($this->height,2));
			$alpha = SurfaceCalculator::$pi/2 - asin($this->bottomlength/(2 * $r));

			return array("bl" => $this->bottomlength, "r" => $r, "alpha" =>rad2deg($alpha) , "shape" => "triangle", "amount" => $this->edges);
		}
	}


	private function doPyramidStub()
	{
		if($this->edges == 1)
		{
			$rmark = sqrt(  pow($this->height,2) + pow (($this->bottomlength-$this->toplength)/2,2));
			$theta = ASIN($this->height/$rmark);
			$capitalr = $this->bottomlength / ( 2 * sin((PI()/2) - $theta) );
			$alpha = 180*$this->bottomlength/$capitalr;
			$lowerr = $capitalr - $rmark;

			return array(
				"alpha" => $alpha, 
				"r" => $lowerr, 
				"R" => $capitalr, 
				"shape" => "rainbow",
				"amount" => 1
			);
		}
		else
		{
			$tcr = $this->toplength    / ( 2 * sin( SurfaceCalculator::$pi / $this->edges )) ;
			$bcr = $this->bottomlength / ( 2 * sin( SurfaceCalculator::$pi  / $this->edges )) ;
			$psl = sqrt(  pow($this->height, 2) + pow($bcr - $tcr, 2)  );
			$opp = sqrt( pow($psl, 2)  -  pow(($this->bottomlength - $this->toplength)/2,2) );
			$alpha = rad2deg(asin($opp / $psl));
			return array(
				"alpha" => $alpha, 
				"psl" => $psl, 
				"bl" => $this->bottomlength, 
				"tl" => $this->toplength, 
				"shape" => "trapezoid", 
				"amount" => $this->edges
			);

		}
	}



	
}



?>