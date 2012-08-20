<?php


class Point
{
	var $x_m;
	var $y_m;
	var $unit_m;
	
	function __construct($x = 0, $y = 0)
	{
		$this->x_m = $x;
		$this->y_m= $y;
	}
	
	function setX($x) { $this->x_m = $x; }
	function setY($y) { $this->y_m = $y; }
	function x() { return $this->x_m ; }
	function y() { return $this->y_m ; }


	function __tostring()
	{
		return "[".$this->x_m.",".$this->y_m."]";
	}

	function differenceTo($other)
	{
		return new Point(
			$other->x_m - $this->x_m,
			$other->y_m - $this->y_m
		);
	}

	function setUnit($u) {	$this->unit_m = $u;	}
	function unit() { return $this->unit_m; }
	
}


?>