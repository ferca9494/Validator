<?php
/*
VALIDATOR v1.3
---------------------------------------------
*/

$regex_list = array(
	"id" => "/^[0-9]+$/",
	"int" => "/^[0-9]+$/",
	"float" => "/^\d*\.?\d*$/",
	"money" => "/^[0-9]+(\.[0-9]{1,2})?$/", // 10.25

	"sql_string" => "/^[A-Za-z0-9\s]+$/",				//sql_string
	"basic_string" => "/^[A-ZÀ-ÖØ-Þa-zß-öø-ÿ\s]+$/", 	//basic_string
	"alnum_string" => "/^[A-ZÀ-ÖØ-Þa-zß-öø-ÿ0-9\s]+$/",	//alnum_string
	"chara" => "/^[A-ZÀ-ÖØ-Þa-zß-öø-ÿ0-9.,_\-\s]+$/",	//chara_string

	"json_string" => "/^[A-ZÀ-ÖØ-Þa-zß-öø-ÿ0-9{}\[\]:,\"\s]+$/",

	"fullname" => "/^([A-ZÀ-ÖØ-Þ])([a-zß-öø-ÿ ]+) ([A-ZÀ-ÖØ-Þa-zß-öø-ÿ ]+)$/", // Cañete Fernando
	"fullname2" => "/^([A-ZÀ-ÖØ-Þ]+), ([A-ZÀ-ÖØ-Þa-zß-öø-ÿ ]+)$/u", // Cañete, Fernando
	"fullname2_html" => "/^([A-Z&#0-9a-z, ]+)$/u", // Ca&#209ete, Fernando
	"fulltext" => "/^([0-9A-ZÀ-ÖØ-Þa-zß-öø-ÿ .,?¿!¡_$#%&()=+{}_:;\-]+)$/", 

	"fullmail" => "/^[a-z0-9!\#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!\#$%&'*+=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/",
	"mail" => "/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/",
	"url" => "/^https?:\/\/[\w\-]+(\.[\w\-]+)+[\#?]?.*$/",
	
	"simple_escape_string" => '/(\\\\x00|\\\\n|\\\\r|\\\\|\'|"|\\\\x1a)/',
	"escape_string" => '/[\"\'`](?:(?<=")[^"\\\\]*(?s:\\\\.[^"\\\\]*)*"|(?<=\')[^\'\\\\]*(?s:\\\\.[^\'\\\\]*)*\'|[^`]*`)/',

	"date(dd/mm/aaaa)" => "/^(0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/]\d{4}$/", // 26/05/2021
	"date(dd/mm/aa)" => "/^(0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/]\d{2}$/",  // 26/05/21
);


class validator
{
	private $name;
	private $value;
	private $error_list = array();
	private $opcional = false;

	function name(string $name = "")
	{
		$this->name = $name;

		return $this;
	}

	function val($value = null,$opcional = false)
	{
		$this->value = $value;
		
		$this->opcional = $opcional;


		if ($this->opcional==false && !isset($value))
			array_push($this->error_list, "valor " . $this->name . "(" . $value . ") indefinido (" . $value . ")");

		return $this;
	}

	function range($min, $max)
	{
		$v = $this->value;
		if (is_numeric($v)) {
			if ($v < $min || $v > $max)
				array_push($this->error_list, "valor " . $this->name . "(" . $v . ") fuera de rango (min:" . $min . " max:" . $max . ")");
		}


		return $this;
	}

	function len($min, $max)
	{
		$v = $this->value;
		if (is_string($v)) {
			if (strlen($v) < $min || strlen($v) > $max)
				array_push($this->error_list, "valor " . $this->name . "(" . $v . ") fuera de largo (min:" . $min . " max:" . $max . ")");
		}

		return $this;
	}

	function max($r2)
	{
		return $this->range(0, $r2);
	}

	function min($r1)
	{
		return $this->range($r1, 2147483647);
	}

	function maxlen($r2)
	{
		return $this->len(0, $r2);
	}

	function minlen($r1)
	{
		if ($r1 < 0)
			$r1 = 0;
		return $this->len($r1, 65535);
	}

	private function is_pattern($value, $p)
	{
		foreach ($GLOBALS["regex_list"] as $k => $v) {
			if ($k == $p) {
				$patern = $GLOBALS["regex_list"][$p];
			}
		}

		if (!isset($patern)) {
			array_push($this->error_list, "patron inexistente");
			return 0;
		}

		return preg_match($patern, $value); //1 = coicide , 0 = no coicide
	}

	function pattern($p, $opcional = false)
	{
		$val = $this->value;

		if($opcional)
			$this->opcional = $opcional;

		if ($this->opcional == false && $this->is_pattern($this->value, $p) == 0)
			array_push($this->error_list, "valor " . $this->name . "(" . $val . ") no coicide con el patron (" . $p . ")"); //si es un patron custom se mostrara (arreglar)

		return $this;
	}

