<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

function amazon_prepare_desc($in_desc, $use_limit=false, $filter_text=false) {
  $in_desc = str_replace('>', '> ', $in_desc);
  $in_desc = preg_replace('/<script.*?<\/script>/ims','',$in_desc);
  $in_desc = preg_replace('/<noscript.*?<\/noscript>/ims','',$in_desc);
  $in_desc = strip_tags($in_desc);
  $in_desc = trim(str_replace(array('&nbsp;',chr(160), ',',"\t","\n","\r",'"'),' ',$in_desc));
  $in_desc = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $in_desc);
  $in_desc = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $in_desc);
  $in_desc = html_entity_decode($in_desc);
  $in_desc = trim($in_desc);
  if ( $filter_text ) {
    // try remove "Click here for the full review" or "Click here for the review" or "Click here for review"
    $in_desc = preg_replace('/click\s*?here\s*?for(\s*?the)?(\s*?full)?\s*?review\.?/i', ' ', $in_desc);
  }
  $in_desc = preg_replace( '/\s{2,}/', ' ', $in_desc );
  if( is_string($use_limit) && substr($use_limit, -1)=='w' ){
    $words = split(' ',$in_desc);
    $wordlimit = (int)$use_limit;
    $in_desc = implode(' ',array_slice($words, 0,$wordlimit )).(count($words)>$wordlimit?' ...':'');
  }elseif ( $use_limit!==false && (int)$use_limit>0 ) {
    if (strlen($in_desc)>(int)$use_limit) $in_desc = substr($in_desc,0,(int)$use_limit-1) . '...';
  }
  $in_desc = str_replace(array('&nbsp;', chr(160),',',"\t","\n","\r",'"'),' ',$in_desc);
  return trim($in_desc);
}


function getFakeProcessingReport($status, $Processed, $Successful, $WithError, $WithWarn) {
  return '<?xml version="1.0"?'.'>
<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">
    <MessageType>ProcessingReport</MessageType>
    <Message>
        <MessageID>1</MessageID>
        <ProcessingReport>
            <DocumentTransactionID>-</DocumentTransactionID>
            <StatusCode>'.$status.'</StatusCode>
            <ProcessingSummary>
                <MessagesProcessed>'.intval($Processed).'</MessagesProcessed>
                <MessagesSuccessful>'.intval($Successful).'</MessagesSuccessful>
                <MessagesWithError>'.intval($WithError).'</MessagesWithError>
                <MessagesWithWarning>'.intval($WithWarn).'</MessagesWithWarning>
            </ProcessingSummary>
        </ProcessingReport>
    </Message>
</AmazonEnvelope>';
}


?>
