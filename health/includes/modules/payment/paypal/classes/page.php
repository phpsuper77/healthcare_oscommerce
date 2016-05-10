<?php
/*
  $Id: page.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/

  class PayPal_Page {

    var $baseDirectory,
        $pageTitle,
        $metaTitle;

    function PayPal_Page()
    {
      $this->setBaseDirectory(dirname(realpath(dirname(__FILE__) . '/../../paypal.php')) . '/paypal/');

      $this->setBaseURL('includes/modules/payment/paypal/');

      $this->addCss('general.css');

      $this->addCss('stylesheet.css');

      $this->addJavascript('general.js');

      $this->setTitle('osCommerce: PayPal_Shopping_Cart_IPN');

      $this->setMetaTitle('osCommerce: PayPal_Shopping_Cart_IPN');

      $this->setTemplate('default');
    }

    function template()
    {
      return $this->baseDirectory . 'content/templates/' . basename($this->templateName) . '.php';
    }

    function setTemplate($template)
    {
      $this->templateName = basename($template);
    }

    function setContentFile($contentFile = '')
    {
      $this->contentFile = $this->baseDirectory . 'content/pages/'. basename($contentFile) . '.php';

      $this->contentType = 'file';
    }

    function setContent($content)
    {
      $this->content = $content;

      $this->contentType = 'string';
    }

    function setContentLangaugeFile($file)
    {
      global $language;

      $base_dir = $this->baseDirectory . 'languages/';

      $file = basename($file);

      if (file_exists($base_dir . $language . '/' . $file . '.php'))

        $this->contentFile = $base_dir . $language . '/' . $file . '.php';

      elseif (file_exists($base_dir . '/english/' . $lng_file . '.php'))

        $this->contentFile =  $base_dir . '/english/' . $lng_file . '.php';

      $this->contentType = 'file';
    }

    function setTitle($title = '')
    {
      $this->pageTitle = $this->outputString($title);
    }

    function setMetaTitle($title)
    {
      $this->metaTitle = $this->outputString($title);
    }

    function setOnLoad($javascript)
    {
      $this->onLoad = $this->outputString($javascript);
    }

    function includeLanguageFile($file)
    {
      global $language;

      $base_dir = $this->baseDirectory . '/languages/';

      $file = basename($file);

      if (file_exists($base_dir . $language . '/' . $file . '.php')) {

        require_once($base_dir . $language . '/' . $file . '.php');

      } elseif (file_exists($base_dir . 'english/' . $file . '.php')) {

        require_once($base_dir . 'english/' . $file . '.php');

      }
    }

    function setBaseDirectory($dir)
    {
      $this->baseDirectory = $dir;
    }

    function setBaseURL($location)
    {
      $this->baseURL = $location;
    }

    function imagePath($image)
    {
      return $this->baseURL. 'images/'.$image;
    }

    function image($img,$alt='', $width = '', $height = '', $parameters = '')
    {
      return tep_image($this->imagePath($img),$alt,$width,$height,$parameters);
    }

    function hrefLink($page = '', $parameters = '', $connection = 'NONSSL')
    {
      return tep_href_link($page, $parameters, $connection);
    }

    function javascriptLink($ppURLText, $ppURLParams = '', $ppURL = '', $js = true)
    {
      if (empty($ppURL) === true)
        $ppURL = FILENAME_PAYPAL;

      $ppURL = PayPal_Page::hrefLink($ppURL,$ppURLParams);

      if ($js === true)
        $ppScriptLink = '<script language="javascript" type="text/javascript">'."\n".'<!--'."\n".'document.write("<a style=\"color: #0033cc; text-decoration: none;\" href=\"javascript:openPayPalWindow(\''.str_replace('"','\"',$ppURL).'\');\" tabindex=\"-1\">'.str_replace('"','\"',$ppURLText).'</a>");'."\n".'-->'."\n".'</script>'."\n".'<noscript><a style="color: #0033cc; text-decoration: none;" href="'.PayPal_Page::outputString($ppURL).'" target="paypalWindow">'.$ppURLText.'</a></noscript>'."\n";
      else
        $ppScriptLink = '<a style="color: #0033cc; text-decoration: none;" href="'.PayPal_Page::outputString($ppURL).'" target="paypalWindow">'.$ppURLText.'</a>';

      return $ppScriptLink;
    }

    function addJavascript($filename)
    {
      $this->javascript[] = basename($filename);
    }

    function javascript()
    {
      if(is_array($this->javascript) === true && empty($this->javascript) === false) {

        for($i=0,$n = count($this->javascript); $i<$n; $i++)
          $javascript .= '<script language="javascript" src="' . $this->baseURL . 'content/' . basename($this->javascript[$i]) . '"></script>'."\n";

        return $javascript;

      }
    }

    function addCss($filename)
    {
      $this->css[] = basename($filename);
    }

    function css()
    {
      if (is_array($this->css) === true && empty($this->css) === false) {

        $css = "<style type='text/css' media=\"all\">\n";

        $cssCount = count($this->css);

        for($i=0, $n = count($this->css); $i<$n; $i++)
          $css .= "@import url(" . $this->baseURL . 'content/' . basename($this->css[$i]) . ");\n";

        return $css."</style>\n";

      }
    }

    function outputString($string)
    {
      return strtr(trim($string), array('"' => '&quot;'));
    }

    function copyright()
    {
      /*
        The following is a courtesy copyright notice.
      */
      return "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">"."\n".
             "<tr><td><br class=\"h10\"/></td></tr>"."\n".
             "<tr><td align=\"center\" class=\"ppfooter\">E-Commerce Engine Copyright &copy; 2000-2004 <a href=\"http://www.oscommerce.com\" class=\"copyright\" target=\"_blank\">osCommerce</a><br/>osCommerce provides no warranty and is redistributable under the <a href=\"http://www.fsf.org/licenses/gpl.txt\" class=\"copyright\" target=\"_blank\">GNU General Public License</a></td></tr>"."\n".
             "<tr><td><br class=\"h10\"/></td></tr><tr><td align=\"center\" class=\"ppfooter\"><a href=\"http://www.oscommerce.com\" target=\"_blank\" class=\"poweredByButton\"><span class=\"poweredBy\">Powered By</span><span class=\"osCommerce\">" . PROJECT_VERSION . "</span></a></td></tr><tr><td><br class=\"h10\"/></td></tr></table>";
    }
  }//end class
?>