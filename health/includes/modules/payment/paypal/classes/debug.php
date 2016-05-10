<?php
/*
  $Id: debug.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Portions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class PayPal_Debug {

    function PayPal_Debug($email_address, $debug_enabled = 'No')
    {
      $this->email_address = $email_address;

      $this->enabled = ($debug_enabled == 'Yes') ? true : false;

      $this->error = false;

      $this->info = array();
    }

    function init($debug_str)
    {
      $this->add(DEBUG,sprintf(DEBUG_MSG,str_replace('&',"\n",$debug_str)));
    }

    function add($subject,$msg)
    {
      $this->info[] = array( 'subject' => $subject, 'msg' => $msg);
    }

    function error($subject, $msg)
    {
      unset($this->info);

      $this->add($subject,$msg);

      $this->error = true;
    }

    function info($html = false)
    {
      $debug_string = '';

      $lf = "\r\n";

      $debug = $this->info;

      reset($debug);

      $nMsgs = count($debug);

      for ($i=0; $i<$nMsgs; $i++)
        $debug_string .= EMAIL_SEPARATOR.$lf.$debug[$i]['subject'].$lf.EMAIL_SEPARATOR.$lf.str_replace("\n",$lf,$debug[$i]['msg']).$lf.$lf;

      return ($html === true) ? str_replace($lf, "\n<br />", $debug_string) : $debug_string;
    }

    function sendEmail()
    {
      if(count($this->info) > 0) {

        $to_name = '';

        $to_address = $this->email_address;

        $subject = MODULE_PAYMENT_PAYPAL_AGENT;

        $this->add(IPN_EMAIL,IPN_EMAIL_MSG."\n\n".EMAIL_SEPARATOR."\n".MODULE_PAYMENT_PAYPAL_AGENT.'/'.MODULE_PAYMENT_PAYPAL_AGENT_VERSION."\n");

        $msg = strip_tags(nl2br($this->info()));

        $from_name = MODULE_PAYMENT_PAYPAL_AGENT;

        $from_address = strtolower(trim($this->email_address));

        tep_mail($to_name, $to_address, $subject, $msg, $from_name, $from_address);

      }
    }

    function &display()
    {
      $page = paypal::newPage();

      $page->setTitle(HEADING_ITP_RESULTS_TITLE);

      $page->setContent( $this->displayResults($page) );

      return $page;
    }

    function displayResults(&$page)
    {
      $str = '<form name="ipn" method="GET" action="' . $_SERVER['HTTP_REFERER'] . '">'."\n".
             '<input type="hidden" name="action" value="itp">'."\n".
             '<table border="0" cellspacing="0" cellpadding="0" class="main">'."\n";

      if($this->error === false) {

        $str .= '  <tr>'."\n".
                '    <td>'."\n".
                '      <table border="0" cellspacing="0" cellpadding="0" style="padding: 4px; border:1px solid #aaaaaa; background: #ffffcc;">'."\n".
                '        <tr>'."\n".
                '          <td><br class="text_spacer"></td>'."\n".
                '          <td class="pperrorbold" style="text-align: center; width:100%;">'.  TEST_COMPLETE . '</td>'."\n".
                '        </tr>'."\n".
                '      </table>'."\n".
                '    </td>'."\n".
                '  </tr>'."\n";

        if($this->enabled === true)
          $str .= '  <tr>'."\n".
                  '    <td style="pptext">' . $this->info(true) . '</td>'."\n".
                  '  </tr>'."\n";

      } else {

        $str .= '  <tr>'."\n".
                '    <td>'."\n".
                '      <table border="0" cellspacing="0" cellpadding="0" style="padding: 4px; border:1px solid #aaaaaa; background: #ffffcc;">'."\n".
                '        <tr>'."\n".
                '          <td>' . $page->image('icon_error_40x40.gif',IMAGE_ERROR) . '</td>'."\n".
                '          <td><br class="text_spacer"></td>'."\n".
                '          <td class="pperrorbold" style="text-align: center; width:100%;">' . TEST_INCOMPLETE . '</td>'."\n".
                '        </tr>'."\n".
                '      </table>'."\n".
                '    </td>'."\n".
                '  </tr>'."\n".
                '  <tr><td><br class="h10"/></td></tr>'."\n".
                '  <tr>'."\n".
                '    <td class="ppsmalltext">' . TEST_INCOMPLETE_MSG . '</td>'."\n".
                '  </tr>'."\n".
                '  <tr><td><br class="h10"/></td></tr>'."\n";
      }

      $str .= '  <tr><td><hr class="solid"/></td></tr>'."\n".
              '  <tr><td class="buttontd"><input class="ppbuttonsmall" type="submit" name="submit" value="Continue"></td></tr>'."\n".
              '</table>'."\n".
              '</form>'."\n";

      return $str;
    }

  }//end class
?>