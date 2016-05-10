<?php
/*
$Id: protx_cc_validation.php

Copyright (c) 2006 Holbi LLP

http://www.holbi.co.uk

Author: Vladislav B. Malyshev
e-mail: vmalyshev@holbi.co.uk

Description: extanded class for most CC. 
*/

  class protx_cc_validation {
    var $cc_type, $cc_number, $cc_expiry_month, $cc_expiry_year;

    function validate($number, $expiry_m, $expiry_y, $supported_cc) {
      $this->cc_number = ereg_replace('[^0-9]', '', $number);

      if (ereg('^4[0-9]{12}([0-9]{3})?$', $this->cc_number)) {
        $this->cc_type = 'Visa';
      } elseif (ereg('^5[1-5][0-9]{14}$', $this->cc_number)) {
        $this->cc_type = 'Mastercard';
      } elseif (ereg('^3[47][0-9]{13}$', $this->cc_number)) {
        $this->cc_type = 'American Express';
      } elseif (ereg('^3(0[0-5]|[68][0-9])[0-9]{11}$', $this->cc_number)) {
        $this->cc_type = 'Diners Club';
      } elseif (ereg('^6011[0-9]{12}$', $this->cc_number)) {
        $this->cc_type = 'Discover';
      } elseif (ereg('^(3[0-9]{4}|2131|1800)[0-9]{11}$', $this->cc_number)) {
        $this->cc_type = 'JCB';
      } elseif (ereg('^5610[0-9]{12}$', $this->cc_number)) { 
        $this->cc_type = 'Australian BankCard';
      } elseif (ereg('^49030[2-9]{1}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Switch';
      } elseif (ereg('^49033[5-9]{1}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Switch';
      } elseif (ereg('^49110[1-2]{1}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Switch';
      } elseif (ereg('^49117[4-9]{1}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Switch';
      } elseif (ereg('^49118[0-2]{1}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Switch';
      } elseif (ereg('^4936[0-9]{2}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Switch';
      } elseif (ereg('^564182([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'Maestro';
      } elseif (ereg('^6333[1-4]{1}[0-9]{1}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Switch';
      } elseif (ereg('^6759[0-9]{2}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'Maestro';
      } elseif (ereg('^6334[5-9]{1}[0-9]{1}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Solo';
      } elseif (ereg('^6767[0-9]{2}([0-9]{10}|[0-9]{12}|[0-9]{13})$', $this->cc_number)) {
        $this->cc_type = 'UK Switch';
      } elseif (ereg('^633110[0-9]{10}$', $this->cc_number)) {
        $this->cc_type = 'UK Solo';
      } else {
        return -1;
      }

      if(!in_array($this->cc_type, $supported_cc))
        return -1;

      if (is_numeric($expiry_m) && ($expiry_m > 0) && ($expiry_m < 13)) {
        $this->cc_expiry_month = $expiry_m;
      } else {
        return -2;
      }

      $current_year = date('Y');
      $expiry_y = substr($current_year, 0, 2) . $expiry_y;
      if (is_numeric($expiry_y) && ($expiry_y >= $current_year) && ($expiry_y <= ($current_year + 10))) {
        $this->cc_expiry_year = $expiry_y;
      } else {
        return -3;
      }

      if ($expiry_y == $current_year) {
        if ($expiry_m < date('n')) {
          return -4;
        }
      }

      return $this->is_valid();
    }

    function is_valid() {
      $cardNumber = strrev($this->cc_number);
      $numSum = 0;

      for ($i=0; $i<strlen($cardNumber); $i++) {
        $currentNum = substr($cardNumber, $i, 1);

// Double every second digit
        if ($i % 2 == 1) {
          $currentNum *= 2;
        }

// Add digits of 2-digit numbers together
        if ($currentNum > 9) {
          $firstNum = $currentNum % 10;
          $secondNum = ($currentNum - $firstNum) / 10;
          $currentNum = $firstNum + $secondNum;
        }

        $numSum += $currentNum;
      }

// If the total has no remainder it's OK
      return ($numSum % 10 == 0);
    }
  }
?>