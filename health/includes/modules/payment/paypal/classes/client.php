<?php
/*
  $Id: client.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Portions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class PayPal_Client {

    function PayPal_Client() {}

    function getResponse($domain)
    {
      global $debug;

      $response = $this->fsockopen('ssl://',$domain,'443');

      if (empty($response))
        $response = $this->curl_exec($domain);

      if (empty($response))
        $response = $this->fopen('https://'.$domain.'/cgi-bin/webscr?'.$this->response_string);

      if (empty($response))
        $response = $this->fsockopen('tcp://',$domain,'80');

      if (empty($response)) {

        $response = @file('http://'.$domain.'/cgi-bin/webscr?'.$this->response_string);

        if (!$response && ($debug->enabled))
          $debug->add(HTTP_ERROR,sprintf(HTTP_ERROR_MSG,'','','',''));

      }

      if ($debug->enabled) {

        $debug->add(PAYPAL_RESPONSE,sprintf(PAYPAL_RESPONSE_MSG,$this->getVerificationResponse($response)));

        $debug->add(CONNECTION_TYPE,sprintf(CONNECTION_TYPE_MSG,$this->curl_flag,$this->transport,$domain,$this->port));

      }

      unset($this->response_string,$this->curl_flag,$this->transport,$this->port);

      return $response;
    }

    function validateReceiverEmails($receiver_email,$business,$other)
    {
      global $debug;

      $receiverEmails = explode(',',strtolower($receiver_email));
      $businessIds = array_merge(array(strtolower($business)),explode(',',strtolower($other)));

      if (in_array(strtolower($this->key['receiver_email']),$receiverEmails) && in_array(strtolower($this->key['business']),$businessIds)) {

        if ($debug->enabled)
          $debug->add(EMAIL_RECEIVER,sprintf(EMAIL_RECEIVER_MSG,implode(', ',$receiverEmails),implode(', ',$businessIds),$this->key['receiver_email'],$this->key['business']));

        return true;

      } else {

        if ($debug->enabled)
          $debug->add(EMAIL_RECEIVER,sprintf(EMAIL_RECEIVER_ERROR_MSG,implode(', ',$receiverEmails),implode(', ',$businessIds),$this->key['receiver_email'],$this->key['business'],$this->key['txn_id']));

        return false;
      }
    }

    function validPayment($amount,$currency)
    {
      global $debug;
      $valid_payment = true;
      //check the payment currency and amount
      if ( ($this->key['mc_currency'] != $currency) || ($this->key['mc_gross'] != $amount) )
        $valid_payment = false;

      if ($valid_payment === false && $debug->enabled)
        $debug->add(CART_TEST,sprintf(CART_TEST_ERR_MSG,$amount,$currency,$this->key['mc_gross'],$this->key['mc_currency']));

      return $valid_payment;
    }

    function dienice($status = '200')
    {
      switch($status) {
        case '200';
          header("HTTP/1.1 200 OK");
          break;
        case '500':
        default:
          if ($this->validDigest()) {
            header("HTTP/1.1 204 No Content"); exit;
          } else {
            header("HTTP/1.1 500 Internal Server Error"); exit;
          }
          break;
      }
    }

    function digest()
    {
      return strrev(md5(md5(strrev(md5(MODULE_PAYMENT_PAYPAL_IPN_DIGEST_KEY)))));
    }

    function validDigest()
    {
      return (isset($this->key['digest']) && $this->key['digest'] === $this->digest());
    }

    function setTestMode($testMode)
    {
      switch($testMode) {
        case 'On':
          $this->testMode = 'On';
          break;
        default:
          $this->testMode = 'Off';
        break;
      }
    }

    function testMode($testMode='')
    {
      if (empty($testMode) === false)
        return ($this->testMode === $testMode);
      elseif (isset($this->testMode) === true)
        return ($this->testMode === 'On');

      return FALSE;
    }

    function getVerificationResponse($response)
    {
      if (is_array($response) === true) {

        return @$response[0];

      } elseif (is_string($response) === true) {

        $array = explode("\r\n",$response);

        return @$array[0];

      }

      return FALSE;
    }

    function getRequestBodyContents(&$handle)
    {
      $headerdone = false;

      if ($handle) {

        while(feof($handle) === false) {

          $line = @fgets($handle, 1024);

          if (strcmp($line, "\r\n") === 0) {

            $headerdone = true;

          } elseif ($headerdone) {

            $line = str_replace("\r\n",'',$line);

            if (in_array($line,array('VERIFIED','INVALID')) === true)
              return $line;

          } elseif (in_array($line,array('VERIFIED','INVALID')) === true) {

            return $line;

          }

        }

      }

      return FALSE;
    }

    function curl_exec($domain)
    {
      $response = '';

      $this->curl_flag = function_exists('curl_exec');

      if ( $this->curl_flag === true) {

        $ch = @curl_init();

        @curl_setopt($ch,CURLOPT_URL, "https://$domain/cgi-bin/webscr");
        @curl_setopt($ch,CURLOPT_POST, 1);
        @curl_setopt($ch,CURLOPT_POSTFIELDS, $this->response_string);
        @curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($ch,CURLOPT_HEADER, 0);
        @curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
        @curl_setopt($ch,CURLOPT_TIMEOUT, 60);

        $response = @curl_exec($ch);

        @curl_close($ch);

      }

      return $response;
    }

    function fsockopen($transport,$domain,$port)
    {
      $response = '';

      $this->transport = $transport;

      $this->port = $port;

      $fp = @fsockopen($transport.$domain,$port, $errno, $errstr, 30);

      if ($fp) {

        $header = "POST /cgi-bin/webscr HTTP/1.1\r\n" .
                  "Host: {$domain}\r\n" .
                  "From: " . MODULE_PAYMENT_PAYPAL_BUSINESS_ID . "\r\n" .
                  "User-Agent: " . MODULE_PAYMENT_PAYPAL_AGENT . "/" . MODULE_PAYMENT_PAYPAL_AGENT_VERSION . "\r\n" .
                  "Content-type: application/x-www-form-urlencoded\r\n" .
                  "Content-length: " . strlen($this->response_string) . "\r\n" .
                  "Connection: close\r\n\r\n";

        @fputs($fp, $header . $this->response_string);

        $response = $this->getRequestBodyContents($fp);

        @fclose($fp);

      }

      return $response;
    }

    function fopen($filename)
    {
      $response = '';

      $fp = @fopen($filename,'rb');

      if ($fp) {

        $response = $this->getRequestBodyContents($fp);

        @fclose($fp);

      }

      return $response;
    }

  }//end class
?>