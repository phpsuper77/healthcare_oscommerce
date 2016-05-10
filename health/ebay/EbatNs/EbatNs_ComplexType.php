<?php
// $Id: EbatNs_ComplexType.php,v 1.3 2008/07/09 11:40:35 oes Exp $
/* $Log: EbatNs_ComplexType.php,v $
/* Revision 1.3  2008/07/09 11:40:35  oes
/* Improved name guessing for flattened array element names
/*
/* Revision 1.2  2008/02/27 12:37:53  oes
/* Renamed parameter for handling of values that evaluate to false
/*
/* Revision 1.1  2007/05/31 11:38:00  michael
/* - initial checkin
/* - version < 513
/*
 * 
 * 4     5.07.06 8:00 Mcoslar
 * 
 * 3     13.02.06 17:03 Charnisch
 * 
 * 2     3.02.06 10:44 Mcoslar
 * 
 * 1     30.01.06 12:11 Charnisch
 */
require_once 'EbatNs_SimpleType.php';

class EbatNs_ComplexType extends EbatNs_SimpleType
{ 
	// an array of SimpleTypes / ComplexTypes  (child-elements)
	var $_elements = array(); 
	// will define wheather the data is stored in the value-field (as a assoc array)
	// or either in Members of an object
	var $_dataInValueArray = false;

	function EbatNs_ComplexType( $name, $nsURI )
	{
		$this->EbatNs_SimpleType( $name, $nsURI );
	} 
	// will serialize the given value
	// and return XML-data.
	// give preserveNull = true to enforce serialization of values that evaluate to false (null or 0 or '')
	// we assume a value also as empty if the any child elements will not return any data.
	function serialize( $elementName, $value, $attributeValues, $preserveNull, $typeName, &$dataConverter )
	{ 
		$ret = '';
		// lets decide where we are getting the data from
		if ( $this->_dataInValueArray )
		{
			$ret = '';
			foreach ( $value as $key => $data )
			{
				if ( isset( $data->attributeValues ) )
					$attributeValues = $data->attributeValues;
				else
					$attributeValues = null;

				if ( is_a( $data, 'EbatNs_SimpleType' ) )
				{
					$ret .= $data->serialize( $key, $data, $attributeValues, $preserveNull, null, $dataConverter );
				} 
				else
					$ret .= EbatNs_SimpleType::serialize( $key, $data, $attributeValues, $preserveNull, null, $dataConverter );
			} 
		} 
		else
		{
			if ( count( $this->_elements ) == 0 )
			{
				$ret = $this->value;
			} 
			else
				foreach ( $this->_elements as $childElementName => $childTypeInfo )
			{
				$childValue = $this->
				{
					$childElementName} ;
				if ( isset( $childTypeInfo['type'] ) )
					$childType = $childTypeInfo['type'];
				else
					$childType = null;

				if ( is_array( $childValue ) )
				{
					$needArraySurrounding = null;
					foreach ( $childValue as $arrayElementValue )
					{
						if ( isset( $arrayElementValue->attributeValues ) )
							$childAttributeValues = $arrayElementValue->attributeValues;
						else
							$childAttributeValues = null;

						if ( is_a( $childValue, 'EbatNs_SimpleType' ) )
						{ 
							$ret .= $childValue->serialize( $childElementName, $arrayElementValue, $childAttributeValues, $preserveNull, $childType, $dataConverter ); 
						} 
						else
						{ 
							if (is_object($arrayElementValue))
							{
								// hack to guess the original element name out of
								// the type name of the array element
								if (!$childTypeInfo['array'])
								{
									 list($questedName) = split('Type', $arrayElementValue->_typeName);
									
									/*
									$ret .= '<' . $childElementName . '>' 
										 . $arrayElementValue->serialize( $questedName, $arrayElementValue, $childAttributeValues, $preserveNull, $childType, $dataConverter ) 
										 . '</' . $childElementName . '>';
									*/
									$needArraySurrounding = $childElementName;	 
									$ret .= $arrayElementValue->serialize( $questedName, $arrayElementValue, $childAttributeValues, $preserveNull, $childType, $dataConverter ) ;
								}
								else
								{
									$ret .= $arrayElementValue->serialize( $childElementName, $arrayElementValue, $childAttributeValues, $preserveNull, $childType, $dataConverter );
								}
							}
							else
								$ret .= EbatNs_SimpleType::serialize( $childElementName, $arrayElementValue, $childAttributeValues, $preserveNull, $childType, $dataConverter );
						} 
					}
					
					if ($needArraySurrounding !== null)
					{
						$ret = '<' . $needArraySurrounding . '>' . $ret . '</' . $needArraySurrounding . '>';
					}
				}  
				else
				{
					if ( isset( $childValue->attributeValues ) )
						$childAttributeValues = $childValue->attributeValues;
					else
						$childAttributeValues = null;

					if ( is_a( $childValue, 'EbatNs_SimpleType' ) )
					{ 
						$ret .= $childValue->serialize( $childElementName, $childValue, $childAttributeValues, $preserveNull, $childType, $dataConverter ); 
					} 
					else
					{ 
						$ret .= EbatNs_SimpleType::serialize( $childElementName, $childValue, $childAttributeValues, $preserveNull, $childType, $dataConverter );
					} 
				} // plain
			} 
		} 
		$ret = '<' . $elementName . $this->_getAttributeString( $attributeValues ) . '>' . $ret . '</' . $elementName . '>';
		return $ret;
	} 
} 
?>