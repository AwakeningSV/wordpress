<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // Google Chrome Frame for IE ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <?php if (is_front_page()): ?>
        <title>Welcome to <?php bloginfo('name') ?> - San Jose, CA</title>
        <meta name="description" content="<?php bloginfo('description'); ?>">
        <?php else: ?>
       <title><?php wp_title(''); echo ' – ';  bloginfo( 'name' ); ?></title>
        <?php endif; ?>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

        <?php /* Load html5shiv for old IE.
               * Conditional load possible to do with WP APIs, see https://core.trac.wordpress.org/ticket/16024#comment:59 */ ?>
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/library/js/html5shiv.js"></script>
        <![endif]-->

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>

	</head>

	<body <?php body_class(); ?>>

		<div id="container">

			<header class="header" role="banner">

				<div id="inner-header" class="clearfix">

                    <div class="wrap">
					<?php // to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> ?>
					<p id="logo" class="h1"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></p>

					<?php // if you'd like to use the site description you can un-comment it below ?>
					<?php // bloginfo('description'); ?>


                    <a href="#menu" class="main-menu-link" id="menuLink"><span></span></a>
                    <nav role="navigation" id="menu">
                        <div class="pure-menu pure-menu-open">
                            <?php bones_main_nav(); ?>
                        </div>
					</nav>
                    </div>

				</div> <?php // end #inner-header ?>

            </header> <?php // end header ?>

        <?php
            $announcements = z_get_posts_in_zone('upcoming-livestream', array(
                'posts_per_page' => 1,
                'post_type' => 'any',
                'post_status' => 'publish'
            ), false);

            foreach($announcements as $announcement) :

                $live_start = (int) get_post_meta($announcement->ID, 'live_time', true);
                if (!$live_start || $live_start > time()) :
        ?>
            <div class="wrap clearfix">
            <p class="announce announce-upcoming" data-livestart="<?php echo $live_start; ?>">
                    <a href="<?php echo esc_html(get_permalink($announcement)); ?>">
                    <b>Upcoming Live Stream</b>&nbsp;&mdash; &ldquo;<?php echo esc_html(get_the_title($announcement)); ?>&rdquo; service broadcast begins <span class="announce-when">soon</span>
                    </a>
                </p>
            </div>
        <?php else: ?>
			<?php
                // If live video is not embedded, hide this message
                // if (!has_shortcode($announcement->post_content, 'live')) :
			?>
	            <div class="wrap clearfix">
	                <p class="announce announce-live">
	                    <a href="<?php echo esc_html(get_permalink($announcements[0])); ?>">
	                    <b>Watch Live Now</b>&nbsp;&mdash; &ldquo;<?php echo esc_html(get_the_title($announcements[0])); ?>&rdquo; service broadcast live from San Jose, CA
	                    </a>
	                </p>
	            </div>
	        <?php // endif; ?>
        <?php endif; ?>
        <?php endforeach; wp_reset_postdata(); ?>