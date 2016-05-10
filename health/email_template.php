<?php
/*
  $Id: email_template.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  //##EMAIL_TITLE##
?>
<table cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td style="padding:20px 10px 40px">
            <table style="text-align:left;line-height:1.4" align="center" bgcolor="#ffffff" cellpadding="0" cellspacing="0" width="600">
                <tbody>
                    <tr>
                        <td>
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td>
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="line-height:0;width:490px" valign="top" width="490">
													<a href="http://www.healthcare4all.co.uk/?utm_source=email&utm_campaign=sales_survey&utm_medium=email" target="_blank">
														<img src="http://www.healthcare4all.co.uk/templates/images/email/logo.png" alt="Healthcare4all.co.uk" style="display:block" align="left" border="0" hspace="0" vspace="0" width="255" height="53" />
													</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="font-size:0;line-height:0;border-bottom:1px solid #dddddd" colspan="3" height="20"><br>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding-top:30px; padding-bottom:30px;">
							##EMAIL_TEXT##
                        </td>
                    </tr>
					<tr>
						<td>
							<font style="font-size:14px;font-family:Arial,Helvetica,sans-serif;line-height:22px;color:#444444">
								Best regards,<br>
								Daniel, Claire and the team<br>
								<font style="color:#6ea23c;text-decoration:none;font-weight:bold">Healthcare4All Ltd</font>
							</font><br />
							<br />						
						</td>
					</tr>
                </tbody>
            </table></td></tr></tbody>
</table>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="padding:0px 0px 0px 0px;">
		<table cellpadding="0" cellspacing="0" width="100%" style="background-color:#fafafa" bgcolor="#fafafa">
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td width="100%" align="center">
				<table cellpadding="0" cellspacing="0" align="center" border="0">
					<tbody>
						<tr>
							<td style="text-align: center;" width="75">
								<a href="https://twitter.com/healthcare4"><img src="http://www.healthcare4all.co.uk/templates/images/email/footer-twitter.png" alt="twitter" border="0" height="44" width="44" style="margin:0px 25px 0px 25px;" /></a>
							</td>
							<td style="text-align: center;" width="75">
								<a href="https://www.facebook.com/Healthcare4all"><img src="http://www.healthcare4all.co.uk/templates/images/email/footer-facebook.png" alt="facebook" border="0" height="44" width="44" style="margin:0px 25px 0px 25px;" /></a>
							</td>
							<td style="text-align: center;" width="75">
								<a href="https://plus.google.com/+Healthcare4allCoUk/"><img src="http://www.healthcare4all.co.uk/templates/images/email/footer-googleplus.png" alt="google plus" border="0" height="44" width="44" style="margin:0px 25px 0px 25px;" /></a>
							</td>
							<td style="text-align: center;" width="75">
								<a href="http://www.healthcare4all.co.uk/blog/"><img src="http://www.healthcare4all.co.uk/templates/images/email/footer-blog.png" alt="blog" border="0" height="44" width="44" style="margin:0px 25px 0px 25px;" /></a>
							</td>							
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>										
		<tr>
			<td style="font:12px Arial,Helvetica,sans-serif;color:#818285;padding:0 0 5px 0;text-align:left; background-color:#f3f3f3;" align="center">
				<table align="center" cellpadding="0" cellspacing="0" width="600" style="background-color:#f3f3f3; text-align:left;line-height:1.4;" bgcolor="#f3f3f3">
				<tr>
					<td align="left" style="line-height:18px;">
						<br />
						<font style="font-size:11px;font-family:Arial,Helvetica,sans-serif;line-height:18px;color:#888888">
							Copyright 2014 <a href="http://www.healthcare4all.co.uk" style="color:#105aa3; text-decoration:none;"><span style="color:#105aa3; text-decoration:none;">Healthcare4All.co.uk</span></a><br>
							Telephone: 0113 350 5432<br>
							<br>
							You have received an email because you recently purchased a product from <a href="http://www.healthcare4all.co.uk" style="color:#105aa3; text-decoration:none;"><span style="color:#105aa3; text-decoration:none;">Healthcare4All.co.uk</span></a>.  Please add our email address 
							<a href="mailto:sales@healthcare4all.co.uk" style="color:#105aa3; text-decoration:none;"><span style="color:#105aa3; text-decoration:none;">(sales@healthcare4all.co.uk)</span></a> to your safe list to ensure you receive emails from us in the future.							
						</font><br />						
						<br />
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</html>
</body>