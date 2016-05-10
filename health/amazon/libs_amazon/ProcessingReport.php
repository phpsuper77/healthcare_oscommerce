<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

  class ProcessingReport {
    var $DocumentTransactionID = '';
    var $StatusCode = '';
    var $MessagesProcessed = 0;
		var $MessagesSuccessful = 0;
		var $MessagesWithError = 0;
		var $MessagesWithWarning = 0;
    var $Result = array();
    function fromXML( $report_ar ){
      if( isset($report_ar['DocumentTransactionID']['VALUE']) ) $this->DocumentTransactionID = $report_ar['DocumentTransactionID']['VALUE'];
      if( isset($report_ar['StatusCode']['VALUE']) ) $this->StatusCode = $report_ar['StatusCode']['VALUE'];
      if( isset($report_ar['StatusCode'][0]['VALUE']) ) $this->StatusCode = $report_ar['StatusCode'][0]['VALUE'];
      if ( isset($report_ar['ProcessingSummary'][0]) && is_array($report_ar['ProcessingSummary'][0]) ) {
        // strange bug in response - double ProcessingSummary, use first
        $report_ar['ProcessingSummary'] = $report_ar['ProcessingSummary'][0];
      }
      if( isset($report_ar['ProcessingSummary']['MessagesProcessed']['VALUE']) ) $this->MessagesProcessed = $report_ar['ProcessingSummary']['MessagesProcessed']['VALUE'];
      if( isset($report_ar['ProcessingSummary']['MessagesSuccessful']['VALUE']) ) $this->MessagesSuccessful = $report_ar['ProcessingSummary']['MessagesSuccessful']['VALUE'];
      if( isset($report_ar['ProcessingSummary']['MessagesWithError']['VALUE']) ) $this->MessagesWithError = $report_ar['ProcessingSummary']['MessagesWithError']['VALUE'];
      if( isset($report_ar['ProcessingSummary']['MessagesWithWarning']['VALUE']) ) $this->MessagesWithWarning = $report_ar['ProcessingSummary']['MessagesWithWarning']['VALUE'];
      if ( isset( $report_ar['Result'] ) ) {
        $_list = is_array( $report_ar['Result'][0] )?$report_ar['Result']:array($report_ar['Result']);
        foreach( $_list as $Result ) {
          $SKU = '';
          $AdditionalInfo = '';
          if ( isset($Result['AdditionalInfo']['SKU']['VALUE']) ) {
             $AdditionalInfo .= 'SKU='.$Result['AdditionalInfo']['SKU']['VALUE']."\n";
             $SKU = $Result['AdditionalInfo']['SKU']['VALUE'];
          }
          if ( isset($Result['AdditionalInfo']['FulfillmentCenterID']['VALUE']) ) $AdditionalInfo .= 'FulfillmentCenterID='.$Result['AdditionalInfo']['FulfillmentCenterID']['VALUE']."\n";
          if ( isset($Result['AdditionalInfo']['AmazonOrderID']['VALUE']) ) $AdditionalInfo .= 'AmazonOrderID='.$Result['AdditionalInfo']['AmazonOrderID']['VALUE']."\n";
          if ( isset($Result['AdditionalInfo']['AmazonOrderItemCode']['VALUE']) ) $AdditionalInfo .= 'AmazonOrderItemCode='.$Result['AdditionalInfo']['AmazonOrderItemCode']['VALUE']."\n";
          $this->Result[] = array(
            'MessageID' => isset($Result['MessageID']['VALUE'])?$Result['MessageID']['VALUE']:'',
            'ResultCode' => isset($Result['ResultCode']['VALUE'])?$Result['ResultCode']['VALUE']:'',
            'ResultMessageCode' => isset($Result['ResultMessageCode']['VALUE'])?$Result['ResultMessageCode']['VALUE']:'',
            'ResultDescription' => isset($Result['ResultDescription']['VALUE'])?$Result['ResultDescription']['VALUE']:'',
            'AdditionalInfo' => $AdditionalInfo,
            'SKU'=>$SKU,
          ); 
        }
      }
    }
    
    function render(){
?>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="dataTableContent">DocumentTransactionID: <?php echo $this->DocumentTransactionID; ?></td>
        <td class="dataTableContent">MessagesProcessed: <?php echo $this->MessagesProcessed; ?></td>
        <td class="dataTableContent">MessagesWithError: <?php echo $this->MessagesWithError; ?></td>
      </tr>
      <tr>
        <td class="dataTableContent">StatusCode: <?php echo $this->StatusCode; ?></td>
        <td class="dataTableContent">MessagesSuccessful: <?php echo $this->MessagesSuccessful; ?></td>
        <td class="dataTableContent">MessagesWithWarning: <?php echo $this->MessagesWithWarning; ?></td>
      </tr>
    </table>
<?php
      if ( count($this->Result)>0 ) {
        echo tep_black_line();
        echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
        foreach( $this->Result as $Result ) {
        ?>
        <tr>
          <td class="dataTableContent">MessageID: <?php echo $Result['MessageID']; 
          echo ' &nbsp; '.$Result['ResultCode'].'['.$Result['ResultMessageCode'].']'; ?></td>
        </tr>  
        <tr>
          <td class="dataTableContent"><?php echo $Result['ResultDescription'].'<br> ['.$Result['AdditionalInfo'].']'; ?></td>
        </tr>
        <?php
        }
        echo '</table>';
      }
      
    }
  }
  
  class AmazonProcessingReport {
    var $report_list;
    
    function AmazonProcessingReport(){
      $this->report_list = array();
    }
    
    function fromXML( $rawXml ) {
      $parser = new xmlParser( $rawXml );
      $root = $parser->GetRoot();
      $data = $parser->GetData();
      $data = $data[$root];
      $this->report_list = array();
      if ( $data['MessageType']['VALUE']=='ProcessingReport' ) {
        $_list = is_array( $data['Message'][0] )?$data['Message']:array($data['Message']);
        foreach( $_list as $message ) {
          $ProcessingReport = new ProcessingReport();
          $idx = $message['MessageID']['VALUE'];
          $ProcessingReport->fromXML( $message['ProcessingReport'] );
          $this->report_list[ $idx ] = $ProcessingReport;
        }
      }
    }

    function getKickOff(){
      $not_active = array();
      if ( is_array($this->report_list) ) foreach( $this->report_list as $report_list ) {
        if ( is_array($report_list->Result) ) foreach( $report_list->Result as $error ) {
          $addInfo = array_key_exists(0, $error)?
                       $error:
                       array($error);
          foreach( $addInfo as $infoErr ) {
            if ($infoErr['ResultMessageCode']=='13013'){
              if (!empty($infoErr['SKU'])) {
                $not_active[] = $infoErr['SKU'];
              }
            }
          }
        }
      }
      return $not_active;
    }
    
    function showstate(){
      $state = 'ok';
      foreach( $this->report_list as $ProcessingReport ) {
        if ( intval($ProcessingReport->MessagesProcessed)==0 ) {
          $state = 'error';
          break;
        } elseif ( intval($ProcessingReport->MessagesWithError)!=0 ) {
          $state = 'error';
          break;
        } elseif ( intval($ProcessingReport->MessagesWithWarning)!=0 ) {
          $state = 'warning';
        }
      }
    }
    
    function render(){
      if ( is_array( $this->report_list ) ) foreach( $this->report_list as $report ) $report->render();
    }
  }
?>
