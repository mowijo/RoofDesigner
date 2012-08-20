<?php

require_once(dirname(__FILE__) . '/simpletest/autorun.php');

include(dirname(__FILE__)."/../src/SurfaceCalculator.php");

function mapCompare($lhs, $rhs)
{
	$lkeys = array_keys($lhs);
	$rkeys = array_keys($rhs);
	$diff = array_diff($lkeys, $rkeys);

	if( sizeof($diff) != 0) {
		print_r($lhs);
		echo "and\n";
		print_r($rhs);
		echo "differs in keys by\n";
		print_r($diff);
		return false;
	}

	$r = true;

	foreach($lhs as $key => $val)
	{
		if( is_int($val) )
		{
			if( $val != $rhs[$key] ) $r = false;
		}
		else if( is_string($val) )
		{
			if( $val != $rhs[$key] ) $r = false;
		}
		else	if( is_float($val) )
		{
			if( intval(1000*$val) != intval(1000*$rhs[$key]) ) $r = false;
		}


		if( ! $r ) {
			print_r($lhs);
			echo "and\n";
			print_r($rhs);
			echo "differs in value $key\n";		
			return false;
		}

	}
	return true;
}



class SurfaceCalculatorTest extends UnitTestCase {
	
	var $data = array(
	
		//Frustrums on polygons
		array(
			"input" => array("height" => 5, "edges" => 5, "toplength" => 2, "bottomlength" => 5),
			"expected" => array("alpha" =>  74.501782659189, "psl" => 5.6135961005179, "bl" => 5, "tl"=>2, "shape" => "trapezoid", "amount" => 5)
		),
		array(
			"input" => array("height" => 7, "edges" => 4, "toplength" => 1, "bottomlength" => 3),
			"expected" => array("alpha" =>  81.9505330245, "psl" => 7.1414284285, "bl" => 3, "tl"=>1, "shape" => "trapezoid", "amount" => 4)
		),
		array(
			"input" => array("height" => 5, "edges" => 3, "toplength" => 3, "bottomlength" => 4),
			"expected" => array("alpha" =>  84.2988381953, "psl" => 5.0332229568, "bl" => 4, "tl"=>3, "shape" => "trapezoid", "amount" => 3)
		),

		//Frustrums on circles
		array(
			"input" => array("height" => 6, "edges" => 1, "toplength" => 1, "bottomlength" => 7),
			"expected" => array("alpha" =>  160.9968944, "R" => 7.8262379, "r" => 1.118033989,  "shape" => "rainbow", "amount" => 1)
		),

		array(
			"input" => array("height" => 3, "edges" => 1, "toplength" => 2, "bottomlength" => 6),
			"expected" => array("alpha" =>  199.6920706411, "R" => 5.40832691, "r" => 1.8027756377,  "shape" => "rainbow", "amount" => 1)
		),

		array(
			"input" => array("height" => 4, "edges" => 1, "toplength" => 3, "bottomlength" => 5),
			"expected" => array("alpha" =>  87.31282501, "R" => 10.30776406, "r" => 6.184658438, "shape" => "rainbow", "amount" => 1)
		),

		//Cone on circle
		array(
			"input" => array("height" => 5, "edges" => 1, "toplength" => 0, "bottomlength" => 6),
			"expected" => array("r" => 5.830951895, "alpha" => 185.218472, "shape" => "pie", "amount" => 1)
		),

		array(
			"input" => array("height" => 6, "edges" => 1, "toplength" => 0, "bottomlength" => 3),
			"expected" => array("r" => 6.184658438, "alpha" => 87.31282501, "shape" => "pie", "amount" => 1)
		),

		array(
			"input" => array("height" => 8, "edges" => 1, "toplength" => 0, "bottomlength" => 5),
			"expected" => array("r" => 8.381527307, "alpha" => 107.3789975, "shape" => "pie", "amount" => 1)
		),

		//Cone on circle
		array(
			"input" => array("height" => 4, "edges" => 3, "toplength" => 0, "bottomlength" => 6),
			"expected" => array("bl" => 6, "r" => 5.291502622, "alpha" => 55.46241621, "shape" => "triangle", "amount" => 3)
		),
	
		array(
			"input" => array("height" => 5, "edges" => 4, "toplength" => 0, "bottomlength" => 5),
			"expected" => array("bl" => 5, "r" =>6.123724357 , "alpha" => 65.90515745, "shape" => "triangle", "amount" => 4)
		),

		array(
			"input" => array("height" => 6, "edges" => 5, "toplength" => 0, "bottomlength" => 4),
			"expected" => array("bl" => 4, "r" => 6.897659658, "alpha" =>73.1448327 , "shape" => "triangle", "amount" => 5)
		),

		//Cylinder on circle
		array(
			"input" => array("height" => 4, "edges" => 1, "toplength" => 6, "bottomlength" => 6),
			"expected" => array("width" => 18.8495559215, "height" => 4,  "shape" => "rectangle", "amount" => 1)
		),
		array(
			"input" => array("height" => 3, "edges" => 1, "toplength" => 4, "bottomlength" => 4),
			"expected" => array("width" => 12.5663706144, "height" => 3,  "shape" => "rectangle", "amount" => 1)
		),
		array(
			"input" => array("height" => 8, "edges" => 1, "toplength" => 2, "bottomlength" => 2),
			"expected" => array("width" => 6.28318530718, "height" => 8,  "shape" => "rectangle", "amount" => 1)
		),

		//Cylinder on polygon
		array(
			"input" => array("height" => 4, "edges" => 3, "toplength" => 6, "bottomlength" => 6),
			"expected" => array("width" => 6, "height" => 4,  "shape" => "rectangle", "amount" => 3)
		),
		array(
			"input" => array("height" => 3, "edges" => 4, "toplength" => 4, "bottomlength" => 4),
			"expected" => array("width" => 4, "height" => 3,  "shape" => "rectangle", "amount" => 4)
		),
		array(
			"input" => array("height" => 8, "edges" => 5, "toplength" => 2, "bottomlength" => 2),
			"expected" => array("width" => 2, "height" => 8,  "shape" => "rectangle", "amount" => 5)
		)



	);


	function testCalculations() {
		
		for($i = 0; $i < sizeof($this->data); $i++)
		{
			$params = $this->data[$i]["input"];
			$sc = new SurfaceCalculator;	
			$sc->setHeight($params["height"]);
			$sc->setEdges($params["edges"]);
			$sc->setToplength($params["toplength"]);
			$sc->setBottomlength($params["bottomlength"]);
			$this->assertTrue($sc->isInputSane());
			$r = ($sc->calculateSurface());
			$this->assertTrue(mapCompare($r, $parms = $this->data[$i]["expected"]));
		}
	}
}



?>