<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="utf-8">

        <?php if (is_front_page()): ?>
        <title>Welcome to <?php bloginfo('name') ?> - San Jose, CA</title>
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
                $live_start = ac_get_teaching_live_time($announcement);
                $is_active_sunday_item = ac_is_sunday_teaching_active($announcement);

                if (!$live_start) continue;

                if ($live_start > time()):
        ?>
            <div class="announce announce-upcoming" data-livestart="<?php echo $live_start; ?>">
                <p class="announce-u">
                    <a href="https://www.youtube.com/awakeningsv" target="_blank">
                        <b>Upcoming Live Stream</b><br><span><?php echo esc_html(get_the_title($announcement)); ?> begins <i class="announce-when">soon</i></span>
                    </a>
                </p>
            </div>
        <?php elseif (ac_is_sunday_teaching_live($announcement)): ?>
            <div class="announce announce-sunday announce-sunday-live">
                <p class="announce-u">
                    <a href="https://www.youtube.com/awakeningsv" target="_blank">
                        <b>Live</b> <span><em><?php echo esc_html(get_the_title($announcement)); ?></em> streaming live</span>
                        <?php if (get_queried_object_id() !== $announcement->ID) : ?>
                            <span class="button button-arrow">Watch Now</span>
                        <?php endif; ?>
                    </a>
                </p>
            </div>
        <?php endif; ?>
        <?php if ($is_active_sunday_item): ?>
            <div class="announce announce-sunday announce-sunday-notes">
                <p class="announce-u">
                    <a href="<?php echo esc_html(get_permalink($announcements[0])); ?>#teaching-notes">
                        <b>Sermon Notes</b> <span>Read <em><?php echo esc_html(get_the_title($announcements[0])); ?></em> notes and scripture</span>
                        <?php if (get_queried_object_id() !== $announcement->ID) : ?>
                            <span class="button button-arrow">Read Now</span>
                        <?php endif; ?>
                    </a>
                </p>
            </div>
        <?php endif; ?>
        <?php endforeach; wp_reset_postdata(); ?>