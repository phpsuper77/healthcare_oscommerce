<?php

define('XNODE_TYPE_ROOT',0);
define('XNODE_TYPE_TEXT',1);
define('XNODE_TYPE_ATTR',2);
define('XNODE_TYPE_ELEM',3);
class xNode {
  var $childs;
  var $name;
  var $value;
  var $attributes;
  var $type;
  var $parent;
  function _safe_chars($text){
    $str = $text;
    $str = htmlspecialchars($str);
    $str = str_replace('&amp;amp;', '&amp;', $str);
    return $str;
  }
  function xNode( $vname, $type, $parent=null ) { 
    if ($type==XNODE_TYPE_TEXT) {
      $this->name = null;
      $this->value=$vname;        
    }else{
      $this->name = $vname;
      $this->value=null;    
    }
    $this->parent = &$parent;
    $this->type=$type;
    
    $this->childs = array();
    $this->attributes = array(); 
  }
//DOMNode append_child ( DOMNode newnode )
  function append_child( $newnode ){
    if ($newnode->type==XNODE_TYPE_ROOT) {
      //$newnode->type=XNODE_TYPE_ELEM;
      $newnode = $newnode->childs[0];
      $newnode->parent = &$this;
      switch ( $this->type ) {
        case XNODE_TYPE_ROOT:
        if ( count($this->childs)!=0 ) {
          $this->childs[0]->childs[] = $newnode;
          return $this->childs[0]->childs[ count($this->childs[0]->childs)-1 ];
        }
        case XNODE_TYPE_ELEM:
          $this->childs[] = $newnode;
          return $this->childs[ count($this->childs)-1 ];
        break;
        
      }
      /*
      foreach( $newnode->childs as $idx=>$newchild ) {
        $newchild->parent = &$this;
        $this->childs[] = $newchild;
      }*/
    }else{
      $newnode->parent = &$this;
      switch ( $this->type ) {
        case XNODE_TYPE_ROOT:
        if ( count($this->childs)!=0 ) {
          $this->childs[0]->childs[] = $newnode;
          return $this->childs[0]->childs[ count($this->childs[0]->childs)-1 ];
        }
        case XNODE_TYPE_ELEM:
          $this->childs[] = $newnode;
          return $this->childs[ count($this->childs)-1 ];
        break;
        
      }
      /*
      if ( $this->type==XNODE_TYPE_ROOT ) {
        if ( count($this->childs)!=0 ) {
          $this->childs[0]->childs[] = $newnode;
        }else{
          $this->childs[] = $newnode;
        }
      }else{
        $this->childs[] = $newnode;
      }
      */
    } 
    
    return $this->childs[ count($this->childs)-1 ];
  }
//DOMNode cloneNode ( [bool deep] )
//domelement DomNode->clone_node ( void )
  function clone_node( $deep=false ){
  
    $newNode = new xNode( $this->name, $this->type );
    $newNode->name = $this->name; 
    $newNode->value = $this->value;
    $newNode->type = $this->type;
    
    if ( $deep ) {
      // clone childs & attribs
     foreach ( $this->attributes as $attr ) {
       $newNode->set_attribute( $attr->name(), $attr->value() );
     }
     foreach( $this->childs as $child ) {
       $newNode->append_child( $child->clone_node($deep) );
     }

    }
    
    return $newNode;
  }
//domelement DomDocument->create_element ( string name )
  function create_element( $name ){
    return new xNode( $name,XNODE_TYPE_ELEM, $this ); 
  }
  function create_text_node( $name ){
    return new xNode( $name, XNODE_TYPE_TEXT, $this );
  }
 
