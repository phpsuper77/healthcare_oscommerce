<?php echo tep_draw_form('vendor_signup',  tep_href_link(FILENAME_VENDOR_SIGNUP, '', 'SSL'), 'post') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>     
        </td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD

// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  if (isset($HTTP_GET_VARS['vendor_email_address'])) $a_email_address = tep_db_prepare_input($HTTP_GET_VARS['vendor_email_address']);
  $vendor['vendor_country_id'] = STORE_COUNTRY;

?>
<?php

  if (!isset($is_read_only)) $is_read_only = false;
  if (!isset($processed)) $processed = false;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="main">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_PERSONAL);

  new contentBoxHeading($info_box_contents, false, false);

?>      
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
<?php
  if (ACCOUNT_GENDER == 'true') {
    $male = ($vendor['vendor_gender'] == 'm') ? true : false;
    $female = ($vendor['vendor_gender'] == 'f') ? true : false;
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_GENDER; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo ($vendor['vendor_gender'] == 'm') ? MALE : FEMALE;
    } elseif ($error == true) {
      if ($entry_gender_error == true) {
        echo tep_draw_radio_field('a_gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('a_gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        echo ($a_gender == 'm') ? MALE : FEMALE;
        echo tep_draw_hidden_field('a_gender');
      }
    } else {
      echo tep_draw_radio_field('a_gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('a_gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $vendor['vendor_firstname'];
  } elseif ($error == true) {
    if ($entry_firstname_error == true) {
      echo tep_draw_input_field('a_firstname') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $a_firstname . tep_draw_hidden_field('a_firstname');
    }
  } else {
    echo tep_draw_input_field('a_firstname', $vendor['vendor_firstname']) . '&nbsp;' . ENTRY_FIRST_NAME_TEXT;
  }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $vendor['vendor_lastname'];
  } elseif ($error == true) {
    if ($entry_lastname_error == true) {
      echo tep_draw_input_field('a_lastname') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
    } else {
      echo $a_lastname . tep_draw_hidden_field('a_lastname');
    }
  } else {
    echo tep_draw_input_field('a_lastname', $vendor['vendor_lastname']) . '&nbsp;' . ENTRY_FIRST_NAME_TEXT;
  }
?>
            </td>
          </tr>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo tep_date_short($vendor['vendor_dob']);
    } elseif ($error == true) {
      if ($entry_date_of_birth_error == true) {
        echo tep_draw_input_field('a_dob') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
      } else {
        echo $a_dob . tep_draw_hidden_field('a_dob');
      }
    } else {
      echo tep_draw_input_field('a_dob', tep_date_short($vendor['vendor_dob'])) . '&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $vendor['vendor_email_address'];
  } elseif ($error == true) {
    if ($entry_email_address_error == true) {
      echo tep_draw_input_field('a_email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_email_address_check_error == true) {
      echo tep_draw_input_field('a_email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } elseif ($entry_email_address_exists == true) {
      echo tep_draw_input_field('a_email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $a_email_address . tep_draw_hidden_field('a_email_address');
    }
  } else {
    echo tep_draw_input_field('a_email_address', $vendor['vendor_email_address']) . '&nbsp;' . ENTRY_EMAIL_ADDRESS_TEXT;
  }
?>
            </td>
          </tr>
        
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>  

  <tr>
    <td class="main">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_COMPANY);

  new contentBoxHeading($info_box_contents, false, false);

?>      
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_VENDOR_COMPANY; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_company'];
    } elseif ($error == true) {
      if ($entry_company_error == true) {
        echo tep_draw_input_field('a_company') . '&nbsp;' . ENTRY_VENDOR_COMPANY_ERROR;
      } else {
        echo $a_company . tep_draw_hidden_field('a_company');
      }
    } else {
      echo tep_draw_input_field('a_company', $vendor['vendor_company']) . '&nbsp;' . ENTRY_VENDOR_COMPANY_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_VENDOR_COMPANY_TAXID; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_company_taxid'];
    } elseif ($error == true) {
      if ($entry_company_taxid_error == true) {
        echo tep_draw_input_field('a_company_taxid') . '&nbsp;' . ENTRY_VENDOR_COMPANY_TAXID_ERROR;
      } else {
        echo $a_company_taxid . tep_draw_hidden_field('a_company_taxid');
      }
    } else {
      echo tep_draw_input_field('a_company_taxid', $vendor['vendor_company_taxid']) . '&nbsp;' . ENTRY_VENDOR_COMPANY_TAXID_TEXT;
    }
?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  }
?>  

  <tr>
    <td class="main">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_PAYMENT_DETAILS);

  new contentBoxHeading($info_box_contents, false, false);

