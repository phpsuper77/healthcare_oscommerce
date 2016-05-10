<?
class ProductViews {
	
	public function getBestSellersQuery($args) {
		if ($args['limit'] > 0) {
			$limit_sql = " LIMIT ".$args['limit']." ";
		}
		
		
		if (isset($args['category_id']) && ($args['category_id'] > 0)) {
			$best_sellers_query = "select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$args['category_id'] . "' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name ".$limit_sql;
		} else {
			$best_sellers_query = "select distinct 
				p.products_id, 
				pd.products_name,
				p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id order by p.products_ordered desc, pd.products_name ".$limit_sql;
		}
		return($best_sellers_query);
	}
	
}
?>