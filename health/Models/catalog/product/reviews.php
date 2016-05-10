<?

	class ProductReviews
	{
		private $productID;
		
		public function getProductAverageRating($productID) {
			$sql = tep_db_query("SELECT AVG(reviews_rating) AS AverageRating FROM reviews WHERE products_id = $productID");
			$reviews = tep_db_fetch_array($sql);
			
			if (count($reviews) > 0) {
				return((int)$reviews['AverageRating']);
			} else {
				return(0);
			}
		}
		
		public function getReviewCount($productID) {
			$sql = tep_db_query("SELECT COUNT(reviews_rating) AS ReviewCount FROM reviews WHERE products_id = $productID");
			$reviews = tep_db_fetch_array($sql);		
			return((int)$reviews['ReviewCount']);
		}
		
	}
?>