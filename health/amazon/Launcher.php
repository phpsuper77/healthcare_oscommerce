<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/


class Launcher {
  function dbLogClean(){
    if ( AmazonConfig::getDBLogKeep()!='' && preg_match('/(\d{1,})d/',AmazonConfig::getDBLogKeep(),$param_keep) ) {
      if ( (int)$param_keep[1]>0 ) {
        $del_mkt = mktime(0, 0, 0, date('m'), (date('d')-(int)$param_keep[1]), date('Y'));
        $clean = new RecordSetProxy();
        $clean->query("DELETE FROM ".TABLE_AMAZON_SOAP." ".
                      "WHERE as_state='_DONE_' AND as_date < '".date('Y-m-d',$del_mkt)."'");
        if ($clean->affected()>0){
          $clean->query("OPTIMIZE TABLE ".TABLE_AMAZON_SOAP);
        }
      }
    }
  }

  function postProductFeed( $process_upload='', $to_file=false ){
    global $_amazon_fws;

    if ( Launcher::haveNotDone($soap_do) ) {
      $_amazon_fws->say("Have not done ".$soap_do.". exiting\n");
      return;
    }

    $product_sub_feed = '';
    $productCollection = new AmazonProductsCollection();
    switch( $process_upload ){
      case 'products':
        $product_sub_feed = $productCollection->makeProductFeed();
        $soap_do = '_POST_PRODUCT_DATA_';
      break;
      case 'images':
        $product_sub_feed = $productCollection->makeImageFeed();
        $soap_do = '_POST_PRODUCT_IMAGE_DATA_';
      break;
      case 'inventory':
        $product_sub_feed = $productCollection->makeInventoryFeed();
        $soap_do = '_POST_INVENTORY_AVAILABILITY_DATA_';
      break;
      case 'price':
        $product_sub_feed = $productCollection->makePricingFeed();
        $soap_do = '_POST_PRODUCT_PRICING_DATA_';
      break;
    }
    if ( empty($product_sub_feed) ) {
      $_amazon_fws->say("Produced empty feed ".$soap_do.". exiting\n");
      return;
    }
    
    if ( $to_file ) {
      $target_file = AmazonConfig::logDir().$soap_do.'.xml';
      $do_chmod = !file_exists($target_file);
      if ($log_file = fopen($target_file, "w")) {
        fputs($log_file, $product_sub_feed);
        fclose($log_file);
        if ( $do_chmod ) @chmod($target_file, 0666);
      }
      return;
    }
    
    $MerchantInterface = $_amazon_fws->getSoap();
    
    $post_doc = $MerchantInterface->postDocument( $soap_do, $product_sub_feed);
    if ( $post_doc!==false && isset( $post_doc['documentTransactionID'] ) ) {
      $documentTransactionID = $post_doc['documentTransactionID'];
      $add_state = array( 'as_date' => 'now()',
                          'as_feedtype' => $soap_do,
                          'as_state' => 'POSTED',
                          'as_documentTransactionID' => $documentTransactionID
                        );
      $rs = new RecordSetProxy();
      $rs->insert( TABLE_AMAZON_SOAP, $add_state );
      if ($soap_do == '_POST_PRODUCT_DATA_') {
        // mark as new
        $productCollection->markNewAsAdded();
      }
    }
  }



  // send shipped confirmation for amazon
  function sendShipped( $only_file=false ){
    global $_amazon_fws;
    $list = new ProxyOrderFulfillmentList();
    $list->load_db_list();
    if ( $list->have_orders() ) {
      $_amazon_fws->say("Ship have ".$list->have_orders()." shipped orders\n");
      $soap_do = '_POST_ORDER_FULFILLMENT_DATA_';
      $confirmShip = $list->toXML();

      if ( $only_file==true ) {
        $target_file = AmazonConfig::logDir().$soap_do.'.xml';
        $do_chmod = !file_exists($target_file);
        if ($log_file = fopen($target_file, "w")) {
          fputs($log_file, $confirmShip);
          fclose($log_file);
          if ( $do_chmod ) @chmod($target_file, 0666);
        }
        return;
      }

      $MerchantInterface = $_amazon_fws->getSoap();
      $post_doc = $MerchantInterface->postDocument( $soap_do, $confirmShip );
      if ( $post_doc!==false && isset( $post_doc['documentTransactionID'] ) ) {
        $documentTransactionID = $post_doc['documentTransactionID'];
        $add_state = array( 'as_date' => 'now()',
                            'as_feedtype' => $soap_do,
                            'as_state' => 'POSTED',
                            'as_documentTransactionID' => $documentTransactionID
                          );
        $ins = new RecordSetProxy();
        $ins->insert( TABLE_AMAZON_SOAP, $add_state );
        $list->mark_ship_done();
      }
    } else {
      $_amazon_fws->say("Not found new shipped orders\n");
    }
  }

