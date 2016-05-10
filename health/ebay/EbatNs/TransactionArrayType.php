<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'TransactionType.php';
require_once 'EbatNs_ComplexType.php';

class TransactionArrayType extends EbatNs_ComplexType
{
	// start props
	// @var TransactionType $Transaction
	var $Transaction;
	// end props

/**
 *

 * @return TransactionType
 * @param  $index 
 */
	function getTransaction($index = null)
	{
		if ($index) {
		return $this->Transaction[$index];
	} else {
		return $this->Transaction;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setTransaction($value, $index = null)
	{
		if ($index) {
	$this->Transaction[$index] = $value;
	} else {
	$this->Transaction = $value;
	}

	}
/**
 *

 * @return 
 */
	function TransactionArrayType()
	{
		$this->EbatNs_ComplexType('TransactionArrayType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Transaction' =>
				array(
					'required' => false,
					'type' => 'TransactionType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
