<title><?php wp_title( '', true, 'right' ); ?></title>
<link rel="canonical" href="<?php echo wpamp_canonical_link(); ?>">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,minimum-scale=1">
<meta name="description" content="<?= wp_title('',true, 'right')?>">
<?php
	do_action('wpamp_favicon_icon'); 
	do_action('wpamp_head_json_script'); 
	do_action('wpamp_custom_style'); 
	do_action('wpamp_custom_script'); 
	do_action('wpamp_google_analytics_script'); 
?>
</head>
<body>
<amp-sidebar id="sidebar" layout="nodisplay" side="right">
    <div class="close-sidebar" tabindex="0" on="tap:sidebar.close" role="button"><amp-img class="amp-close-image" src="<?=WPAMP_PLUGIN_URL ;?>images/close-flat.png" width="8" height="14" alt="Close Sidebar"></amp-img></div>
	<?php wp_nav_menu( array( 'menu' => 'wpamp_menu', 'depth' => 3, 'menu_class' => 'menu' ) ); ?>
</amp-sidebar>
<header class="container">
    <div id="header">
        <div class="brand">
            <a href="<?php bloginfo('url'); echo '/?' . AMP_CONSTANT; ?>">
                <?php 
                    $logoid = get_option( 'logoid' );
                    if( get_option( 'amp_header' ) == '1' && $logoid != '' ) { 
                        $logo_array = wp_get_attachment_image_src( $logoid, 'full', false );
                        echo '<amp-img src="' . $logo_array[0] . '" class="sitelogo" width="' . $logo_array[1] . '" height="' . $logo_array[2] . '"></amp-img>';
                    } else {
                        bloginfo( 'name' );
                    }
                ?>
            </a>
        </div>
        <div class="menuicon"><amp-anim src="<?=WPAMP_PLUGIN_URL ;?>images/menu-icon.gif" width="20" height="20" on="tap:sidebar.open" role="button" tabindex="0"></amp-anim></div>
    </div>
</header>
<div class="clearfix"></div>
<section role="main">