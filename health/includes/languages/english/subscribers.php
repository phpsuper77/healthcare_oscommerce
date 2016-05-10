<?php
/*
  $Id: create_account.php,v 1.7 2001/12/20 14:14:14 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/
define('CATEGORY_UNSUBSCRIBE', 'Unsubscribe');
define('NAVBAR_TITLE', 'Newsletter subscribers');
define('HEADING_TITLE', 'Newsletter subscribers');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTE:</b></font></small> If you already have an account with us, please login at the <a href="%s"><u>login page</u></a>.');
define('TEXT_ALREADY_SUBSCRIBED', 'You already subscribed.');
define('TEXT_ALREADY_REGISTERED', 'You already registered in system, please login and choose subscribed option at your profile.');
define('TEXT_CONGRATILATION_SUBSCRIBED', 'CONGRATULATIONS! You successfully subscribed for our newsletter.');
define('TEXT_CONGRATILATION_UNSUBSCRIBED', 'CONGRATULATIONS! You successfully unsubscribed from our newsletter.');
define('TEXT_REGISTERED_NEWSLETTER', 'You registered in system, please login and choose unsubscribed option at your profile.');
define('TEXT_NOT_REGISTERED_FOR_NEWSLETTER', 'There is no subscribed customer in our base with that e-mail address.');


define('JS_SPAMER_INVALID_FIRSTNAME', 'Please enter firstname.');
define('JS_SPAMER_INVALID_LASTNAME', 'Please enter lastname.');
define('JS_SPAMER_INVALID_EMAIL', 'It\\\'s seems you enter invalid email address.');


define('LETTER_SUBJ_SUBSCRIBED', 'You subscribed for newsletter at '.STORE_NAME);
define('LETTER_SUBSCRIBED', 'You successfully subscribed for newsletter at '.STORE_NAME.'. '."\n\n".'If you don\'t want to receive our hot news please visit our site and unsubscribe for newsletter');
define('LETTER_SUBJ_UNSUBSCRIBED', 'You unsubscribed for newsletter at '.STORE_NAME);
define('LETTER_UNSUBSCRIBED', 'You successfully unsubscribed for newsletter at '.STORE_NAME.'. '."\n\n");



?>