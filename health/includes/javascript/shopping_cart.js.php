<script language="javascript" type="text/javascript">
<!--
function changeCartQty(ctrl, delta){
  var qtyCtrl = document.getElementById('cart_qty'+ctrl);
  if ( qtyCtrl ) {
    qtyCtrl.value = parseInt(qtyCtrl.value) + delta;
    if ( parseInt(qtyCtrl.value)<=0 ) {
      var removeCtrl = document.getElementById('cart_delete'+ctrl);
      if (typeof removeCtrl.checked != 'undefined') removeCtrl.checked=true;
    } 
    document.cart_quantity.submit(); 
  }
}
//-->
</script>