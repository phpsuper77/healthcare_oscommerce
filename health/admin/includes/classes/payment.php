<?php
/*
  $Id: payment.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class payment {
    var $modules, $selected_module;

// class constructor
    function payment($module = '') {
// BOF: WebMakers.com Added: Downloads Controller
      global $payment, $language, $PHP_SELF, $cart, $affiliate_ref, $HTTP_SESSION_VARS;
// EOF: WebMakers.com Added: Downloads Controller

      $flag = false;
      if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != '' && defined('MODULE_PAYMENT_INSTALLED_' . $affiliate_ref) && tep_not_null(constant('MODULE_PAYMENT_INSTALLED_' . $affiliate_ref))){
//        if (defined(constant('MODULE_PAYMENT_INSTALLED_' . $affiliate_ref))){
        $flag = true;
//        }
        $module_key = 'MODULE_PAYMENT_INSTALLED_' . $affiliate_ref;
      }else{
        if (defined('MODULE_PAYMENT_INSTALLED')){
          $flag = true;
        }
        $module_key = 'MODULE_PAYMENT_INSTALLED';
      }
//      if (defined(constant($module_key)) && tep_not_null(constant($module_key))) {
      if ($flag && tep_not_null(constant($module_key))) {
        $this->modules = explode(';', constant($module_key));
        $include_modules = array();

        if ( (tep_not_null($module)) && (in_array($module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), $this->modules)) ) {
          $this->selected_module = $module;

          $include_modules[] = array('class' => $module, 'file' => $module . '.php');
        } else {
          reset($this->modules);

          if (tep_get_configuration_key_value('MODULE_PAYMENT_FREECHARGER_STATUS') and ($cart->show_total()==0 and $cart->show_weight==0)) {
            $this->selected_module = $module;
            $include_modules[] = array('class'=> 'freecharger', 'file' => 'freecharger.php');
          } else {
            // All Other Payment Modules
            while (list(, $value) = each($this->modules)) {
              $class = substr($value, 0, strrpos($value, '.'));
              // Don't show Free Payment Module
              if ($class !='freecharger') {
                $include_modules[] = array('class' => $class, 'file' => $value);
              }
            }
// EOF: WebMakers.com Added: Downloads Controller
          }
        }

        for ($i=0, $n=sizeof($include_modules); $i<$n; $i++) {
          include_once(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/payment/' . $include_modules[$i]['file']);
          include_once(DIR_FS_CATALOG . DIR_WS_MODULES . 'payment/' . $include_modules[$i]['file']);

          if (!is_object($include_modules[$i]['class'])){
            $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class'];
          }
        }

// if there is only one payment method, select it as default because in
// checkout_confirmation.php the $payment variable is being assigned the
// $HTTP_POST_VARS['payment'] value which will be empty (no radio button selection possible)
        if ( (tep_count_payment_modules() == 1) && (!isset($GLOBALS[$payment]) || (isset($GLOBALS[$payment]) && !is_object($GLOBALS[$payment]))) ) {
          $payment = $include_modules[0]['class'];
        }

        if ( (tep_not_null($module)) && (in_array($module, $this->modules)) && (isset($GLOBALS[$module]->form_action_url)) ) {
          $this->form_action_url = $GLOBALS[$module]->form_action_url;
        }
      }
    }

// class methods
/* The following method is needed in the checkout_confirmation.php page
   due to a chicken and egg problem with the payment class and order class.
   The payment modules needs the order destination data for the dynamic status
   feature, and the order class needs the payment module title.
   The following method is a work-around to implementing the method in all
   payment modules available which would break the modules in the contributions
   section. This should be looked into again post 2.2.
*/   
    function update_status() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module])) {
          if (function_exists('method_exists')) {
            if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
              $GLOBALS[$this->selected_module]->update_status();
            }
          } else { // PHP3 compatibility
            @call_user_method('update_status', $GLOBALS[$this->selected_module]);
          }
        }
      }
    }

    function javascript_validation() {
      $js = '';
      if (is_array($this->modules)) {
        $js = '<script language="javascript"><!-- ' . "\n" .
              'function check_form() {' . "\n" .
              '  var error = 0;' . "\n" .
              '  var error_message = "' . JS_ERROR . '";' . "\n" .
              '  var payment_value = null;' . "\n" .
              '  if (document.checkout_payment.payment.length) {' . "\n" .
              '    for (var i=0; i<document.checkout_payment.payment.length; i++) {' . "\n" .
              '      if (document.checkout_payment.payment[i].checked) {' . "\n" .
              '        payment_value = document.checkout_payment.payment[i].value;' . "\n" .
              '      }' . "\n" .
              '    }' . "\n" .
              '  } else if (document.checkout_payment.payment.checked) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  } else if (document.checkout_payment.payment.value) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  }' . "\n\n";

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $js .= $GLOBALS[$class]->javascript_validation();
          }
        }

        $js .= "\n" . '  if (payment_value == null && submitter != 1) {' . "\n" . // ICW CREDIT CLASS Gift Voucher System
               '    error_message = error_message + "' . JS_ERROR_NO_PAYMENT_MODULE_SELECTED . '";' . "\n" .
               '    error = 1;' . "\n" .
               '  }' . "\n\n";
//  ICW CREDIT CLASS Gift Voucher System Line below amended
        if (GERMAN_SITE == 'True'){
          $js .= ' if (!document.checkout_payment.conditions.checked){' . "\n".
                 '   error = 1;'. "\n".
                 '   error_message = error_message + "' . ERROR_JS_CONDITIONS_NOT_ACCEPTED . '";' . "\n" .
                 ' }' . "\n";
        }
        $js .= '  if (error == 1 && submitter != 1) {' . "\n" .
               '    alert(error_message);' . "\n" .
               '    return false;' . "\n" .
               '  } else {' . "\n" .
               '    return true;' . "\n" .
               '  }' . "\n" .
               '}' . "\n" .
               '//--></script>' . "\n";
      }

      return $js;
    }

    function selection() {
      $selection_array = array();

      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $selection = $GLOBALS[$class]->selection();
            if (is_array($selection)) $selection_array[] = $selection;
          }
        }
      }

      return $selection_array;
    }
 //ICW CREDIT CLASS Gift Voucher System
 // check credit covers was setup to test whether credit covers is set in other parts of the code
function check_credit_covers() {
	global $credit_covers;

	return $credit_covers;
}
    function pre_confirmation_check() {
      global $credit_covers, $payment_modules; //ICW CREDIT CLASS Gift Voucher System
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {

          if ($credit_covers) { //  ICW CREDIT CLASS Gift Voucher System
            $GLOBALS[$this->selected_module]->enabled = false; //ICW CREDIT CLASS Gift Voucher System
            $GLOBALS[$this->selected_module] = NULL; //ICW CREDIT CLASS Gift Voucher System
            $payment_modules = ''; //ICW CREDIT CLASS Gift Voucher System
          } else { //ICW CREDIT CLASS Gift Voucher System
            $GLOBALS[$this->selected_module]->pre_confirmation_check();
          }
        }
      }
    } //ICW CREDIT CLASS Gift Voucher System

    function confirmation() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->confirmation();
        }
      }
    }

    function process_button() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->process_button();
        }
      }
    }

    function before_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->before_process();
        }
      }
    }

    function after_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->after_process();
        }
      }
    }

    function get_error() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->get_error();
        }
      }
    }
  }
?>