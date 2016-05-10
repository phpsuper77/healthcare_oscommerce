<?php

function get_form_data_for( $SetID, $Version ){
  $core = ebay_core::get();
  $registry = $core->get_registry();
  $hash = 'form_data_'.$SetID.'_'.$Version;
  
  $mform = $registry->getBlob($hash);
  $form_data = unserialize($mform);
  if ( empty($mform) || $form_data===false ) {
    $raw_xml = get_xml_for_sets($SetID, $Version);
    $form_data = parse_form( $raw_xml, $SetID );
    if ( is_array($form_data) ) {
      $registry->setBlob($hash, serialize($form_data) );
    }
  }
  return $form_data;
}

function get_xml_for_sets($SetID, $Version ){
  require_once EBAY_DIR_EBATLIB.'/GetAttributesCSRequestType.php';
  require_once EBAY_DIR_EBATLIB.'/GetAttributesCSResponseType.php';

  $core = ebay_core::get();
  $proxy = $core->get_proxy();

  $req = new GetAttributesCSRequestType();
  $req->setAttributeSetID( array($SetID) );
  $req->setAttributeSystemVersion($Version);
  $req->setIncludeCategoryMappingDetails(true);
  $req->SetDetailLevel('ReturnAll');
  $res = $proxy->GetAttributesCS($req);
  $attr_data = $res->GetAttributeData();
  if ( substr($attr_data,0,1)!='<' ) $attr_data = html_entity_decode($attr_data); 
  $xml_dat = preg_replace('/<p>/ims', '', $attr_data );
  return $xml_dat;
}

function parse_form($xml_dat, $need) {
  require_once( dirname(__FILE__).'/ebay_form_parser.php' );

  $eform = new ebayForm_xmlParser( /*"<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n".*/$xml_dat );
  $root = $eform->GetRoot();
  $data = $eform->GetData();
  $root_elements = array_keys($data[$root]);

  $form_data = array('field_set'=>array(), 'dict'=>array());
  
  $chars = is_array( $data[$root]['Characteristics']['CharacteristicsSet'][0] )?$data[$root]['Characteristics']['CharacteristicsSet']:array($data[$root]['Characteristics']['CharacteristicsSet']);

  foreach( $chars as $sub_char ) {
    if ( $sub_char['id']!==$need ) continue;
    if (isset($sub_char['type']) && $sub_char['type']=='SiteWide') {
      $push_first = false;
    }else{
      $push_first = true;
    }
    $field_set = array( 'id'=>$sub_char['id'], 'label'=>$sub_char['DomainName']['VALUE'], 'controls'=>array() );
    $form_rows = $sub_char['PresentationInstruction']['Initial']['Row'];
    if ( !isset($form_rows[0]) ) $form_rows = array($form_rows);
    foreach( $form_rows as $row ) {
      if ( $row['Widget']['type']!='normal' ) continue;
      $ctl = array( 'id'=>$row['Widget']['Attribute']['id'],
                    'type'=>$row['Widget']['Attribute']['Input']['type'] );
      $field_set['controls'][] = $ctl;
    } 
  
    $char_options = $sub_char['CharacteristicsList']['Initial']['Attribute'];
    if ( is_array($char_options) && !isset($char_options[0]) ) $char_options = array($char_options);

    $sub_dep_options = array();
    foreach( $char_options as $opt_i ) {
      if ( isset($opt_i['Dependency']) ) {
        $_sub_dep_options = isset($opt_i['Dependency']['parentValueId'])?array($opt_i['Dependency']):$opt_i['Dependency'];
        foreach( $_sub_dep_options as $dep_values ) {
          $dep_ctrl_values = array();
          if ( !isset($dep_values['Value'][0]['id']) ) $dep_values['Value'] = array( $dep_values['Value'] ); 
          foreach( $dep_values['Value'] as $dep_vals ) {
            $dep_ctrl_values[] = array( 'id'=>$dep_vals['id'], 'text'=>$dep_vals['Name']['VALUE'] );
          }
          if ( !is_array($sub_dep_options[ $dep_values['parentValueId'] ]) ) $sub_dep_options[ $dep_values['parentValueId'] ] = array();
          $sub_dep_options[ $dep_values['parentValueId'] ][ $dep_values['childAttrId'] ] = $dep_ctrl_values;
        }
      }
    }

    foreach( $char_options as $opt_i ) {
      $variants = isset($opt_i['ValueList']['Value'][0])?$opt_i['ValueList']['Value']:array($opt_i['ValueList']['Value']);

      $pd_v = array();
      $dynamic_lists = array();
      foreach( $variants as $vi ) {
        if ( isset($sub_dep_options[$vi['id']]) && is_array(($sub_dep_options[$vi['id']])) ) {
          $dynamic_lists[$vi['id']] = $sub_dep_options[$vi['id']];
        }
        $pd_v[] = array('id'=>$vi['id'], 'text'=>$vi['Name']['VALUE']);
      }
      $form_data['dict'][ $opt_i['id'] ] = array('label'=>$opt_i['Label']['VALUE'], 'variants'=>$pd_v, 'dynamic'=>$dynamic_lists );
    }
    if ( $push_first ) {
      $form_data['field_set'] = array_merge(array($field_set), $form_data['field_set']);
    }else{
      $form_data['field_set'][] = $field_set;
    }
  }
  return $form_data;
}
    
