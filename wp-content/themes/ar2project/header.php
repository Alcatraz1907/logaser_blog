<?php
/**
 * AR2's main header template.
 *
 * @package AR2
 * @since 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes() ?>>

<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />

<?php if ( is_search() || is_author() ) : ?>
<meta name="robots" content="noindex, nofollow" />
<?php endif ?>

<title><?php ar2_document_title() ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ) ?>" />

<?php
wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.custom.min.js', null, '2012-07-08' );
wp_enqueue_script( 'tinynav', get_template_directory_uri() . '/js/tinynav.min.js', array( 'jquery' ), '2012-08-02' );

if ( is_singular() ) :
wp_enqueue_style( 'colorbox_css', get_template_directory_uri() . '/css/colorbox.css', null, '2012-08-04' );
wp_enqueue_script( 'colorbox', get_template_directory_uri() . '/js/jquery.colorbox.min.js', array( 'jquery' ), '2012-08-04' );
endif;

if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
if(!isset($_GET['wpamp']) && wp_is_mobile()){
    wp_redirect('?wpamp');
}
?>

<?php
wp_head();
/********************************************************************************************************************/
// include custom style
wp_enqueue_style( 'custom_style', get_template_directory_uri() . '/css/custom_style.css', null, '2013-07-24' );
wp_enqueue_script( 'logaster_js', get_template_directory_uri() . '/js/botstrap.js', array( 'jquery' ), '2017-05-12' );

/********************************************************************************************************************/
?>
   <link rel='stylesheet' id='site_bt5-css'  href=' <?=get_template_directory_uri() . '/css/site_bt5.css'?>' type='text/css' media='all' />
		<!-- Google Analytics -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-18930150-1']);
			_gaq.push(['_trackPageview']);

			(function()
			{
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
		<!-- Google Analytics -->
</head>

<body <?php body_class() ?>>
<?php ar2_body() ?>

<div id="wrapper">

<nav id="top-menu" class="clearfix" role="navigation">
<?php ar2_above_top_menu() ?>
	<?php
	wp_nav_menu( array(
		'sort_column'		=> 'menu_order',
		'menu_class' 		=> 'menu clearfix',
		'theme_location' 	=> 'top-menu',
		'container'			=> false,
		'fallback_cb' 		=> ''
	) );
	?>
<?php ar2_below_top_menu() ?>
</nav><!-- #top-menu -->

<header role="banner">
    <div class="logaster">
        <nav class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">
                        <img itemprop="logo" src="<?php echo get_template_directory_uri(); ?>/images/head_baner/logo.png" alt="Logo Logaster">
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="/logo/" class="dropdown-toggle" data-toggle="dropdown"><?php _e( 'Create') ?><span
                                        class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/logo/"
                                       onclick="logasterTrack.trackGA('Header', 'Create', 'CreateLogo');"><?php _e( 'Logo')?></a></li>
                                <li><a href="/business-card/"
                                       onclick="logasterTrack.trackGA('Header', 'Create', 'CreateBusinessCard');"><?php _e( 'Business card')?></a></li>
                                <li><a href="/envelope/"
                                       onclick="logasterTrack.trackGA('Header', 'Create', 'CreateEnvelope');"><?php _e( 'Envelope')?></a></li>
                                <li><a href="/letterhead/"
                                       onclick="logasterTrack.trackGA('Header', 'Create', 'CreateBlankForm');"><?php _e( 'Letterhead/Fax')?>
                                        cover</a></li>
                                <li><a href="/favicon/"
                                       onclick="logasterTrack.trackGA('Header', 'Create', 'CreateFavicon');"><?php _e( 'Favicon')?></a></li>

                            </ul>
                        </li>

                        <li><a href="/pricing/"
                               onclick="logasterTrack.trackGA('Header', 'Price');"><?php _e( 'Price')?></a></li>

                        <li class="about-us-menu-item"><a href="/about-logaster/"
                                                          onclick="logasterTrack.trackGA('Header', 'AboutUs');"><?php _e( 'About us')?></a>
                        </li>
                        <li class="blog-menu-item active"><a href="/blog/"
                                                             onclick="logasterTrack.trackGA('Header', 'Blog');"><?php _e( 'Blog')?></a></li>

                        <li><a href="/logo-inspiration/" onclick="logasterTrack.trackGA('Header', 'LogoInspiration');"><?php _e( 'Inspiration')?></a>
                        </li>

                        <li><a href="/gallery/" onclick="logasterTrack.trackGA('Header', 'Gallery');"><?php _e( 'Gallery')?></a></li>
                        <li><a href="/about-logaster/contact-us/"><?php _e( 'Support')?></a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">

                        <li><a href="/account/sso/" rel="nofollow"
                               onclick="logasterTrack.trackGA('Header', 'Login');"><?php _e( 'Sign in')?></a></li>

                    </ul>
                    <div id="authInfoServer" style="display: none;">false</div>
                </div>
            </div>
        </nav>

        <!-- info -->
        <div class="container-fluid">
            <div class="gallery-subject-page">
                <div class="row">
                    <div class="create-logo-section text-center">
                        <h2><?php _e( 'How to create a logo?')?></h2>
                        <p class="col-md-8 col-md-offset-2"><?php _e( "In just a couple of minutes, Logaster can create multiple logo options for your business. All you need to do is change your selected emblem to address your needs and download it! Click on the 'Create' button and pick the best logos to reflect your brand identity.")?></p>

                        <section class="row bottom-create-logo text-center">
                            <div class="col-md-12 bottom-create-logo">
                                <a href="/logo/" class="btn create-logo btn-lg" ga-action="TryItBtn" ga-data="BottomCreateBTN"><?php _e( 'Create a logo')?></a>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
</header><!-- #header -->
</div>

<?php ar2_above_nav() ?>
<nav id="main-nav" role="navigation">
	<?php
	wp_nav_menu( array(
		'sort_column'	=> 'menu_order',
		'menu_class' 	=> 'menu clearfix',
		'theme_location'=> 'main-menu',
		'container'		=> false,
		'fallback_cb'	=> 'ar2_nav_fallback_cb'
	) );
	?>
</nav><!-- #nav -->
<?php ar2_below_nav() ?>

<?php ar2_above_main() ?>

<div id="main" class="clearfix">
   <div id="container" class="clearfix">