	function pattern_and($arrp)
	{
		$val = $this->value;
		foreach($arrp as $p)
		{
			if ($this->is_pattern($val, $p) == 0)
			{
				array_push($this->error_list, "valor " . $this->name . "(" . $val . ") no coicide con el patron (" . $p . ")"); //si es un patron custom se mostrara (arreglar)
				return $this;
			}
		}

		return $this;
	}

	function pattern_or($arrp)
	{
		$val = $this->value;
		$count = 0;
		foreach($arrp as $p)
		{
			if ($this->is_pattern($val, $p) == 0)		
				$count++;
			
		}

		if($count == count($arrp))
			array_push($this->error_list, "valor " . $this->name . "(" . $val . ") no coicide con ningun patron definido"); //si es un patron custom se mostrara (arreglar)

		return $this;
	}
	function arraypattern($p)
	{
		$val = $this->value;
		if (is_array($this->value)) {
			$cont = 0;
			foreach ($this->value as $v) {
				if ($this->is_pattern($v, $p) == 0)
					$cont++;
			}

			//si no todos coiciden
			if ($cont < count($this->value))
				array_push($this->error_list, "arreglo " . $this->name . "(" . $val . ") no coicide con el patron (" . $p . ")");
		} else {
			array_push($this->error_list, "arreglo " . $this->name . "(" . $val . ") no es arreglo y no se puede comparar con el patron");
		}

		return $this;
	}

	function array()
	{
		$val = $this->value;
		if (!is_array($val)) {
			array_push($this->error_list, "arreglo " . $this->name . "(" . $val . ") no es arreglo");
		}
		return $this;
	}

	function arrayempty()
	{
		$val = $this->value;
		if (is_array($val) && count($val) == 0) {
			array_push($this->error_list, "arreglo " . $this->name . "(" . $val . ") es un arreglo vacio");
		}
		return $this;
	}

	function belongs($array)
	{
		$val = $this->value;
		if (is_array($array)) {
			$flag = 0;
			foreach ($array as $v) {
				if ($this->value == $v)
					$flag = 1;
			}

			//si no coicide por lo menos con uno
			if ($flag == 0)
				array_push($this->error_list, "valor " . $this->name . "(" . $val . ") no pertenece a arreglo (" . json_encode($array) . ")");
		}
		return $this;
	}

	function required()
	{
		$v = $this->value;
		if ($this->value == null || $this->value == "")
			array_push($this->error_list, "valor " . $this->name . "(" . $v . ") requerido");

		return $this;
	}


	function ajax_req()
	{
		//es la mejor forma de saber si es ajax
		//pero tambien es insegura

		if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
			array_push($this->error_list, "Petición denegada code:I065!");
		}

		return $this;
	}
	function a_referer($where)
	{

		$referer = strtolower($_SERVER['HTTP_REFERER']);
		$whereLow = strtolower($where);

		$url = $_SERVER["SERVER_NAME"];

		$wurl = "www." . $url;

		$url_A = "https://" . $wurl;
		$url_B = "https://" . $url;
		$url_C = "http://" . $wurl;
		$url_D = "http://" . $url;

		$url_referer = array(
			$url_A . $whereLow,
			$url_A . $whereLow . "/",
			$url_B . $whereLow,
			$url_B . $whereLow . "/",
			$url_C . $whereLow,
			$url_C . $whereLow . "/",
			$url_D . $whereLow,
			$url_D . $whereLow . "/"
		);

		if (array_search($referer, $url_referer) === false) {
			return false;
		}

		return true;
	}

	function referer($array_where)
	{
		if (empty($_SERVER['HTTP_REFERER'])) {
			array_push($this->error_list, "(no referer) Petición denegada code:I066!");
			return $this;
		}

		if (is_array($array_where)) {
			foreach ($array_where as $wher) {
				if ($this->a_referer($wher))
					return $this;
			}
			array_push($this->error_list, "(invalid referer) Petición denegada code:I066!");
		} else
			if (!$this->a_referer($array_where))
			array_push($this->error_list, "(invalid referer) Petición denegada code:I066!");

		return $this;
	}

	function clear_errors()
	{
		foreach ($this->error_list as $k => $val)
			unset($this->error_list[$k]);

		return $this;
	}
	function display_errors()
	{
		foreach ($this->error_list as $k => $val)
			echo "<p>Validator ERROR => " . $val . "</p>";

		return $this;
	}
	function validate($option = null)
	{
		if (count($this->error_list) == 0) //no errores
			return 1;

		if ($option == null || $option != "noclear")
			$this->clear_errors();

		return 0;
	}
}