  function orderImportFromFile( ) {
    global $_amazon_fws;
    $xmlString = file_get_contents( AmazonConfig::logDir().'testorder.xml' );
    $OrderReport = new OrderReport();
    $send_ask = $OrderReport->process( $xmlString );
    $_amazon_fws->say("OrderReport::process ".(!empty($send_ask)?'confirm':'REJECT')." Ask\n");
    echo '<pre>'; var_dump( $OrderReport ); echo '</pre>';
  }

  function orderImport( $onlyCheck=false ){
    global $_amazon_fws;
    $MerchantInterface = $_amazon_fws->getSoap();

    $processed = 0;
    $failed = 0;
    $ok = 0;
    $warn = 0;
    $max_chunk_count = 10;
    $initial_till_date = false;

    $new_orders = $MerchantInterface->getAllPendingDocumentInfo( '_GET_ORDERS_DATA_' );
    if ( $new_orders!==false ) {
      if ( empty($new_orders) ) {
        //no orders
        $_amazon_fws->say("No remote orders\n");
      }else{
        //Processes all Order Reports
        $process_pool = array();
        $process_pool = ( isset($new_orders['MerchantDocumentInfo'][0])?$new_orders['MerchantDocumentInfo']:array($new_orders['MerchantDocumentInfo']) );//??
        foreach( $process_pool as $MerchantDocumentInfo ) {
          if ( $max_chunk_count==0 ) break;
          $max_chunk_count--;
          if ( $initial_till_date!==false ) {
            if ( $initial_till_date < substr($MerchantDocumentInfo['generatedDateTime'], 0, strlen($initial_till_date) ) ) {
              break;
            }
          }
          //$MerchantDocumentInfo['generatedDateTime']
          //Stores Document ID
          $documentID = $MerchantDocumentInfo['documentID'];
          $_amazon_fws->say("* Report document ID{$documentID} generated {$MerchantDocumentInfo['generatedDateTime']}\n");
          if ( $onlyCheck ) continue;

          $download_order = $_amazon_fws->getSoap();
          $orderDocument = $download_order->getDocument( $documentID );
          $attaches = $download_order->getAttachments();
          $send_ask = false;
          if ( isset($attaches[0]) && !empty($attaches[0]['data']) ) {
            if ( defined('AFWS_ORDER_IMPORT_PROCESS_MIRROR') && AFWS_ORDER_IMPORT_PROCESS_MIRROR=='true' ) {
              $dated_name = $MerchantDocumentInfo['generatedDateTime'];
              $dated_name = preg_replace('/[^\d]/', '_', $dated_name);
              $dated_name = preg_replace('/_{2,}/', '_', $dated_name);
              $target_file = AmazonConfig::logDir().'OrderReport_'.$dated_name.'-'.$documentID.'.xml';
              $do_chmod = !file_exists($target_file);
              if ($log_file = fopen($target_file, "w+")) {
                fputs($log_file, $attaches[0]['data']);
                fclose($log_file);
                if ( $do_chmod ) @chmod($target_file, 0666);
                $_amazon_fws->say("!! Saved [$documentID] to {$target_file}\n");
              }
            }
            
            $OrderReport = new OrderReport();
            $send_ask = $OrderReport->process( $attaches[0]['data'] );
            $_amazon_fws->say("OrderReport::process ".(!empty($send_ask)?'confirm':'REJECT')." Ask\n");
            $processed += $OrderReport->_processed;
            $failed += $OrderReport->_failed;
            $ok += $OrderReport->_ok;
            $warn += $OrderReport->_warning;
            /* this part for confirm whole doc - remove from orderReport */
            if ( $OrderReport->_failed==0 ) {
              $confirm_downloaded = $_amazon_fws->getSoap();
              $processed_docs = new ArrayOfstring( array('string'=>array($documentID)) );
              $res = $confirm_downloaded->postDocumentDownloadAck( $processed_docs );
            }
          }else{
            $_amazon_fws->say("Empty attaches for [$documentID]\n");
          }
          if ( !empty($send_ask) ) {
            $confirm_orders = $_amazon_fws->getSoap();
            $soap_do = '_POST_ORDER_ACKNOWLEDGEMENT_DATA_';
            $post_doc = $confirm_orders->postDocument( $soap_do, $send_ask );
            if ( $post_doc!==false && isset( $post_doc['documentTransactionID'] ) ) {
              $documentTransactionID = $post_doc['documentTransactionID'];
              $add_state = array( 'as_date' => 'now()',
                                  'as_feedtype' => $soap_do,
                                  'as_state' => 'POSTED',
                                  'as_documentTransactionID' => $documentTransactionID
                                );
              $ins = new RecordSetProxy();
              $ins->insert( TABLE_AMAZON_SOAP, $add_state );
            }
          }
        }
      }
    }

    $add_state = array( 'as_date' => 'now()',
                        'as_feedtype' => '_GET_ORDERS_DATA_',
                        'as_state' => 'INFO',
                        'as_documentTransactionID' => '-',
                        'as_lastinfo' => getFakeProcessingReport('Complete', $processed, $ok, $failed, $warn)
                      );
    $ins = new RecordSetProxy();
    $ins->insert( TABLE_AMAZON_SOAP, $add_state );
  }



