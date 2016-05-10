<?
Class FrontController {
	
	public $canonical_tag;	
	
	protected $model;	
	
	public function dumpArray($array) {
		print "<pre>";
		print_r($array);
		print "</pre>";
	}

	public function get_canonical_tag() {
		if ($_GET['products_id'] != "" || $_GET['cPath_name'] != "") return("");
		if (strtolower($_SERVER['SCRIPT_NAME']) !== "/index.php") {
			$this->canonical_tag = strtolower("http://".$_SERVER["SERVER_NAME"].$_SERVER['SCRIPT_NAME']);
		} else {
			$this->canonical_tag = strtolower("http://".$_SERVER["SERVER_NAME"]."/");
		}
	}
}

?>