?>     
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
<?php
  if (VENDOR_USE_CHECK == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_AFFILIATE_PAYMENT_CHECK; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_payment_check'];
    } elseif ($error == true) {
      if ($entry_payment_check_error == true) {
        echo tep_draw_input_field('a_payment_check') . '&nbsp;' . ENTRY_VENDOR_PAYMENT_CHECK_ERROR;
      } else {
        echo $a_payment_check . tep_draw_hidden_field('a_payment_check');
      }
    } else {
      echo tep_draw_input_field('a_payment_check', $vendor['vendor_payment_check']) . '&nbsp;' . ENTRY_VENDOR_PAYMENT_CHECK_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
  if (VENDOR_USE_PAYPAL == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_VENDOR_PAYMENT_PAYPAL; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_payment_paypal'];
    } elseif ($error == true) {
      if ($entry_payment_paypal_error == true) {
        echo tep_draw_input_field('a_payment_paypal') . '&nbsp;' . ENTRY_VENDOR_PAYMENT_PAYPAL_ERROR;
      } else {
        echo $a_payment_paypal . tep_draw_hidden_field('a_payment_paypal');
      }
    } else {
      echo tep_draw_input_field('a_payment_paypal', $vendor['vendor_payment_paypal']) . '&nbsp;' . ENTRY_VENDOR_PAYMENT_PAYPAL_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
  if (VENDOR_USE_BANK == 'true') {
?>  
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_VENDOR_PAYMENT_BANK_NAME; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_payment_bank_name'];
    } elseif ($error == true) {
      if ($entry_payment_bank_name_error == true) {
        echo tep_draw_input_field('a_payment_bank_name') . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_NAME_ERROR;
      } else {
        echo $a_payment_bank_name . tep_draw_hidden_field('a_payment_bank_name');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_name', $vendor['vendor_payment_bank_name']) . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_NAME_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_payment_bank_branch_number'];
    } elseif ($error == true) {
      if ($entry_payment_bank_branch_number_error == true) {
        echo tep_draw_input_field('a_payment_bank_branch_number') . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER_ERROR;
      } else {
        echo $a_payment_bank_branch_number . tep_draw_hidden_field('a_payment_bank_branch_number');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_branch_number', $vendor['vendor_payment_bank_branch_number']) . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_payment_bank_swift_code'];
    } elseif ($error == true) {
      if ($entry_payment_bank_swift_code_error == true) {
        echo tep_draw_input_field('a_payment_bank_swift_code') . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE_ERROR;
      } else {
        echo $a_payment_bank_swift_code . tep_draw_hidden_field('a_payment_bank_swift_code');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_swift_code', $vendor['vendor_payment_bank_swift_code']) . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_payment_bank_account_name'];
    } elseif ($error == true) {
      if ($entry_payment_bank_account_name_error == true) {
        echo tep_draw_input_field('a_payment_bank_account_name') . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME_ERROR;
      } else {
        echo $a_payment_bank_account_name . tep_draw_hidden_field('a_payment_bank_account_name');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_account_name', $vendor['vendor_payment_bank_account_name']) . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME_TEXT;
    }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_payment_bank_account_number'];
    } elseif ($error == true) {
      if ($entry_payment_bank_account_number_error == true) {
        echo tep_draw_input_field('a_payment_bank_account_number') . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER_ERROR;
      } else {
        echo $a_payment_bank_account_number . tep_draw_hidden_field('a_payment_bank_account_number');
      }
    } else {
      echo tep_draw_input_field('a_payment_bank_account_number', $vendor['vendor_payment_bank_account_number']) . '&nbsp;' . ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?> 
	      </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="main">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_ADDRESS);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $vendor['vendor_street_address'];
  } elseif ($error == true) {
    if ($entry_street_address_error == true) {
      echo tep_draw_input_field('street_address_line1') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
    } else {
      echo $a_street_address . tep_draw_hidden_field('street_address_line1');
    }
  } else {
    echo tep_draw_input_field('street_address_line1', $vendor['vendor_street_address']) . '&nbsp;' . ENTRY_STREET_ADDRESS_TEXT;
  }
?>
            </td>
          </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_SUBURB; ?></td>
            <td class="main">&nbsp;
<?php
    if ($is_read_only == true) {
      echo $vendor['vendor_suburb'];
    } elseif ($error == true) {
      if ($entry_suburb_error == true) {
        echo tep_draw_input_field('street_address_line2') . '&nbsp;' . ENTRY_SUBURB_ERROR;
      } else {
        echo $a_suburb . tep_draw_hidden_field('street_address_line2');
      }
    } else {
      echo tep_draw_input_field('street_address_line2', $vendor['vendor_suburb']) . '&nbsp;' . ENTRY_SUBURB_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_POST_CODE; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $vendor['vendor_postcode'];
  } elseif ($error == true) {
    if ($entry_post_code_error == true) {
      echo tep_draw_input_field('a_postcode') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
    } else {
      echo $a_postcode . tep_draw_hidden_field('a_postcode');
    }
  } else {
    echo tep_draw_input_field('a_postcode', $vendor['vendor_postcode']) . '&nbsp;' . ENTRY_POST_CODE_TEXT;
  }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_CITY; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $vendor['vendor_city'];
  } elseif ($error == true) {
    if ($entry_city_error == true) {
      echo tep_draw_input_field('a_city') . '&nbsp;' . ENTRY_CITY_ERROR;
    } else {
      echo $a_city . tep_draw_hidden_field('a_city');
    }
  } else {
    echo tep_draw_input_field('a_city', $vendor['vendor_city']) . '&nbsp;' . ENTRY_CITY_TEXT;
  }
