<?php 
$mailerContent = '
<div style="margin: auto auto; width: 610px;">
	<table width="600px" border="1" cellpadding="0" style="min-height: 400px; margin: auto auto;" cellspacing="0">
		<tr style="padding: 5px; border-bottom: solid 1px #ccc;">
			<td colspan="4" align="center" style="padding: 10px;">
				<h1 style="margin-bottom: 0px; margin-top:0px">'.config_item('site_name').'</h1>
				<hr style="border: dashed 1px #ccc;">
				<div style="font-family: Calibri Light; background: #9932cc; font-size: 20px; padding: 5px;color: white; text-transform: uppercase; font-weight; bolder">
				<strong>subject</strong>
				</div>
				<hr style="border: dashed 1px #ccc;">
			</td>
		</tr>

		<tr>
			<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase;"><strong>Product</strong>?>
			#FORM THE MESSAGE TO BE SENT TO THE USER
                    $message = \'Hi \'.$fullname.\'<br>You have requested to reset your password at '.config_item('site_name').'
                    <br><br>The scoreg are your login details:<br>
                   <br><br>Before you can reset your password please follow this link.<br><br>
                    <a class="alert alert-success" href="'.$config->base_url().'verify?pwd&tk='.$request_token.'">Click Here to Reset Password</a>
                    <br><br>If it does not work please copy this link and place it in your browser url.<br><br>
			</td>
		</tr>
	</table>
	<table width="600px">
		<tbody style="text-align: center;">
			<tr>
				<td colspan="4">
					<hr style="border: dashed 1px #ccc; text-align: center;">
					<img width="150px" src="urlassets/images/logo.png"  alt="logo-small" class="logo-sm">
				</td>
			</tr>
		</tbody>
	</table>
</div>';
print $mailerContent;
?>