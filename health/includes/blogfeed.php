<?
require(DIR_WS_CLASSES . 'social/blogfeed.php');
class BlogFeedClass extends BlogFeed {
	
	function __construct($feedURL, $limit=5) {
		parent::__construct($feedURL, $limit);		
	}
    
    protected function _fetchFeed(){		
		parent::_fetchFeed();
		print $this->_url;
	
        return($this->items);
    }
}

$blogfeed = new BlogFeedClass('http://www.healthcare4all.co.uk/blog/feed/',3);
$items = $blogfeed->__get('items');
?>

<div class="blog-feed">
	<span class="title">&raquo; &nbsp;from our blog</span>
	<? foreach($items as $key=>$item) : ?>
		<div class="blog-item">
			<div class="title">
				<a href="<?=$item->link;?>">
					<?=$item->title?>
				</a>
			</div>
			<div class="description">
				<?
					if (strlen($item->description) < 40) print utf8_decode($item->description);
					else print substr(utf8_decode($item->description),0,40)."...";
				?><br>
				<a href="<?=$item-link;?>">read more</a>				
			</div>
		</div>
	<? endforeach; ?>
</div>