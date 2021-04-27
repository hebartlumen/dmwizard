<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Inkness
 */
?>
<?php
global $message;
// check for form submission - if it doesn't exist then send back to contact form
if ( isset($_POST["submit"]) && $_POST["name"] ) {
    // Trigger action/function 'enquiry_send_message'
    do_action( 'enquiry_send_message' );
    //$message = 'Your Message Sent Successfully';
    //if($message){ echo $message; }
    wp_redirect( 'https://www.dmwizard.in/blog/thankyou', 301 ); exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?php wp_title( '|', true, 'right' ); ?>
</title>
<link rel="profile" href="https://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-89411439-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body <?php body_class(); ?>>
<div id="parallax-bg"></div>
<div id="page" class="hfeed site">
<?php do_action( 'inkness_before' ); ?>
<div id="header-top">
	<div class="wrap wrap-head">
  <header id="masthead" class="wrap-in" role="banner">
    <div class="col-md-3 col-sm-3 col-xs-12 logo">
      <?php if((of_get_option('logo', true) != "") && (of_get_option('logo', true) != 1) ) { ?>
      <a href="<?php /*echo esc_url( home_url( '/' ) );*/ echo 'https://www.dmwizard.in'; ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
      <?php
				echo "<img class='main_logo' src='".of_get_option('logo', true)."' title='".esc_attr(get_bloginfo( 'name','display' ) )."'></a>";	
				}
			else { ?>
      <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
        <?php bloginfo( 'name' ); ?>
        </a></h1>
      <?php	
			}
			?>
    </div>
    <div class="col-md-9 col-sm-9 col-xs-12 logo-right">
      <div class="col-md-9 col-sm-9 col-xs-9 mail-number-main">
        <div class="mail-number">
          <div class="mails"><a href="mailto:info@dmwizard.in"><span class="icon-block"><i aria-hidden="true" class="fa fa-envelope-o"></i></span> info@dmwizard.in</a></div>
          <div class="number"><a href="JavaScript:void(0);"><span class="icon-block"><i aria-hidden="true" class="fa fa-phone"></i></span> +919061645457 | +919061645458</a></div>
        </div>
      </div>
      
      <div class="col-md-3 col-sm-3 col-xs-3 enquire-box-main">
        <div class="enquire-form" id="enquire-form">
          <h4>Enquire Now <span class="form-close" id="form-close">x</span></h4>
          <form action="" method="post">
            <input name="name" placeholder="Name required">
            <input name="email" placeholder="Email" required type="email">
            <input name="location" placeholder="Location" required>
            <input name="number" placeholder="Number" required maxlength="10" pattern="[0-9]{1,10}" title="Accepts Only Numbers">
            <textarea cols="30" name="message" placeholder="Message" required rows="10"></textarea>
            <button class="submit" type="submit" name="submit" value="submit">Submit</button>
          </form>
        </div>
        <div class="enquire-box"><span class="enquire-now" id="enquire-now"><a href="javascript:void(0)">Enquire Now</a></span></div>
        
      </div>
    </div>
    
    <!--<?php get_template_part('social', 'fa'); ?>--> 
    
  </header>
  </div>
  <!-- #masthead --> 
</div>
<div class="clearfix"></div>
<div id="header-2">
  <div class="container"> 
    <!-- search
			<div id="top-search" class="col-md-4 col-xs-12">
				<?php get_search_form(); ?>
			</div>
			 -->
    <div class="default-nav-wrapper col-md-12 col-xs-12 nav-center">
      <nav id="site-navigation" class="main-navigation " role="navigation">
        <div id="nav-container nav-main">
          <h1 class="menu-toggle">Menu <button class="collapsed navbar-toggle toggle-icon" type="button" aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse"><span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button></h1>
          <div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'inkness' ); ?>">
            <?php _e( 'Skip to content', 'inkness' ); ?>
            </a></div>
          <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
        </div>
      </nav>
      <!-- #site-navigation --> 
    </div>
  </div>
</div>
<?php get_template_part('slider', 'nivo'); ?>

<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
  <div class="container">
    <?php if(function_exists('bcn_display'))
    {
        bcn_display();
    }?>
  </div>
</div>
<?php if ( function_exists('yoast_breadcrumb') ) 
{yoast_breadcrumb('<p id="breadcrumbs">','</p>');} ?>


<div id="content" class="site-content row clearfix clear">
<div class="container col-md-12">
