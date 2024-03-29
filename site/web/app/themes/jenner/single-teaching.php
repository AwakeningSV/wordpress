<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="eightcol first clearfix" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                            <?php
                                $series = get_the_terms($post->ID, 'series');
                                $series = $series[0];

                                if ($series) {
                                    $query = new WP_Query(array(
                                        'series' => $series->slug,
                                        'posts_per_page' => -1,
                                        'post_status' => 'publish',
                                        'meta_key' => 'teaching-date',
                                        'orderby' => 'meta_value_num',
                                        'order' => 'ASC',
                                        'date_query' => array(
                                            // Series may repeat by year, so only count
                                            // posts in this year. This will not work if
                                            // series continue over a year boundary,
                                            // but this is unlikely to ever occur.
                                            'year' => get_the_date('Y', $post->ID)
                                        ),
                                        'fields' => 'ids' // get only post ids
                                    ));

                                    $series_position = array_search($post->ID, $query->posts) + 1;
                                }
                            ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

<?php

ac_backfill_podcast_episode($post->ID, false);
ac_backfill_podcast_episode($post->ID, true);

$event_presented_date = get_post_meta($post->ID, 'teaching-date', true);
$live_time = ac_get_teaching_live_time($post);
$year = (int) date('Y', $event_presented_date);

$itunes_artwork = false;

$should_use_itunes_artwork = WP_ENV === 'development' || $year >= 2019 || $year === 2012;

if ($should_use_itunes_artwork) {
    $itunes_artwork = ac_get_podcast_artwork($post);
}

$audio_episode = powerpress_get_enclosure_data($post->ID, 'podcast');
$video_episode = powerpress_get_enclosure_data($post->ID, 'video');

if ($itunes_artwork) {
    $artwork = array($itunes_artwork, '892', '892', '');
} else {
    $artwork = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
}

$post_content = get_the_content();
$video_pattern = '/https?:\/\/[^\s"]+\s*$/im';

// Find a speciifc video URL by looking for oEmbed URLs.
if (preg_match( $video_pattern, $post_content, $matches) ) {
    // If we found a valid oEmbed, save it as the video URL.
    if (wp_oembed_get( $matches[0] )) {
        $oembed_first = $matches[0];

        // Look for a second one after the first.
        if (preg_match( $video_pattern, $post_content, $matches, 0, strlen( $matches[0] ) )) {
            if (wp_oembed_get($matches[0])) {
                // Second service video.
                $oembed_second = $matches[0];
            }
        }
    }

    if ($oembed_second && ac_get_service_count( $post ) === 2) {
        $oembed = $oembed_second;
    } else {
        $oembed = $oembed_first;
    }

    if ($oembed) {
        // Apply Fluid Video Embed filtering (do not use wp_oembed_get HTML directly)
        $video_html = apply_filters( 'the_content', $oembed );
    }

    $post_content = preg_replace( $video_pattern, '', $post_content );
}

$post_content = apply_filters( 'the_content', $post_content );

if (!$video_html && !empty($video_episode)) {
    $src = $video_episode['url'];

    $video_html = "<div class='video-wrap'><video src='${src}' type='video/mp4' controls poster='{$artwork[0]}'></video></div>";
}

$options_exist = $video_html && !empty($audio_episode);

$default_media = $video_html ? 'video' : 'audio';

function media_selection_class($type) {
    global $default_media;
    $selected = 'teaching-content-option-selected';
    if ($default_media == $type) echo $selected;
}

function media_selection_pressed() {
    global $default_media;
    echo ($default_media == $type) ? 'true' : 'false';
}

