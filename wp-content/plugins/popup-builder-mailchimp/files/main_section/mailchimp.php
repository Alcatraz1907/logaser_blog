<?php
$mailchimpContent = '';

if (isset($_GET['id'])) {
	/* if edit mode */
	$mailchimpContent = $result->getContent();
}
?>
<div class="sg-wp-editor-container">
<?php
	$content = @$mailchimpContent;
	$editorId = 'sg_popup_mailchimp';
	$settings = array(
		'wpautop' => false,
		'tinymce' => array(
			'width' => '100%',
		),
		'textarea_rows' => '6',
		'media_buttons' => true
	);
	wp_editor($content, $editorId, $settings);
?>
</div>
