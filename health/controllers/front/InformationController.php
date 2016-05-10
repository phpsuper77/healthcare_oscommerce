<?
include_once(DIR_FS_CATALOG.'/controllers/front/FrontController.php');

Class InformationController extends FrontController {
	
	public function __construct() {
	}
	
	public function get_canonical_tag() {
		if ($_GET['info_name'] != "") {
			$this->canonical_tag = strtolower("http://".$_SERVER["SERVER_NAME"]."/".str_replace(" ", "+", $_GET['info_name'].".html"));
		}
	}
	
}

?>