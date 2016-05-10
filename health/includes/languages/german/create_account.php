<?php
/*
  $Id: create_account.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Konto erstellen');

define('HEADING_TITLE', 'Kontodaten');

define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>ACHTUNG:</b></font></small> Wenn Sie bereits ein Konto besitzen, so melden Sie sich bitte <a href="%s"><u><b>hier</b></u></a> an.');

define('EMAIL_SUBJECT', 'Willkommen zu ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Sehr geehrter Herr ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Sehr geehrte Frau ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Sehr geehrte ' . stripslashes($HTTP_POST_VARS['firstname']) . ',' . "\n\n");
define('EMAIL_WELCOME', 'Willkommen zu <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Zu Ihrer Verf&uuml;gung steht jetzt unser <b>Online-Service</b>. Der Service bietet unter anderem:' . "\n\n" . '<li><b>Kundenwarenkorb</b> - Jeder Artikel bleibt registriert, bis Sie zur Kasse gehen oder die Produkte im Warenkorb l&ouml;schen.' . "\n" . '<li><b>Adressbuch</b> - Wir k&ouml;nnen jetzt die Produkte zu der von Ihnen ausgesuchten Adresse senden. Der perfekte Weg, ein Geburtstagsgeschenk zu versenden.' . "\n" . '<li><b>Vorherige Bestellungen</b> - Sie k�nnen jederzeit Ihre vorherigen Bestellungen �berpr�fen.' . "\n" . '<li><b>Produktbewertung</b> - Teilen Sie Ihre Meinung zu unseren Produkten  anderen Kunden mit.' . "\n\n");
define('EMAIL_CONTACT', 'Falls Sie Fragen mit unserem Kunden-Service haben, wenden Sie sich bitte an den Vertrieb: ' . tep_get_clickable_link(STORE_OWNER_EMAIL_ADDRESS) . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Achtung:</b> Diese E-Mail-Adresse wurde uns von einem Kunden bekannt gegeben. Falls Sie sich nicht angemeldet haben, senden Sie bitte eine E-Mail an ' . tep_get_clickable_link(STORE_OWNER_EMAIL_ADDRESS) . '.' . "\n");
/* ICW Credit class gift voucher begin */
define('EMAIL_GV_INCENTIVE_HEADER', 'Als kleines Willkommensgeschenk senden wir Ihnen einen Gutschein nber %s');
define('EMAIL_GV_REDEEM', 'Ihr pers�nlicher Gutscheincode lautet %s. Sie k�nnen diese Gutschrift entweder wShrend dem Bestellvorgang verbuchen');
define('EMAIL_GV_LINK', 'oder direkt nber diesen Link: ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Herzlich Willkommen in unserem Webshop. Fnr Ihren ersten Einkauf verfngen Sie nber einen kleinen Einkaufsgutschein,' . "\n" .
                                        ' alle notwendigen Informationen diesbeznglich finden Sie hier:' . "\n\n");
define('EMAIL_COUPON_REDEEM', 'Geben Sie einfach Ihren pers�nlichen Code   %s wShrend des Bezahlvorganges ' . "\n" .
                               'ein');

/* ICW Credit class gift voucher end */
?>