  function haveNotDone( $as_feedtype ){
    $check = new RecordSetProxy();
    $count = $check->fetchOne("SELECT COUNT(*) AS c ".
                              "FROM ".TABLE_AMAZON_SOAP." ".
                              "WHERE as_feedtype='".$check->esc($as_feedtype)."' ".
                                "AND as_state IN ( 'POSTED', '_PENDING_', '_IN_PROGRESS_')");
    return (int)$count!=0;
  }

  // query amazon about posted document state
  function amazonPoll(){
    global $_amazon_fws;
    $rs = new RecordSetProxy();
    $rs->query("SELECT ".
                 "as_id, as_state, as_feedtype, as_documentTransactionID ".
               "FROM ".TABLE_AMAZON_SOAP." ".
               "WHERE as_state in ( 'POSTED', '_PENDING_', '_IN_PROGRESS_') ".
               "ORDER BY as_date");
    if ( $rs->count()>0 ) {
      while( $query_state = $rs->next() ){
        $MerchantInterface = $_amazon_fws->getSoap();
        $status = $MerchantInterface->getDocumentProcessingStatus( $query_state['as_documentTransactionID'] );
        if ( $status!==false ) {
          $update_state = array( 'as_lastAckDate' => 'now()',
                                 'as_state' => $status['documentProcessingStatus']
                               );
          switch ( $status['documentProcessingStatus'] ) {
            case '_DONE_':
              $documentID = $status['processingReport']['documentID'];
              $process_info = $MerchantInterface->getDocument($documentID);
              if ( $process_info!==false ) {
                $update_state['as_documentID'] = $documentID;
                $attaches = $MerchantInterface->getAttachments();
                if ( isset($attaches[0]) ) {
                  $update_state['as_lastinfo'] = $attaches[0]['data'];
                  $ProcessingReport = new AmazonProcessingReport();
                  $ProcessingReport->fromXML( $update_state['as_lastinfo'] );
                  $update_state['as_showstate'] = $ProcessingReport->showstate();
                  if ( $query_state['as_feedtype']=='_POST_INVENTORY_AVAILABILITY_DATA_'
                    || $query_state['as_feedtype']=='_POST_PRODUCT_PRICING_DATA_' ) {
                    $kickOff = $ProcessingReport->getKickOff();
                    if ( count($kickOff)>0 ) AmazonProductsCollection::kickOff($kickOff);
                  }
                }
              } else {
                $update_state = false;
              }
             break;

            case '_PENDING_':
            case '_IN_PROGRESS_':
            break;

            case '_FAILED_DUE_TO_FATAL_ERRORS_':
              $attaches = $MerchantInterface->getAttachments();
              if ( isset($attaches[0]) ) {
                $update_state['as_lastinfo'] = $attaches[0];
                $update_state['as_showstate'] = 'error';
              }
            break;
          }
          if ( is_array($update_state) ) {
            $upd = new RecordSetProxy();
            $upd->update(TABLE_AMAZON_SOAP, $update_state, "as_id='".$query_state['as_id']."'");
          }
        }
      }
    }else{
      // idle
      Launcher::dbLogClean();
    }
  }


}

?>
