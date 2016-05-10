<?

class Module {

	public static function getInstance($instanceName) {
		$instance = new $instanceName;
		return $instance;
	}

}

?>