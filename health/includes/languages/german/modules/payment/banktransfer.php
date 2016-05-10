<?php
/*
  $Id: banktransfer.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  OSC German Banktransfer
  (http://www.oscommerce.com/community/contributions,826)

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 - 2003 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_TITLE', 'Lastschriftverfahren');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_DESCRIPTION', 'Lastschriftverfahren');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK', 'Bankeinzug');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_EMAIL_FOOTER', 'Hinweis: Sie k�nnen sich unser Faxformular unter ' . HTTP_SERVER . DIR_WS_CATALOG . MODULE_PAYMENT_BANKTRANSFER_URL_NOTE . ' herunterladen und es ausgef�llt an uns zur�cksenden.');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_INFO', 'Bitte beachten Sie, dass das Lastschriftverfahren <b>nur</b> von einem <b>deutschen Girokonto</b> aus m�glich ist');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_OWNER', 'Kontoinhaber:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_NUMBER', 'Kontonummer:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_BLZ', 'BLZ:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_NAME', 'Bank:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_FAX', 'Einzugserm�chtigung wird per Fax best�tigt');

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR', '<font color="#FF0000">FEHLER: </font>');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_1', 'Kontonummer und BLZ stimmen nicht �berein!<br>Bitte �berpr�fen Sie Ihre Angaben nochmals.');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_2', 'F�r diese Kontonummer ist kein Pr�fziffernverfahren definiert!');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_3', 'Kontonummer nicht pr�fbar!');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_4', 'Kontonummer nicht pr�fbar!<br>Bitte �berpr�fen Sie Ihre Angaben nochmals.');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_5', 'Bankleitzahl nicht gefunden!<br>Bitte �berpr�fen Sie Ihre Angaben nochmals.');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_8', 'Fehler bei der Bankleitzahl oder keine Bankleitzahl angegeben!');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_BANK_ERROR_9', 'Keine Kontonummer angegeben!');

  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_NOTE', 'Hinweis:');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_NOTE2', 'Wenn Sie aus Sicherheitsbedenken keine Bankdaten �ber das Internet<br>�bertragen wollen, k�nnen Sie sich unser ');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_NOTE3', 'Faxformular');
  define('MODULE_PAYMENT_BANKTRANSFER_TEXT_NOTE4', ' herunterladen und uns ausgef�llt zusenden.');

  define('JS_BANK_BLZ', 'Bitte geben Sie die BLZ Ihrer Bank ein!\n');
  define('JS_BANK_NAME', 'Bitte geben Sie den Namen Ihrer Bank ein!\n');
  define('JS_BANK_NUMBER', 'Bitte geben Sie Ihre Kontonummer ein!\n');
  define('JS_BANK_OWNER', 'Bitte geben Sie den Namen des Kontobesitzers ein!\n');
?>
