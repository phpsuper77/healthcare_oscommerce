<?PHP
require('includes/application_top.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
	<title><?php echo STORE_NAME;?> - PopUp Editor</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">	

<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
_editor_url = '<?php echo ($request_type == 'SSL'?HTTPS_SERVER:HTTP_SERVER) . DIR_WS_ADMIN . DIR_WS_INCLUDES . 'javascript/htmlarea3/'; ?>';
_editor_lang = 'en';
//Change to current language code.
/*]]>*/
</script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_INCLUDES; ?>javascript/htmlarea3/htmlarea.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_INCLUDES; ?>javascript/htmlarea3/plugins/ImageManager/assets/dialog.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_INCLUDES; ?>javascript/htmlarea3/plugins/ImageManager/IMEStandalone.js"></script>
<SCRIPT language="JavaScript">
HTMLArea.loadPlugin("TableOperations");
HTMLArea.loadPlugin("SpellChecker");
HTMLArea.loadPlugin("ContextMenu");
HTMLArea.loadPlugin("ListType");
HTMLArea.loadPlugin("ImageManager"); 
</SCRIPT>
<script language="JavaScript1.2" defer="defer"> 

  var editor;
  function editorGenerate(){
		editor = new HTMLArea("editor");  
		
    editor.registerPlugin(TableOperations);

    editor.registerPlugin(SpellChecker);
    editor.registerPlugin(ListType);
    editor.registerPlugin(ContextMenu);
    editor.registerPlugin(ImageManager);

		if (window.opener != null){
      document.editor.editor.value = window.opener.document.forms[window.opener.editorFormName].elements[window.opener.editorFieldName].value;
    }

    editor.generate(); 
    
    //window.setTimeout('loadData()', 1000);
  }
  
  function update(){
    if (window.opener != null){
      window.opener.document.forms[window.opener.editorFormName].elements[window.opener.editorFieldName].value = editor.getHTML();
    }
    window.close();
  }
  
</script> 
<script language="JavaScript">
initPage = function (){editorGenerate();}
function addEvent(obj, evType, fn) {if (obj.addEventListener) { obj.addEventListener(evType, fn, true); return true; }else if (obj.attachEvent) {  var r = obj.attachEvent("on"+evType, fn);  return r;  }else {  return false; }}
addEvent(window, 'load', initPage);
</script>
</head>
<body>
<form name="editor" method="post"><textarea name="text" wrap="soft" cols="80" rows="24" id="editor" class="editor" style="width: 100%;"></textarea><table border=0 cellspacing="0" cellpadding="0" width="100%">
<tr>
  <td width=100% align="right"><?php echo tep_image_button('button_save.gif', IMAGE_SAVE, 'onClick="update();"'); ?>&nbsp;<?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL, 'onClick="window.close();"'); ?></td>
</tr>
</table>
</form>
</body>
</html>