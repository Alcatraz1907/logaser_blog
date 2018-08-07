<?php
$apiKey = get_option("SG_MAILCHIMP_API_KEY");
$status = SGPBMailChimp::apiKeyStatus($apiKey);
$apiKeySecretView = PBMFunctions::getApiKeySeccretView($apiKey);
?>
<form action="<?php echo SG_APP_POPUP_ADMIN_URL;?>admin-post.php?action=save_api_key" method="post">
	<h3>MailChimp API Settings</h3>

	<table class="form-table">

		<tbody>
			<tr valign="top">
				<th scope="row">Status</th>
				<td>
					<?php if(!$status): ?>
						<span class="sg-mailchimp-connect-status sg-mailchimp-not-connected">NOT CONNECTED</span>
					<?php else : ?>
						<span class="sg-mailchimp-connect-status sg-mailchimp-connected">CONNECTED</span>
					<?php endif;?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mailchimp_api_key">API Key</label></th>
				<td>
					<div class="sg-apikey-inputs-wrapper">
						<div class="sg-apikey-input-div">
							<input type="password" class="widefat" placeholder="Your MailChimp API key" id="mailchimp_api_key" name="mailchimp-api-key" value="<?php echo esc_attr($apiKey);?>">
						</div>
						<div class="sg-show-apikey-div">
							<input type="checkbox" id="sg-show-maichimp-apikey"><span>Show</span>
						</div>
						<div class="clear"></div>
						<p class="help">The API key for connecting with your MailChimp account.<a target="_blank" href="https://admin.mailchimp.com/account/api">Get your API key here.</a></p>
					</div>
					
				</td>

			</tr>

		</tbody>
	</table>

	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
</form>