function get_media_session_artwork($url) {
    $key_bin = pack("H*" , env('IMGPROXY_KEY'));
    $salt_bin = pack("H*" , env('IMGPROXY_SALT'));
    $path = "/rs:fit:600:600/plain/{$url}";
    $signature = rtrim(strtr(base64_encode(hash_hmac('sha256', $salt_bin.$path, $key_bin, true)), '+/', '-_'), '=');
    $url = "https://awakeningimage.azureedge.net/{$signature}{$path}";

    return array($url, '600', '600');
}
?>
                            <div class="teaching-header-single">
                                <ul class="teaching-taxonomy-header">
                                    <li class="teaching-section-byline"><a href="/">Home</a></li>
                                    <li class="teaching-section-byline"><a href="/teaching/">Sermons</a></li>
                                    <?php if ($series): ?>
                                    <li class="teaching-section-byline series">
                                        <span>
                                            Part <? echo $series_position ?> of
                                            <?php the_terms( $post->ID, 'series', '', ', ', ' ' ); ?>
                                        </span>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                                <?php if ($options_exist): ?>
                                <div class="teaching-content-option">
                                    <?php if ($video_html): ?>
                                        <button role="switch" aria-label="Watch the sermon video" class="<?php media_selection_class('video'); ?>" aria-checked="<?php media_selection_pressed(); ?>">Video</button>
                                    <?php endif; ?>
                                    <?php if (!empty($audio_episode)): ?>
                                        <button role="switch" aria-label="Listen to this sermon" class="<?php media_selection_class('audio'); ?>" aria-checked="<?php media_selection_pressed(); ?>">Audio</button>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="teaching-theater teaching-theater-<?php echo $default_media; ?>">

                                <?php if ($itunes_artwork): ?>
                                    <img class="teaching-podcast-image" src="<?php echo $artwork[0] ?>" alt="">
                                <?php endif; ?>

                                <div class="teaching-theater-body">

                                    <header class="article-header">

                                        <h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>

                                        <p class="byline" data-artwork='<?php echo json_encode(get_media_session_artwork($artwork[0])); ?>'>
                                            <span class="teachers">
                                                <?php the_terms( $post->ID, 'teachers', '', ', ', ' &bull; ' ); ?>
                                            </span>
                                            <?php printf('<time class="presented" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_presented_date), date(get_option('date_format'), $event_presented_date)); ?>
                                        </p>

                                    </header>
                                    <?php if ($video_html): ?>
                                        <div class="teaching-video no-print">
                                            <?php echo $video_html; ?>
                                        </div> 
                                    <?php endif; ?>

                                    <?php if (!empty($audio_episode)) : ?>
                                        <div class="teaching-podcast-player teaching-meta">
                                                <audio controls src="<?php echo $audio_episode['url']; ?>" type="audio/mp3" preload="metadata"></audio>
                                                <ul class="teaching-podcast-actions">
                                                    <li>
                                                        <a href="/podcasts/">
                                                            Subscribe to our podcast
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo esc_url($audio_episode['url']); ?>" rel="nofollow">
                                                            Download MP3
                                                        </a>
                                                        <span class="teaching-podcast-meta">
                                                            <?php echo powerpress_readable_duration($audio_episode['duration']); ?>
                                                            /
                                                            <?php echo powerpress_byte_size($audio_episode['size']); ?>
                                                        </span>
                                                    </li>
                                                </ul>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="teaching-content">
                                    <?php echo $post_content; ?>
                                </div>
                                <?php if ($post->content == '') : ?>
                                    <?php if ($event_presented_date > time() && $live_time) : ?>
                                        <p>This sermon will be presented
                                        <?php printf('<time class="presented" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_presented_date), date(get_option('date_format'), $event_presented_date)); ?>.
                                        Check back here to watch live online or <a href="/visit/">plan a visit</a>.</p>
                                    <?php endif; ?>
                                <?php endif; ?>

								<?php
								    $scriptures = get_the_terms($post->ID, 'bible');
									$terms = get_the_terms($post->ID, 'series');

									$term = new stdClass();
									if (!empty($terms)) {
										$term = array_pop($terms);
									}
								?>

                                <div class="no-print">
                                <?php block_template_part( 'teaching-sidebar' ); ?>
                                </div>
 
                                <?php
                                    $teaching_notes = get_post_meta($post->ID, 'teaching-notes', true);
                                ?>

                                <div class="teaching-meta">
                                    <?php if (!empty($video_episode)) : ?>
                                        <h3>Download</h3>
                                        <ul>
                                            <?php if (!empty($video_episode)) : ?>
                                                <li>
                                                    <a href="<?php echo esc_url($video_episode['url']); ?>" rel="nofollow">
                                                        Video MP4
                                                    </a>
                                                    <span class="teaching-podcast-meta">
                                                        <?php echo powerpress_readable_duration($video_episode['duration']); ?>
                                                        /
                                                        <?php echo powerpress_byte_size($video_episode['size']); ?>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php if (!empty($scriptures)) : ?>
                                        <h2>Scripture</h2>
                                        <ul>
                                            <?php foreach($scriptures as $reference) : ?>
                                                <li><?php echo $reference->name; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>

							<?php 
                                $teaching_posts = array();

                                if ($term->name) {
									$teaching_query = new WP_Query(array(
									    'post_type' => 'teaching',
										'tax_query' => array(
											array(
												'taxonomy' => 'series',
												'terms' => $term->term_id
											)
										),
									    'meta_key' => 'teaching-date',
									    'orderby' => 'meta_value_num',
									    'order' => 'ASC',
									    'posts_per_page' => -1
									));

									$teaching_posts = $teaching_query->posts;
                                }

                                if (count($teaching_posts) > 1):
                            ?>
								<div class="teaching-more">
									<h3>More from <?php echo $term->name; ?></h3>
		                                <div class="archive-g">
		                                    <?php
		                                        foreach ($teaching_posts as $post):
		                                            include 'teaching-item.php';
		                                        endforeach;
		                                    ?>
		                                </div>
								</div>
                            <?php else: ?>
                                <div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
							<?php endif; ?>

                            <div class="no-print">
                                <?php block_template_part( 'teaching-footer' ); ?>
                            </div>

                                <?php if ($teaching_notes) : ?>
                                    <div id="teaching-notes">
                                        <h3>Teaching Notes</h3>
                                    </div>
                                    <div class="teaching-notes">
                                        <?php echo wpautop($teaching_notes); ?>
                                    </div>
                                <?php endif; ?>

							<?php endwhile; ?>

							</article> <?php // end article ?>

                            <?php else : ?>

									<article id="post-not-found" class="hentry clearfix">
										<header class="article-header">
											<h1><?php _e( 'Oops, Event Not Found!', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the page.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</div> <?php // end #main ?>

				</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>
