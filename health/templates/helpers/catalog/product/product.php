<?
include("Models/catalog/product/reviews.php");


class ProductHelper {
	
	public $messages = array();
	
	private function get_cutofftime() {
		if (date("w") >= 1 && date('w') <= 5) {
			$message = "Order within xxx yyy for next working day delivery";
			$end_time = mktime(14,0,0);
			//$start_time = mktime(9,0,0);
			$start_time = time();
			
			$difference = $end_time - $start_time;
			
			if ($difference < 0) {
				$cutofftime_past = true;
				$difference = $start_time - $end_time;
				$message = "Order within xxx yyy for delivery on the next available delivery day";
			}
			
			$hours = $difference / 3600; // 3600 seconds in an hour
			$minutes = ($hours - floor($hours)) * 60; 
			$hours = floor($hours);
			$minutes = round($minutes);		
			
			if ($cutofftime_past == true) {				
				if ($hours < 12) {
					$hours = 12 + (12 - ($hours));
				}
			}
			
			if ($hours > 0) $message = str_replace('xxx', $hours.' hours and', $message); 
				else $message = str_replace('xxx', '', $message); 
				
			$message = str_replace('yyy', $minutes.' minutes', $message); 
			$cutoff_message = $message;
			
			$this->messages['cutoff_time'] = $cutoff_message;
			
		}
	}
	
	
	function __construct() {
		$this->get_cutofftime();
	}
	
}


$productHelper = new ProductHelper();

?>