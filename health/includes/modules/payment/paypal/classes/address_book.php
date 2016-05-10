<?php
/*
  $Id: address_book.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Portions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class PayPal_Address_Book {

    function addressFormatId($country_id)
    {
      $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = " . (int)$country_id);

      if (tep_db_num_rows($address_format_query)) {

        $address_format = tep_db_fetch_array($address_format_query);

        return $address_format['format_id'];

      } else {

        return '1';

      }
    }

    function addressLabel($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n")
    {
      $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = " . (int)$customers_id . " and address_book_id = " . (int)$address_id);

      $address = tep_db_fetch_array($address_query);

      $format_id = PayPal_Address_Book::addressFormatId($address['country_id']);

      return tep_address_format($format_id, $address, $html, $boln, $eoln);
    }

  }//end class
?>