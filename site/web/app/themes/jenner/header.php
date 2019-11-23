<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="utf-8">

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
                'post_type' => 'teaching',
                'post_status' => 'publish'
            ), false);

            foreach($announcements as $announcement): 
                $teaching_date = (int) get_post_meta($announcement->ID, 'teaching-date', true);

                if (!$teaching_date) continue;
                
                $teaching_gmt = new DateTime();
                $teaching_gmt->setTimestamp($teaching_date);
                $teaching_day = $teaching_gmt->format('Y-m-d');
                $teaching_local = new DateTime($teaching_day, new DateTimeZone('America/Los_Angeles'));

                $completes = (int) get_post_meta($announcement->ID, 'stream-completed', true);

                if ($completes == 0) {
                    $teaching_local->modify('+9 hours');
                    $teaching_local->modify('+30 minutes');
                    $live_start = $teaching_local->getTimestamp();
                } else if ($completes == 1) {
                    $teaching_local->modify('+11 hours');
                    $teaching_local->modify('+15 minutes');
                    $live_start = $teaching_local->getTimestamp(); 
                } else {
                    // No more for this post.
                    continue;
                }

                if (!$live_start || $live_start > time()) :
        ?>
            <div class="announce announce-upcoming" data-livestart="<?php echo $live_start; ?>">
                <p class="announce-u">
                    <a href="<?php echo esc_html(get_permalink($announcement)); ?>">
                        <b>Upcoming Live Stream</b><br><?php echo esc_html(get_the_title($announcement)); ?> begins <span class="announce-when">soon</span>
                    </a>
                </p>
            </div>
        <?php else: ?>
			<?php
                // If live video is not embedded, hide this message
                // if (!has_shortcode($announcement->post_content, 'live')) :
			?>
                <div class="announce announce-live">
                    <p class="announce-u">
                        <a href="<?php echo esc_html(get_permalink($announcements[0])); ?>">
                            <b>Live</b> <span><em><?php echo esc_html(get_the_title($announcements[0])); ?></em> streaming live</span>
                            <span class="button button-arrow">Watch Now</span>
                        </a>
                    </p>
                </div>
	        <?php // endif; ?>
        <?php endif; ?>
        <?php endforeach; wp_reset_postdata(); ?>