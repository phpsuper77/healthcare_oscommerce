<?
class BlogItem {
	protected $_title;
	protected $_description;
	protected $_link;
	protected $_image;	
	
	public function __construct($title, $description, $link, $image) {
		$this->_title = $title;
		$this->_description = $description;
		$this->_link = $link;		
		$this->image = $image;
	}
	
	public function __get($key){
		switch ($key){
			case "title":
				return $this->_title;
				break;
			case "link":
				return $this->_link;
				break;
			case "description":
				return $this->_description;
				break;
			case "image":
				return $this->_image;
				break;								
			default:
				return null;
				break;
		}
	}
	
	public function __isset($key){
		return (!is_null($this->__get($key)));
	}
	
	public function __set($key, $value){
		switch ($key){
			case "title":
				$this->_title = $value;
				break;
			case "description":
				$this->_description = $value;
				break;
			case "link":
				$this->_description = $value;
				break;
			case "image":
				$this->_image = $value;
				break;			
			default:
				return false;
				break;
		}
	}	
	
}

class BlogFeed {
	
	private $_items;
	private $_feedurl;
	private $_limit;

	
	public function __construct($feedurl, $limit){		
		$this->_feedurl = $feedurl;
		$this->_limit = $limit;
		$this->_items = $this->_fetchFeed();		
	}
	
	protected function _fetchFeed(){
		$ch = curl_init($this->_feedurl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		
		try {
			$doc = new SimpleXmlElement($data, LIBXML_NOCDATA);
		} catch (Exception $e) {
			return(false);
		}
		
		$cnt = count($doc->channel->item);
		for($i=0; $i<$cnt; $i++) {			
			$title = (string)$doc->channel->item[$i]->title;
			if (strlen(strip_tags($title)) > 100) $title = substr(strip_tags($title),0,100)."...";
			$description = (string)$doc->channel->item[$i]->description;
			$description = substr(strip_tags($description),0,200)."...";
			$description = str_replace('&#8230;', '...', $description);
			
			# extract first image
			preg_match_all('/<img [^>]*src=["|\']([^"|\']+)/i', (string)$doc->channel->item[$i]->description, $matches);
			$image = $matches[1][0];
			$item = new BlogItem(
				(string)$title,
				(string)$description,
				(string)$doc->channel->item[$i]->link,
				(string)$image
			);
			$this->_items[] = $item;
			if ($i >= $this->_limit-1) break;			
	    }	
		return $this->_items;
	}
	
	public function __get($key){
		switch ($key){
			case 'items':
				return $this->_items;
				break;
			default:
				foreach ($this->_items as $item){
					if ($item->name == $key){
						return $item;
					}
				}
				return null;
		}
	}
	
	public function __isset($key){
		return (!is_null($this->__get($key)));
	}
	
}
?>