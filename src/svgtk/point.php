<?php


class Point
{
	var $x_m;
	var $y_m;
	var $unit_m;
	static private $default_unit = "mm";
	
	function __construct($x = 0, $y = 0)
	{
		$this->x_m = $x;
		$this->y_m= $y;
		$this->unit_m = Point::$default_unit;
	}
	
	function setX($x) { $this->x_m = $x; }
	function x() { return $this->x_m ; }

	function setY($y) { $this->y_m = $y; }
	function y() { return $this->y_m ; }

	function setDefaultUnit($du) { Point::$default_unit = $du; }
	function defaultUnit() { return Point::$default_unit; }

	function setUnit($u) {	$this->unit_m = $u;	}
	function unit() { return $this->unit_m; }


	function __tostring()
	{
		return "[".$this->x_m.$this->unit_m.",".$this->y_m.$this->unit_m."]";
	}

	function differenceTo($other)
	{
		return new Point(
			$other->x_m - $this->x_m,
			$other->y_m - $this->y_m
		);
	}

	
}


?>