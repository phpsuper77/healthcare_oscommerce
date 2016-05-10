<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'CountryCodeType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'AddressStatusCodeType.php';
require_once 'AddressOwnerCodeType.php';
require_once 'AddressRecordTypeCodeType.php';

class AddressType extends EbatNs_ComplexType
{
	// start props
	// @var string $Name
	var $Name;
	// @var string $Street
	var $Street;
	// @var string $Street1
	var $Street1;
	// @var string $Street2
	var $Street2;
	// @var string $CityName
	var $CityName;
	// @var string $County
	var $County;
	// @var string $StateOrProvince
	var $StateOrProvince;
	// @var CountryCodeType $Country
	var $Country;
	// @var string $CountryName
	var $CountryName;
	// @var string $Phone
	var $Phone;
	// @var CountryCodeType $PhoneCountryCode
	var $PhoneCountryCode;
	// @var string $PhoneCountryPrefix
	var $PhoneCountryPrefix;
	// @var string $PhoneAreaOrCityCode
	var $PhoneAreaOrCityCode;
	// @var string $PhoneLocalNumber
	var $PhoneLocalNumber;
	// @var CountryCodeType $Phone2CountryCode
	var $Phone2CountryCode;
	// @var string $Phone2CountryPrefix
	var $Phone2CountryPrefix;
	// @var string $Phone2AreaOrCityCode
	var $Phone2AreaOrCityCode;
	// @var string $Phone2LocalNumber
	var $Phone2LocalNumber;
	// @var string $PostalCode
	var $PostalCode;
	// @var string $AddressID
	var $AddressID;
	// @var AddressOwnerCodeType $AddressOwner
	var $AddressOwner;
	// @var AddressStatusCodeType $AddressStatus
	var $AddressStatus;
	// @var string $ExternalAddressID
	var $ExternalAddressID;
	// @var string $InternationalName
	var $InternationalName;
	// @var string $InternationalStateAndCity
	var $InternationalStateAndCity;
	// @var string $InternationalStreet
	var $InternationalStreet;
	// @var string $CompanyName
	var $CompanyName;
	// @var AddressRecordTypeCodeType $AddressRecordType
	var $AddressRecordType;
	// @var string $FirstName
	var $FirstName;
	// @var string $LastName
	var $LastName;
	// @var string $Phone2
	var $Phone2;
	// end props

/**
 *

 * @return string
 */
	function getName()
	{
		return $this->Name;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setName($value)
	{
		$this->Name = $value;
	}
/**
 *

 * @return string
 */
	function getStreet()
	{
		return $this->Street;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStreet($value)
	{
		$this->Street = $value;
	}
/**
 *

 * @return string
 */
	function getStreet1()
	{
		return $this->Street1;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStreet1($value)
	{
		$this->Street1 = $value;
	}
/**
 *

 * @return string
 */
	function getStreet2()
	{
		return $this->Street2;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStreet2($value)
	{
		$this->Street2 = $value;
	}
/**
 *

 * @return string
 */
	function getCityName()
	{
		return $this->CityName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCityName($value)
	{
		$this->CityName = $value;
	}
/**
 *

 * @return string
 */
	function getCounty()
	{
		return $this->County;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCounty($value)
	{
		$this->County = $value;
	}
/**
 *

 * @return string
 */
	function getStateOrProvince()
	{
		return $this->StateOrProvince;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStateOrProvince($value)
	{
		$this->StateOrProvince = $value;
	}
/**
 *

 * @return CountryCodeType
 */
	function getCountry()
	{
		return $this->Country;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCountry($value)
	{
		$this->Country = $value;
	}
/**
 *

 * @return string
 */
	function getCountryName()
	{
		return $this->CountryName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCountryName($value)
	{
		$this->CountryName = $value;
	}
/**
 *

 * @return string
 */
	function getPhone()
	{
		return $this->Phone;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhone($value)
	{
		$this->Phone = $value;
	}
/**
 *

 * @return CountryCodeType
 */
	function getPhoneCountryCode()
	{
		return $this->PhoneCountryCode;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhoneCountryCode($value)
	{
		$this->PhoneCountryCode = $value;
	}
/**
 *

 * @return string
 */
	function getPhoneCountryPrefix()
	{
		return $this->PhoneCountryPrefix;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhoneCountryPrefix($value)
	{
		$this->PhoneCountryPrefix = $value;
	}
/**
 *

 * @return string
 */
	function getPhoneAreaOrCityCode()
	{
		return $this->PhoneAreaOrCityCode;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhoneAreaOrCityCode($value)
	{
		$this->PhoneAreaOrCityCode = $value;
	}
/**
 *

 * @return string
 */
	function getPhoneLocalNumber()
	{
		return $this->PhoneLocalNumber;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhoneLocalNumber($value)
	{
		$this->PhoneLocalNumber = $value;
	}
/**
 *

 * @return CountryCodeType
 */
	function getPhone2CountryCode()
	{
		return $this->Phone2CountryCode;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhone2CountryCode($value)
	{
		$this->Phone2CountryCode = $value;
	}
/**
 *

 * @return string
 */
	function getPhone2CountryPrefix()
	{
		return $this->Phone2CountryPrefix;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhone2CountryPrefix($value)
	{
		$this->Phone2CountryPrefix = $value;
	}
/**
 *

 * @return string
 */
	function getPhone2AreaOrCityCode()
	{
		return $this->Phone2AreaOrCityCode;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhone2AreaOrCityCode($value)
	{
		$this->Phone2AreaOrCityCode = $value;
	}
/**
 *

 * @return string
 */
	function getPhone2LocalNumber()
	{
		return $this->Phone2LocalNumber;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhone2LocalNumber($value)
	{
		$this->Phone2LocalNumber = $value;
	}
/**
 *

 * @return string
 */
	function getPostalCode()
	{
		return $this->PostalCode;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPostalCode($value)
	{
		$this->PostalCode = $value;
	}
/**
 *

 * @return string
 */
	function getAddressID()
	{
		return $this->AddressID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAddressID($value)
	{
		$this->AddressID = $value;
	}
/**
 *

 * @return AddressOwnerCodeType
 */
	function getAddressOwner()
	{
		return $this->AddressOwner;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAddressOwner($value)
	{
		$this->AddressOwner = $value;
	}
/**
 *

 * @return AddressStatusCodeType
 */
	function getAddressStatus()
	{
		return $this->AddressStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAddressStatus($value)
	{
		$this->AddressStatus = $value;
	}
/**
 *

 * @return string
 */
	function getExternalAddressID()
	{
		return $this->ExternalAddressID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setExternalAddressID($value)
	{
		$this->ExternalAddressID = $value;
	}
/**
 *

 * @return string
 */
	function getInternationalName()
	{
		return $this->InternationalName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setInternationalName($value)
	{
		$this->InternationalName = $value;
	}
/**
 *

 * @return string
 */
	function getInternationalStateAndCity()
	{
		return $this->InternationalStateAndCity;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setInternationalStateAndCity($value)
	{
		$this->InternationalStateAndCity = $value;
	}
/**
 *

 * @return string
 */
	function getInternationalStreet()
	{
		return $this->InternationalStreet;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setInternationalStreet($value)
	{
		$this->InternationalStreet = $value;
	}
/**
 *

 * @return string
 */
	function getCompanyName()
	{
		return $this->CompanyName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCompanyName($value)
	{
		$this->CompanyName = $value;
	}
/**
 *

 * @return AddressRecordTypeCodeType
 */
	function getAddressRecordType()
	{
		return $this->AddressRecordType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAddressRecordType($value)
	{
		$this->AddressRecordType = $value;
	}
/**
 *

 * @return string
 */
	function getFirstName()
	{
		return $this->FirstName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFirstName($value)
	{
		$this->FirstName = $value;
	}
/**
 *

 * @return string
 */
	function getLastName()
	{
		return $this->LastName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setLastName($value)
	{
		$this->LastName = $value;
	}
/**
 *

 * @return string
 */
	function getPhone2()
	{
		return $this->Phone2;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPhone2($value)
	{
		$this->Phone2 = $value;
	}
/**
 *

 * @return 
 */
	function AddressType()
	{
		$this->EbatNs_ComplexType('AddressType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Name' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Street' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Street1' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Street2' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CityName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'County' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'StateOrProvince' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Country' =>
				array(
					'required' => false,
					'type' => 'CountryCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CountryName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Phone' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PhoneCountryCode' =>
				array(
					'required' => false,
					'type' => 'CountryCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PhoneCountryPrefix' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PhoneAreaOrCityCode' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PhoneLocalNumber' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Phone2CountryCode' =>
				array(
					'required' => false,
					'type' => 'CountryCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Phone2CountryPrefix' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Phone2AreaOrCityCode' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Phone2LocalNumber' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PostalCode' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AddressID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AddressOwner' =>
				array(
					'required' => false,
					'type' => 'AddressOwnerCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AddressStatus' =>
				array(
					'required' => false,
					'type' => 'AddressStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ExternalAddressID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'InternationalName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'InternationalStateAndCity' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'InternationalStreet' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CompanyName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AddressRecordType' =>
				array(
					'required' => false,
					'type' => 'AddressRecordTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'FirstName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'LastName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Phone2' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
