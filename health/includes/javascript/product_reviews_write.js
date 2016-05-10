<script language="javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var review = document.product_reviews_write.review.value;
	// SEO Reviews
	if(document.product_reviews_write.customer != null)
	{
	 var customer = document.product_reviews_write.customer.value;

   if (customer.length < <?php echo REVIEW_TEXT_FROM_LENGTH; ?>) {
     error_message = error_message + "<?php echo JS_REVIEW_CUSTOMER_FULL_NAME . '\n'; ?>";
     error = 1;
   }
  } 
  // SEO Reviews	
  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }

  if(document.product_reviews_write.robot != null)
	{
	 var robot = document.product_reviews_write.robot.value;

   if (robot.length < <?php echo REVIEW_TEXT_FROM_LENGTH; ?>) {
     error_message = error_message + "* <?php echo ENTRY_ROBOT_ERROR . '\n'; ?>";
     error = 1;
   }
  }

  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
    error = 1;
  }  

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
