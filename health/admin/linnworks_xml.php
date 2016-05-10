<?php

header("Content-type: text/html; charset=utf-8");
error_reporting(0);
	define('DB_HOST','mysql51');
	define('DB_USER','health4all2');
	define('DB_PASS','Diamatrox3281');
	define('DB_NAME','health4all2');
	define('PASSWORD','954995ef-7695-44dd-bde4-c60f32c8395e');

$db = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('<?xml version="1.0"?><error><![CDATA[' . mysql_error(). ']]></error>');
	mysql_select_db(DB_NAME, $db) or die('<?xml version="1.0"?><error><![CDATA[' . mysql_error(). ']]></error>');
	mysql_query("SET NAMES 'utf8'") or die('<?xml version="1.0"?><error><![CDATA[' . mysql_error(). ']]></error>'); ;
    mysql_query("SET SESSION SQL_BIG_SELECTS=1;") or die('<?xml version="1.0"?><error><![CDATA[' . mysql_error(). ']]></error>');

    if ($_GET["password"]!=PASSWORD){exit('<?xml version="1.0"?><error><![CDATA[Password is incorrect!]]></error>');}


	$xmlP = new Queries(str_replace('\\"','"',str_replace("\\'","'",urldecode($_POST['query']))));

	$xmlP->parseQueries();

	foreach($xmlP->queries as $k=>$v)
	{
	    $queries = split("--GO;--",$v);
        foreach($queries as $q){
			$q = htmlspecialchars_decode($q);
		    $result = mysql_query($q);
        }

		if($xmlP->request == 'RETURN'){
			$xmlP->select($result, $k);
		} else {
			$xmlP->exec($result, $k);
		}
	}

	$xmlP->prepareReturn();

	echo('<?xml version="1.0"?>'.$xmlP->xmlReturn);



	class Queries
	{
		private $xml;
		private $xmlQueries;
		public  $request;
		public  $queries    = array();
		public  $ids        = array();
		public  $return     = array();
		public  $xmlReturn  = array();

		public function __construct($xml)
		{
			$this->xml = $xml;
		}

		public function parseQueries()
		{
			preg_match('/<queries type="([^"]+)">(.+)<\/queries>/s', $this->xml, $m);
			$this->request    = $m[1];

			$this->xmlQueries = $m[2];

			preg_match_all('/<query id="([^"]+)">(.+?)<\/query>/s', $this->xmlQueries, $m);
			$this->ids     = $m[1];
			$this->queries = $m[2];

		}

		public function select($res, $k)
		{
			if(!mysql_num_rows($res) && mysql_error()!=""){
				$this->return[$this->ids[$k]]['status'] = 'ERROR';
				$this->return[$this->ids[$k]]['error'] =  mysql_error();
			} else {
				$this->return[$this->ids[$k]]['status'] = 'OK';
				while($row = mysql_fetch_array($res))
				{
					foreach($row as $key=>$val)
					{
						if(preg_match('/^[0-9]+$/',$key)) unset($row[$key]);
					}
					$this->return[$this->ids[$k]][] = $row;
				}
			}
		}

		public function exec($res, $k)
		{
			if($res || mysql_error()==""){
				$this->return[$this->ids[$k]]['status'] = 'OK';
			} else {
				$this->return[$this->ids[$k]]['status'] = 'ERROR';
				$this->return[$this->ids[$k]]['error'] = mysql_error();
			}
		}

		public function prepareReturn()
		{
			$this->xmlReturn = '<resultset>';
			foreach($this->return as $k=>$v)
			{
				$this->xmlReturn.= '<return id="'.$k.'" status="'.$v['status'].'">';
				if($v['status'] == 'ERROR'){
					$this->xmlReturn.= '<errormessage>'.$v['error'].'</errormessage>';
				} else {
					foreach($v as $key=>$val)
					{
						if(preg_match('/^[0-9]+$/',$key)){
							$this->xmlReturn.= '<row>';
							foreach($val as $recId=>$recValue)
							{
								$this->xmlReturn.= "<$recId><![CDATA[$recValue]]></$recId>";
							}
							$this->xmlReturn.= '</row>';
						}
					}
				}
				$this->xmlReturn.= '</return>';
			}
			$this->xmlReturn.= '</resultset>';
		}


	}
?>