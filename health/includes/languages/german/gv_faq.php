<?php
/*
  $Id: gv_faq.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
  $Id: gv_faq.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Gutscheine - Fragen und Antworten');
define('HEADING_TITLE', 'Gutscheine - Fragen und Antworten');

define('TEXT_INFORMATION_GV', '<a name="Top"></a>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=1','NONSSL').'">Gutscheine kaufen</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=2','NONSSL').'">Wie man Gutscheine versendet</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=3','NONSSL').'">Mit Gutscheinen Einkaufen</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=4','NONSSL').'">Gutscheine verbuchen</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=5','NONSSL').'">Falls es zu Problemen kommen sollte :</a><br>
');
switch ($HTTP_GET_VARS['faq_item']) {
  case '1':
define('SUB_HEADING_TITLE','Gutscheine kaufen');
define('SUB_HEADING_TEXT','Gutscheine k&ouml;nnen, falls sie im Shop angeboten werden, wie normale Artikel gekauft werden.
  Sobald Sie einen Gutschein gekauft haben und dieser nach erfolgreicher Zahlung freigeschaltet wurde, erscheint der Betrag unter Ihrem Warenkorb. Nun k&ouml;nnen Sie &uuml;ber den Link " Gutschein versenden " den gew&uuml;nschten Betrag per E-Mail versenden.');
  break;
  case '2':
define('SUB_HEADING_TITLE','Wie man Gutscheine versendet');
define('SUB_HEADING_TEXT','Um einen Gutschein zu versenden, klicken Sie bitte auf den Link" Gutschein versenden " in Ihrem Einkaufskorb.
  Um einen Gutschein zu versenden, ben&ouml;tigen wir folgende Angaben von Ihnen:
  Vor- und Nachname des Empf&auml;ngers.
  Eine g&uuml;ltige E-Mail Adresse des Empf&auml;ngers.
  Den gew&uuml;nschten Betrag ( Dieser Betrag kann auch unter Ihrem Guthaben liegen ).)
  Eine kurze Nachricht an den Empf&auml;nger.
  Bitte &uuml;berpr&uuml;fen Sie Ihre Angaben noch einmal vor dem Versenden. Sie haben vor dem Versenden jederzeit die M&ouml;glichkeit Ihre Angaben zu korrigieren.');
  break;
  case '3':
  define('SUB_HEADING_TITLE','Mit Gutscheinen Einkaufen.');
  define('SUB_HEADING_TEXT','Sobald Sie &uuml;ber ein Guthaben verf&uuml;gen, k&ouml;nnen Sie dieses zum Bezahlen Ihrer Bestellung verwenden. W&auml;hrend des Bestellvorganges haben Sie die M&ouml;glichkeit Ihr Guthaben einzul&ouml;sen.
  Falls das Guthaben unter dem Warenwert liegt m&uuml;ssen Sie Ihre bevorzugte Zahlungsweise f&uuml;r den Differenzbetrag w&auml;hlen.
  &Uuml;bersteigt Ihr Guthaben den Warenwert, steht Ihnen das Restguthaben selbstverst&auml;ndlich f&uuml;r Ihre n&auml;chste Bestellung zur Verf&uuml;gung.');
  break;
  case '4':
  define('SUB_HEADING_TITLE','Gutscheine verbuchen.');
  define('SUB_HEADING_TEXT','Wenn Sie einen Gutschein per E-Mail erhalten haben, k&ouml;nnen Sie den Betrag wie folgt verbuchen:.<br>
  1. Klicken Sie auf den in der E-Mail angegebenen Link. Falls Sie noch nicht über ein pers&ouml;nliches Kundenkonto verf&uuml;gen, haben Sie die M&ouml;glichkeit ein Konto zu er&ouml;ffnen.<br>
  2. W&auml;hrend des Bestellvorganges k&ouml;nnen Sie den Code auf der Seite " Zahlungsweise " manuell eingeben. Bitte verbuchen Sie zuerst Ihren Gutschein und w&auml;hlen dann die gew&uuml;nschte Zahlungsweise.');
  break;
  case '5':
  define('SUB_HEADING_TITLE','Falls es zu Problemen kommen sollte:');
  define('SUB_HEADING_TEXT','Falls es wider Erwarten zu Problemen mit einem Gutschein kommen sollte, kontaktieren Sie uns bitte
  per E-Mail : '. STORE_OWNER_EMAIL_ADDRESS . '. Bitte beschreiben Sie m&ouml;glichst genau das Problem, wichtige Angaben sind unter anderem : Ihre Kundennummer, der Gutscheincode, Fehlermeldungen des Systems sowie der von Ihnen benutzte Browser. ');
  break;
  default:
  define('SUB_HEADING_TITLE','');
  define('SUB_HEADING_TEXT','Bitte w&auml;hlen Sie aus den obigen Fragen.');

  }
?>
