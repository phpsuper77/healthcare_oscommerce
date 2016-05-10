<?
include_once(DIR_FS_CATALOG.'/controllers/front/FrontController.php');
include_once(DIR_FS_CATALOG.'/classes/ProductReviews.php');

Class ProductReviewsController extends FrontController {
	
	public function __construct() {
		$this->model = new ProductReviewsModel();
	}
	
	public function get_canonical_tag($id_product, $id_review="") {
		if ($id_review == "" && is_numeric($id_product)) {
			$canonical_tag = HTTP_SERVER."/product_reviews.php?products_id=".$id_product;
			$canonical_tag = ($canonical_tag != "" ? $canonical_tag : false);
			$this->canonical_tag = $canonical_tag;
			return strtolower($canonical_tag);
		} elseif (is_numeric($id_review) && is_numeric($id_product)) {
			$canonical_tag = HTTP_SERVER."/product_reviews_info.php?products_id=".$id_product."&reviews_id=".$id_review;
			$canonical_tag = ($canonical_tag != "" ? $canonical_tag : false);
			$this->canonical_tag = $canonical_tag;
			return strtolower($canonical_tag);
		}
	}
	
}

?>