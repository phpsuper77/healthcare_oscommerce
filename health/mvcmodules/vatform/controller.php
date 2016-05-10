<?php
/**
 * User: Musaffar Patel
 */

class VatFormFrontController extends module {

	public function display() {
		include_once(dirname(__FILE__)."/form.php");
	}

	public function js() {
		print '<script src="//rawgithub.com/noelboss/featherlight/master/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>';
	}

	public function css() {
		print '<link href="//rawgithub.com/noelboss/featherlight/master/release/featherlight.min.css" type="text/css" rel="stylesheet" title="Featherlight Styles" />';
	}

}

