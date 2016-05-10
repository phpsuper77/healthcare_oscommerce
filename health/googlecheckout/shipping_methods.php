<?php
/**
  * File: googlecheckout/shipping_methods.php file
  */ 
$mc_shipping_methods = array(
  'first' => array('domestic_types' => array(
                                               'first' => 'Low Value Orders',
                                            ),
                   'international_types' => array(
                                           ),
                  ),
  'reg'   => array('domestic_types' => array(
                                               'reg' => 'Signature On Delivery',
                                            ),
                   'international_types' => array(
                                           ),
                  ),
  'spec'  => array('domestic_types' => array(
                                               'spec' => 'Urgent or higher value - Insurance Included Upto 2500 GBP',
                                            ),
                   'international_types' => array(
                                           ),
                  ),
  'stand' => array('domestic_types' => array(
                                               'stand' => 'Non-Urgent - Insurance Included Upto 500 GBP',
                                            ),
                   'international_types' => array(
                                           ),
                  ),
  'int1'  => array('domestic_types' => array(
                                            ),
                   'international_types' => array(
                                               'int1' => '(World Zone 2) Non-Urgent - Insurance Included Upto 500 GBP',
                                           ),
                  ),
  'int2'  => array('domestic_types' => array(
                                            ),
                   'international_types' => array(
                                               'int2' => '(World Zone 3) Non-Urgent - Insurance Included Upto 500 GBP',
                                           ),
                  ),
  'int3'  => array('domestic_types' => array(
                                            ),
                   'international_types' => array(
                                               'int3' => '(World Zone 4) Non-Urgent - Insurance Included Upto 500 GBP',
                                           ),
                  ),



);

$mc_shipping_methods_names = array(
  'first' => 'Royal Mail First Class',
  'reg' => 'Royal Mail Recorded',
  'spec' => 'Royal Mail Special Delivery',
  'stand' => 'Royal Mail Standard Parcels',
  'int1' => 'Royal Mail (Zone2)',
  'int2' => 'Royal Mail (Zone3)',
  'int3' => 'Royal Mail (Zone4)',
);
?>