?>
            </td>
          </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_STATE; ?></td>
            <td class="main">&nbsp;
<?php
    $state = tep_get_zone_name($a_country, $a_zone_id, $a_state);
    if ($is_read_only == true) {
      echo tep_get_zone_name($vendor['vendor_country_id'], $vendor['vendor_zone_id'], $vendor['vendor_state']);
    } elseif ($error == true) {
      if ($entry_state_error == true) {
        if ($entry_state_has_zones == true) {
          $zones_array = array();
          $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($a_country) . "' order by zone_name");
          while ($zones_values = tep_db_fetch_array($zones_query)) {
            $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
          }
          echo tep_draw_pull_down_menu('a_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
        } else {
          echo tep_draw_input_field('a_state') . '&nbsp;' . ENTRY_STATE_ERROR;
        }
      } else {
        echo $state . tep_draw_hidden_field('a_zone_id') . tep_draw_hidden_field('a_state');
      }
    } else {
      echo tep_draw_input_field('a_state', tep_get_zone_name($vendor['vendor_country_id'], $vendor['vendor_zone_id'], $vendor['vendor_state'])) . '&nbsp;' . ENTRY_STATE_TEXT;
    }
?>
            </td>
          </tr>
<?php
  }
?>          
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo tep_get_country_name($vendor['vendor_country_id']);
  } elseif ($error == true) {
    if ($entry_country_error == true) {
      echo tep_get_country_list('a_country') . '&nbsp;' . ENTRY_COUNTRY_ERROR;
    } else {
      echo tep_get_country_name($a_country) . tep_draw_hidden_field('a_country');
    }
  } else {
    echo tep_get_country_list('a_country', $vendor['vendor_country_id']) . '&nbsp;' . ENTRY_COUNTRY_TEXT;
  }
?>
            </td>
          </tr>

        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="main">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_CONTACT);

  new contentBoxHeading($info_box_contents, false, false);

?>    
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $vendor['vendor_telephone'];
  } elseif ($error == true) {
    if ($entry_telephone_error == true) {
      echo tep_draw_input_field('a_telephone') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
    } else {
      echo $a_telephone . tep_draw_hidden_field('a_telephone');
    }
  } else {
    echo tep_draw_input_field('a_telephone', $vendor['vendor_telephone']) . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_TEXT;
  }
?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main">&nbsp;
<?php
  if ($is_read_only == true) {
    echo $vendor['vendor_fax'];
  } elseif ($error == true) {
    if ($entry_fax_error == true) {
      echo tep_draw_input_field('a_fax') . '&nbsp;' . ENTRY_FAX_NUMBER_ERROR;
    } else {
      echo $a_fax . tep_draw_hidden_field('a_fax');
    }
  } else {
    echo tep_draw_input_field('a_fax', $vendor['vendor_fax']) . '&nbsp;' . ENTRY_FAX_NUMBER_TEXT;
  }
?>
            </td>
          </tr>

        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  if ($is_read_only == false) {
?>
  <tr>
    <td class="main">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_PASSWORD);

  new contentBoxHeading($info_box_contents, false, false);

?> 
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_PASSWORD; ?></td>
            <td class="main">&nbsp;
<?php
    if ($error == true) {
      if ($entry_password_error == true) {
        echo tep_draw_password_field('a_password') . '&nbsp;' . ENTRY_PASSWORD_ERROR;
      } else {
        echo PASSWORD_HIDDEN . tep_draw_hidden_field('a_password') . tep_draw_hidden_field('a_confirmation');
      }
    } else {
      echo tep_draw_password_field('a_password') . '&nbsp;' . ENTRY_PASSWORD_TEXT;
    }
?>
            </td>
          </tr>
<?php
    if ( ($error == false) || ($entry_password_error == true) ) {
?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
            <td class="main">&nbsp;
<?php
      echo tep_draw_password_field('a_confirmation') . '&nbsp;' . ENTRY_PASSWORD_CONFIRMATION_TEXT;
?>
            </td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?></td>
  </tr>
  <tr>
    <td class="main">
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">           <tr>
            <td class="main">&nbsp;</td>
            <td class="main">&nbsp;
<?php 
	echo tep_draw_checkbox_field('a_agb', $value = '1', $checked = $vendor['vendor_agb']) . ENTRY_VENDOR_ACCEPT_AGB;
    if ($entry_agb_error == true) {
      echo "<br>".ENTRY_VENDOR_AGB_ERROR;
    }
?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  }
?>
</table>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align="right" class="main" width=100%', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>        
                
                </td>
              </tr>

    </table></form>
