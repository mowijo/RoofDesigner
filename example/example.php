<?

include(dirname(__FILE__)."/../src/SurfaceCalculator.php");
include(dirname(__FILE__)."/../src/SvgCreator.php");

$sc = new SurfaceCalculator;
$sc->setHeight(5);
$sc->setEdges(5);
$sc->setToplength(2);
$sc->setBottomlength(5);

if(!$sc->isInputSane()) 	die("Input not sane: ".$sc->latestErrorMessage()."\n");

$r = $sc->calculateSurface();
print_r($r);/*
$svg = new SvgCreator;
if (! $svg->generate($r) ) die("Could not generate SVG: ".$svg->latestErrorMessage()."\n");
$svg->save("out.svg");
*/
?>