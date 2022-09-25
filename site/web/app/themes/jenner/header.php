<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="utf-8">

        <?php if (is_front_page()): ?>
        <title>Welcome to <?php bloginfo('name') ?> - San Jose, CA</title>
        <?php else: ?>
        <title><?php wp_title(''); echo ' – ';  bloginfo( 'name' ); ?></title>
        <?php endif; ?>

		<meta name="viewport" content="width=device-width, initial-scale=1"/>

        <link rel="preconnect" href="https://awakening.azureedge.net">
        <link rel="preload" href="/app/themes/jenner/build/font/Montserrat-Regular.woff2" as="font" type="font/woff2" crossorigin> 
        <link rel="preload" href="/app/themes/jenner/build/font/Montserrat-Bold.woff2" as="font" type="font/woff2" crossorigin> 
        <link rel="preload" href="/app/themes/jenner/build/font/Oswald-Regular.woff2" as="font" type="font/woff2" crossorigin> 

		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<div id="container">

			<header class="header" role="banner">

				<div id="inner-header" class="clearfix">

                    <div class="wrap">

                        <?php block_template_part( 'header' ); ?>

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
                $is_active_teaching_item = ac_is_teaching_active($announcement);

                $video_url = get_permalink($announcement);

                if (!$live_start) continue;

                if ($live_start > time()):
        ?>
            <div class="announce announce-upcoming" data-livestart="<?php echo $live_start; ?>">
                <p class="announce-u">
                    <a href="<?php echo esc_html($video_url); ?>">
                        <b>Upcoming Live Stream</b><br><span><i class="announce-when">happening soon</i></span>
                    </a>
                </p>
            </div>
        <?php elseif (ac_is_teaching_live($announcement) && get_queried_object_id() !== $announcement->ID): ?>
            <div class="announce announce-sunday announce-sunday-live">
                <p class="announce-u">
                    <a href="<?php echo esc_html($video_url) ?>">
                        <b>Live</b> <span><em><?php echo esc_html(get_the_title($announcement)); ?></em> streaming live</span>
                        <span class="button button-arrow">Watch Now</span>
                    </a>
                </p>
            </div>
        <?php endif; ?>
        <?php if ($is_active_teaching_item && get_queried_object_id() !== $announcement->ID && get_post_meta($announcement->ID, 'teaching-notes', true)): ?>
            <div class="announce announce-sunday announce-sunday-notes">
                <p class="announce-u">
                    <a href="<?php echo esc_html(get_permalink($announcement)); ?>#teaching-notes">
                        <b>Sermon Notes</b> <span>Read <em><?php echo esc_html(get_the_title($announcement)); ?></em> notes and scripture</span>
                        <span class="button button-arrow">Read Now</span>
                    </a>
                </p>
            </div>
        <?php endif; ?>
        <?php endforeach; wp_reset_postdata(); ?>