  function document_element(){
    return $this->_document_element($this);  
  }
  function _document_element( $node ){
    if ( is_null($node->parent) && $node->type==XNODE_TYPE_ROOT ) {
      return $node->childs[0];
    }else{
      if ( !is_null($node->parent) ) {
        return $node->parent->document_element();
      }else{
        return false;
      }
    }
  }
  function name() {return $this->name;}
  function tagname() {return $this->name();}
  function value() {
    if (XNODE_TYPE_TEXT!=$node->type) {
     if (isset($this->childs[0]) && $this->childs[0]->type==XNODE_TYPE_TEXT) {
       return $this->childs[0]->value();
     }
    }
    return $this->value; //???
  }
  function get_content() { return $this->value(); } 
  function set_value($new_val) {$this->value = $new_val;return true;}
  function get_attribute($name){
    foreach ( $this->attributes as $attr ) if ( $attr->name()==$name ) return $attr->value();      
    return '';  
  }
  function get_elements_by_tagname($tagname){
    $ret = array();
    //$root = $this->document_element();
    $root = $this;
    $ret = $root->_get_elements_by_tagname($tagname);
    /*
    foreach($ret as $idx=>$reroot) {
      $newroot = domxml_new_doc('1.0');
      $newroot->append_child
    }*/
    return $ret; 
  }
  function _get_elements_by_tagname($tagname){
    $ret = array();
    if ( $this->name()==$tagname ) $ret[] = $this;
    foreach( $this->childs as $child ) $ret = array_merge($child->_get_elements_by_tagname($tagname),$ret);
    return $ret; 
  }
//DomAttribute set_attribute ( string name, string value )
  function set_attribute($name, $value){
    foreach ( $this->attributes as $attr ) {
      if ( $attr->name()==$name ) {
        $attr->set_value($value);
        return $attr;
      }
    }
    $attrib = new xAttribute( $name );
    $attrib->parent = $this;
    $attrib->set_value( $value );
    $this->attributes[] = $attrib;
    return $this->attributes[ count($this->attributes)-1 ];
  }
  function dump_mem($format=false){
    return $this->_dump_mem($format);
  }
  function _dump_mem($format=false,$deep=0){
    $line_br = '';    
    if ($format) {
      $line_br = "\n";
      $deep++;
    }
    $attr_line = '';

    switch ( $this->type ) {
      case XNODE_TYPE_TEXT:
        $ret = $this->_safe_chars($this->value());
      break;
      case XNODE_TYPE_ROOT:
        $ret = '<?xml version="'.$this->name().'"?'.'>'.$line_br;
        foreach( $this->childs as $child ) $ret .= $child->_dump_mem($format,$deep);
        $ret .= $line_br;
      break;
      case XNODE_TYPE_ELEM:
       foreach ( $this->attributes as $attr ) {
        if ( is_null($attr->value()) ) { $value = $attr->name(); }else{ $value = $attr->value(); } 
        $attr_line .= ' '.$attr->name().'="'.$this->_safe_chars($value).'"';
       }
       $ret = '<'.$this->name.$attr_line.'>';
       $close_br = '';
       if ($format) $deep++;
       foreach( $this->childs as $child ) {
          if ($child->type!=XNODE_TYPE_TEXT) { $ret .= $line_br.str_repeat(' ', $deep ); $close_br=$line_br; }
          $ret .= $child->_dump_mem($format,$deep);
       }
       $ret .= $close_br.'</'.$this->name.'>';
      break;
    }
    
    return $ret; 
  }
}
class xAttribute extends xNode {
  function xAttribute($attrName) { $this->xNode($attrName,XNODE_TYPE_ATTR); }
}

function domxml_new_doc($ver){
  return new xNode($ver, XNODE_TYPE_ROOT, null);
}
//====================================================================
class xXmlParser  {
    var $parser;
    var $cur_link;//???
    var $root;
    var $bad_chr;
    
    function xXmlParser() 
    {
        //$this->bad_chr = array("\x00" => "chr(0)", "\x01" => "chr(1)", "\x02" => "chr(2)", "\x03" => "chr(3)", "\x04" => "chr(4)", "\x05" => "chr(5)", "\x06" => "chr(6)", "\x07" => "chr(7)", "\x08" => "chr(8)", "\x09" => "chr(9)", "\x0a" => "chr(10)", "\x0b" => "chr(11)", "\x0c" => "chr(12)", "\x0d" => "chr(13)", "\x0e" => "chr(14)", "\x0f" => "chr(15)", "\x10" => "chr(16)", "\x11" => "chr(17)", "\x12" => "chr(18)", "\x13" => "chr(19)", "\x14" => "chr(20)", "\x15" => "chr(21)", "\x16" => "chr(22)", "\x17" => "chr(23)", "\x18" => "chr(24)", "\x19" => "chr(25)", "\x1a" => "chr(26)", "\x1b" => "chr(27)", "\x1c" => "chr(28)", "\x1d" => "chr(29)", "\x1e" => "chr(30)", "\x1f" => "chr(31)");
        $this->bad_chr = array('&'=>'chr(38)');
        $this->cur_link = null;
        $this->parser = xml_parser_create();

        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");
    }

    function parse($data){
      $this->root = domxml_new_doc("1.0");
      $this->cur_link = $this->root;
      $data = strtr($data, $this->bad_chr); 
      $this->bad_chr = array_flip($this->bad_chr);
      xml_parse($this->parser, $data);
      return $this->root;    
    }

    function tag_open($parser, $tag, $attributes){
      $tag = strtr($tag, $this->bad_chr);
      $node = new xNode( strtolower($tag), XNODE_TYPE_ELEM, $this->cur_link );
      foreach ($attributes as $aname => $avalue ) {
        $aname = strtr($aname, $this->bad_chr);
        $avalue = strtr($avalue, $this->bad_chr);
        $avalue = html_entity_decode($avalue);
        $node->set_attribute(strtolower($aname), $avalue);
      }
      $this->cur_link->append_child( $node );
      $this->cur_link = $node;
    }

    function cdata($parser, $cdata){
      $cdata = trim($cdata);
      $cdata = strtr($cdata, $this->bad_chr);
      //$cdata = str_replace('&amp;','&',$cdata);
      $cdata = html_entity_decode($cdata);
      if ( strlen($cdata)>0 ) $this->cur_link->append_child( $this->cur_link->create_text_node( $cdata ) );      
    }

    function tag_close($parser, $tag){ $this->cur_link = $this->cur_link->parent; }

} // end of class xml

function domxml_open_mem($input){
  $s = new xXmlParser();
  return $s->parse($input);
}      
?>