<?php class SvgStyle
{
	private $strokecolor_ = "";
	private $strokewidth_ = "";
	private $fillcolor_ = "";
	private $strokelinecap_ = "";
	private $strokelinejoin_  = "";
	private $strokeopacity_ = "";
	private $textanchor_ = "";
	private $fontsize_ = "";

	private $strokedasharray_ = array();
	private $strokedashoffset_ = 0;


	static $defaultfontsize_ = "5mm";


	function __construct()
	{
		$this->fontsize_ = SvgStyle::$defaultfontsize_;
		echo "I create a font with dfs = ".SvgStyle::$defaultfontsize_."\n"; 
	}

	static function setDefaultFontSize($dfs)
	{
		SvgStyle::$defaultfontsize_ = $dfs;
echo "I set DFS = dfs = ".SvgStyle::$defaultfontsize_."\n"; 
		
	}

	function setStrokeColor($c)
	{
		$this->strokecolor_ = $c;
	}
	
	function setStrokeWidth($c)
	{
		$this->strokewidth_ = $c;
	}

	function setStrokeLineCap($lc)
	{
		$this->strokelinecap_ = $lc;
	}

	function setStrokeLineJoin($lj)
	{
		$this->strokelinejoin_ = $lj;
	}

	function setStrokeOpacity($so)
	{
		$this->strokeopacity_ = $so;
	}

	function setFillColor($c)
	{
		$this->fillcolor_ = $c;
	}

	function setTextAnchor($a)
	{
		$this->textanchor_ = $a;
	}

	function setFontSize($fs)
	{
		$this->fontsize_ = $fs;
	}

//stroke-dasharray:1.772,1.772;stroke-dashoffset:0
	function setStrokeDashArray($a)
	{
		if (! is_array($a)) throw new Exception("A is not an array!");
		$this->strokedasharray_ = $a;
	}

	function setStrokeDashOffset($o)
	{
		$this->strokedashoffset_ = $o;
	}


	function toString()
	{
		//stroke:black; fill:none;
		$a = array();
		if($this->strokecolor_ != "") array_push($a, "stroke:".$this->strokecolor_);
		if($this->fillcolor_ != "") array_push($a, "fill:".$this->fillcolor_);
		if($this->strokewidth_ != "") array_push($a, "stroke-width:".$this->strokewidth_);
		if($this->strokelinecap_ != "") array_push($a, "stroke-linecap:".$this->strokelinecap_);
		if($this->strokelinejoin_ != "") array_push($a, "stroke-linejoin:".$this->strokelinejoin_);
		if($this->strokeopacity_ != "") array_push($a, "stroke-opacity:".$this->strokeopacity_);
		if($this->textanchor_ != "") array_push($a, "text-anchor:".$this->textanchor_);
		if($this->fontsize_ != "") array_push($a, "font-size:".$this->fontsize_);
	
		if	( sizeof($this->strokedasharray_) > 1) 
		{
			array_push($a, "stroke-dashoffset:".$this->strokedashoffset_);
			array_push($a, "stroke-dasharray:".implode(",",$this->strokedasharray_));
		}
		return implode(";", $a);
	}







}
?>