function renderForm( $form_data, $pid ){
  global $languages_id;
  $try = array('/condition/i', '/new/i');
  if ( $pid!=0 ) {
  }
  // render form
  foreach( $form_data['field_set'] as $fset ) {
    echo '<fieldset rel="'.$fset['id'].'">';
    echo '<legend>'.$fset['label'].'</legend>';
    foreach( $fset['controls'] as $ctrl ) {
      $desc_ctl = $form_data['dict'][ $ctrl['id'] ];
      echo '<div class="row"><label>'.$desc_ctl['label'].'</label>';
      switch( $ctrl['type'] ) {
        case 'textarea':
        case 'collapsible_textarea':
          echo tep_draw_textarea_field(''.$ctrl['id'], 'soft', '20', '4');
        break;
        case 'dropdown':
          $have_other = false;
          foreach( $desc_ctl['variants'] as $var_info ) {
            if ( $var_info['id']==-6 ) $have_other=true;
          }
          $change_look = '';
          if ( $desc_ctl['dynamic'] ) {
            $change_look = ' onchange="cng_'.$ctrl['id'].'(this);"';
            echo '<script>'."\n";
            echo 'function cng_'.$ctrl['id'].'(ct){'."\n".
                 ' var tval = $(ct).val();'."\n".
                 ' var tform = $(ct.form);'."\n";
            foreach( $desc_ctl['dynamic'] as $new_value=>$deps ) {
              echo ' if (tval=='.$new_value.') {'."\n";
              foreach( $deps as $dep_control=>$dep_values_array ) {
                $dep_value = '';
                foreach( $dep_values_array as $dep_values_info ) {
                  $dep_value.='<option value="'.$dep_values_info['id'].'">'.$dep_values_info['text'].'</option>';
                }
                echo ' $("select[name='.$dep_control.']",tform).html(\''.addslashes($dep_value).'\');'."\n";
              }
              echo ' }'."\n";
            }
            echo '}'."\n";
            echo '</script>'."\n";
          }
          echo tep_draw_pull_down_menu(''.$ctrl['id'], $desc_ctl['variants'], (( preg_match('/condition/i', $desc_ctl['label'] ) )?10425:''), ($have_other?'rel="enable_other"':'').$change_look );
        break;
        case 'textfield':
          echo tep_draw_input_field(''.$ctrl['id']);
        break;
        case 'checkbox':
          echo '<ul>';
          foreach( $desc_ctl['variants'] as $var_ctl ) {
            echo '<li>'.tep_draw_checkbox_field(''.$ctrl['id'].'[]',$var_ctl['id']).'&nbsp;'.$var_ctl['text'];
          }
          echo '</ul>';
        break;
        default:
        //echo '<pre style="text-align:left;background-color:#ddd;color:#000">'; var_dump($ctrl['type']); echo '</pre>';
      }
      echo '</div>';
  /*
      ['id']
      ['type']
      textfield
      hidden
      checkbox
      radio
      single
      multiple
    */
    }
    echo '</fieldset>';
  }
}
?>