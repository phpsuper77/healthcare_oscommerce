<?php
// $Id: EbatNs_DataConverter.php,v 1.4 2008/03/27 18:02:08 oes Exp $
/* $Log: EbatNs_DataConverter.php,v $
/* Revision 1.4  2008/03/27 18:02:08  oes
/* Untested fix for unwanted useof objects as strings in encodeData()
/*
/* Revision 1.3  2008/02/27 14:10:16  oes
/* Fixed a stupid one
/*
/* Revision 1.2  2008/02/27 12:39:57  oes
/* Ensured proper wrapping of strings in CDATA sections
/*
/* Revision 1.1  2007/05/31 11:38:00  michael
/* - initial checkin
/* - version < 513
/*
 * 
 * 3     3.02.06 10:44 Mcoslar
 * 
 * 2     30.01.06 16:44 Mcoslar
 * �nderungen eingef�gt
*/
	class EbatNs_DataConverter
	{
		var $_options = array();
		function EbatNs_DataConverter()
		{
		}
		
		function decodeData($data, $type = 'string')
		{
			switch ($type)
			{
				case 'boolean':
					if ($data == 'true')
						return true;
					else
						return null;
			}
			return $data;
		}
		
		function encodeData($data, $type = 'string', $elementName = null)
		{
			return ("<![CDATA[" . $data . "]]>");
		}
		
		function encryptData($data, $type = null)
		{
			return $data;
		}
		
		function decryptData($data, $type = null)
		{
			return $data;
		}
	}
	
	class EbatNs_DataConverterUtf8 extends EbatNs_DataConverter
	{
		function EbatNs_DataConverterUtf8()
		{
			$this->EbatNs_DataConverter();
		}
	}
	
	class EbatNs_DataConverterIso extends EbatNs_DataConverter
	{
		function EbatNs_DataConverterIso()
		{
			$this->EbatNs_DataConverter();
		}

		function decodeData($data, $type = 'string')
		{
			switch ($type)
			{
				case 'string':
					return utf8_decode($data);
				case 'dateTime':
					{
						$dPieces = split('T', $data);
						$tPieces = split("\.", $dPieces[1]);
						$data = $dPieces[0] . ' ' . $tPieces[0];
						
						// return date('Y-m-d H:i:s', strtotime($data) + date('Z'));
						return $data;
					}
				case 'boolean':
					if ($data == 'true')
						return true;
					else
						return null;
				default:
					return $data;	
			}
		}
		
		function encodeData($data, $type = 'string', $elementName = null)
		{
			switch ($type)
			{
				case 'dateTime':
					$time = strtotime($data);
					$data = gmdate("Y-m-d\\TH:i:s.000\\Z", $time);
					break;

				case 'boolean':
				case 'int':
				case (substr($type, 0, -8) . 'CodeType'):
					break;
					
				default:
					if (is_string($data)) $data = "<![CDATA[" . utf8_encode($data) . "]]>";
					break;
			}
			
			return $data;
		}
	}
?>